<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Solicitud extends Model
{
    use SoftDeletes;

    public const ESTADOS = ['pendiente', 'confirmada', 'cancelada', 'completada'];

    protected $table = 'solicitudes';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'fecha_deseada',
        'id_persona',
        'cant_persona',
        'nota',
        'folio',
        'total',
        'estado'
    ];

    protected function casts(): array
    {
        return [
            'fecha_deseada' => 'date',
            'cant_persona' => 'integer',
            'total' => 'decimal:2',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function experiencias(): BelongsToMany
    {
        return $this->belongsToMany(
            Experiencia::class,
            'asigna_experiencia',
            'id_solicitud',
            'id_experiencia'
        )
            ->using(AsignaExperiencia::class)
            ->withPivot('id_asigna_exp', 'fecha_deseada', 'cant_persona', 'nota')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_solicitud', 'id_solicitud');
    }

    public function ultimoPago(): HasOne
    {
        return $this->hasOne(Pago::class, 'id_solicitud', 'id_solicitud')
            ->latestOfMany('fecha');
    }

    /** total = Σ (precio de cada experiencia × personas asignadas a esa experiencia). */
    public function recalcularTotal(): void
    {
        $this->total = $this->experiencias()->get()->sum(
            fn ($experiencia) => (float) $experiencia->precio * (int) $experiencia->pivot->cant_persona
        );
        $this->save();
    }

    /** Folio único para siempre (también contra solicitudes eliminadas). */
    public static function generarFolio(): string
    {
        do {
            $folio = 'MC-'.now()->format('ymd').'-'.Str::upper(Str::random(4));
        } while (self::withTrashed()->where('folio', $folio)->exists());

        return $folio;
    }

    public function puedePagar(): bool
    {
        return in_array($this->estado, ['pendiente', 'confirmada'], true)
            && ! $this->pagos()
                ->whereIn('estado', ['pendiente', 'aprobado'])
                ->exists();
    }
}