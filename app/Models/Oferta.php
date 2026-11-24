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

    /** Tipos de descuento. 'precio' y 'cantidad' dependen de a qué aplica (ver MODOS_POR_APLICA). */
    public const MODOS = [
        'porcentaje' => 'Porcentaje',
        'precio' => 'Precio fijo por persona',
        'cantidad' => 'Monto de descuento al grupo',
    ];

    public const APLICA = ['persona' => 'persona individual', 'grupo' => 'grupo'];

    public const MODOS_POR_APLICA = [
        'persona' => ['porcentaje', 'precio'],
        'grupo' => ['porcentaje', 'cantidad'],
    ];

    protected $fillable = ['id_experiencia', 'aplica_a', 'grupo_personas', 'modo', 'valor', 'etiqueta', 'inicia', 'termina'];

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

    /** Ofertas de grupo vigentes, cargadas una sola vez por petición. */
    private static ?Collection $gruposCache = null;

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
            $ofertas = static::vigentes()->where('aplica_a', 'persona')->with('experiencia')->get();
        } catch (QueryException) {
            $ofertas = collect();
        }

        return static::$vigentesCache = $ofertas
            ->filter(fn ($o) => $o->experiencia !== null)
            ->sortBy(fn ($o) => $o->precio_oferta)
            ->unique('id_experiencia')
            ->keyBy('id_experiencia');
    }

    /** Ofertas de grupo vigentes, agrupadas por id_experiencia (cada una dice a qué grupo aplica). */
    public static function vigentesGruposPorExperiencia(): Collection
    {
        if (static::$gruposCache !== null) {
            return static::$gruposCache;
        }

        try {
            $ofertas = static::vigentes()->where('aplica_a', 'grupo')->get();
        } catch (QueryException) {
            $ofertas = collect();
        }

        return static::$gruposCache = $ofertas->groupBy('id_experiencia');
    }

    /** Descuento de esta oferta sobre el precio total de un grupo. */
    public function aplicarAGrupo(float $total): float
    {
        if ($this->modo === 'cantidad') {
            return round(max(0, $total - (float) $this->valor), 2);
        }

        return round($total * (100 - (float) $this->valor) / 100, 2);
    }

    /** Para pruebas o después de guardar una oferta en la misma petición. */
    public static function olvidarCache(): void
    {
        static::$vigentesCache = null;
        static::$gruposCache = null;
    }

    /** Total del grupo al que aplica (solo ofertas de grupo); null si no es de grupo o ya no existe. */
    public function precioBaseGrupo(): ?float
    {
        $grupo = $this->experiencia?->grupos->firstWhere('personas', $this->grupo_personas);

        return $grupo ? (float) $grupo->precio : null;
    }

    /** Precio final con la oferta: por persona (persona individual) o total del grupo (grupo). */
    protected function precioOferta(): Attribute
    {
        return Attribute::get(function () {
            if ($this->aplica_a === 'grupo') {
                return $this->precioBaseGrupo() !== null ? $this->aplicarAGrupo($this->precioBaseGrupo()) : null;
            }

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
            if ($this->modo === 'porcentaje') {
                return (int) round((float) $this->valor);
            }

            $base = $this->aplica_a === 'grupo'
                ? (float) $this->precioBaseGrupo()
                : (float) $this->experiencia?->precio;

            return $base > 0 ? (int) round(100 - (float) $this->precio_oferta * 100 / $base) : 0;
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
