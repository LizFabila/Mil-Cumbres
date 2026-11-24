<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

/**
 * Oferta por tiempo limitado sobre una experiencia (tabla ofertas).
 * Vigente desde `inicia` hasta `termina`, ambos días incluidos.
 */
class Oferta extends Model
{
    use SoftDeletes;

    protected $table = 'ofertas';
    protected $primaryKey = 'id_oferta';

    public const MODOS = ['porcentaje' => 'Porcentaje', 'precio' => 'Precio fijo'];

    protected $fillable = ['id_experiencia', 'modo', 'valor', 'etiqueta', 'inicia', 'termina'];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'inicia' => 'date',
            'termina' => 'date',
        ];
    }

    /** Ofertas vigentes por experiencia, cargadas una sola vez por petición. */
    private static ?Collection $vigentesCache = null;

    public function experiencia(): BelongsTo
    {
        return $this->belongsTo(Experiencia::class, 'id_experiencia', 'id_experiencia');
    }

    /** Vigentes hoy: ya empezaron y todavía no terminan. */
    public function scopeVigentes(Builder $query): Builder
    {
        $hoy = today()->toDateString();

        return $query->whereDate('inicia', '<=', $hoy)->whereDate('termina', '>=', $hoy);
    }

    /**
     * La mejor oferta vigente de cada experiencia, indexada por id_experiencia.
     * Si la tabla aún no existe (falta correr las migraciones) regresa una colección vacía
     * para que el sitio siga funcionando sin ofertas.
     */
    public static function vigentesPorExperiencia(): Collection
    {
        if (static::$vigentesCache !== null) {
            return static::$vigentesCache;
        }

        try {
            $ofertas = static::vigentes()->with('experiencia')->get();
        } catch (QueryException) {
            $ofertas = collect();
        }

        return static::$vigentesCache = $ofertas
            ->filter(fn ($o) => $o->experiencia !== null)
            ->sortBy(fn ($o) => $o->precio_oferta)
            ->unique('id_experiencia')
            ->keyBy('id_experiencia');
    }

    /** Para pruebas o después de guardar una oferta en la misma petición. */
    public static function olvidarCache(): void
    {
        static::$vigentesCache = null;
    }

    /** Precio final por persona con la oferta aplicada. */
    protected function precioOferta(): Attribute
    {
        return Attribute::get(function () {
            $precio = (float) $this->experiencia?->precio;

            return $this->modo === 'precio'
                ? (float) $this->valor
                : round($precio * (100 - (float) $this->valor) / 100, 2);
        });
    }

    /** Porcentaje de descuento (redondeado) para el sello «−20 %». */
    protected function porcentaje(): Attribute
    {
        return Attribute::get(function () {
            $precio = (float) $this->experiencia?->precio;

            if ($this->modo === 'porcentaje') {
                return (int) round((float) $this->valor);
            }

            return $precio > 0 ? (int) round(100 - $this->precio_oferta * 100 / $precio) : 0;
        });
    }

    /** activa, programada o vencida. */
    protected function estado(): Attribute
    {
        return Attribute::get(function () {
            $hoy = today();

            return match (true) {
                $this->inicia->gt($hoy) => 'programada',
                $this->termina->lt($hoy) => 'vencida',
                default => 'activa',
            };
        });
    }

    /** Días que le quedan (0 = termina hoy). */
    protected function diasRestantes(): Attribute
    {
        return Attribute::get(fn () => (int) today()->diffInDays($this->termina, false));
    }
}
