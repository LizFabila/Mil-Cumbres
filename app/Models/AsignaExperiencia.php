<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/** Pivote solicitudes <-> experiencias. */
class AsignaExperiencia extends Pivot
{
    use SoftDeletes;

    protected $table = 'asigna_experiencia';

    protected $primaryKey = 'id_asigna_exp';

    public $incrementing = true;

    protected $fillable = [
        'id_solicitud',
        'id_experiencia',
        'fecha_deseada',
        'cant_persona',
        'nota',
    ];

    protected function casts(): array
    {
        return [
            'fecha_deseada' => 'date',
            'cant_persona' => 'integer',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(
            Solicitud::class,
            'id_solicitud',
            'id_solicitud'
        );
    }

    public function experiencia(): BelongsTo
    {
        return $this->belongsTo(
            Experiencia::class,
            'id_experiencia',
            'id_experiencia'
        );
    }

    /** Calificación que el cliente dio a esta experiencia (una como máximo). */
    public function calificacion(): HasOne
    {
        return $this->hasOne(Calificacion::class, 'id_asigna_exp', 'id_asigna_exp');
    }

    /** Fecha en que se vive la experiencia: la propia o, si no tiene, la de la solicitud. */
    public function fechaExperiencia(): ?Carbon
    {
        $fecha = $this->fecha_deseada ?? $this->solicitud?->fecha_deseada;

        return $fecha ? Carbon::parse($fecha)->startOfDay() : null;
    }

    /**
     * Último día para calificar: N días después de la fecha de la experiencia
     * o de que la solicitud se marcó como completada (lo que ocurra después).
     */
    public function limiteParaCalificar(): ?Carbon
    {
        $fecha = $this->fechaExperiencia();
        if (! $fecha) {
            return null;
        }

        $completada = $this->solicitud?->updated_at?->copy()->startOfDay();
        $base = $completada && $completada->gt($fecha) ? $completada : $fecha;

        return $base->copy()->addDays((int) config('milcumbres.calificaciones.dias_para_calificar', 30))->endOfDay();
    }

    /**
     * Se puede calificar cuando la solicitud está completada, la fecha de la
     * experiencia ya llegó y no ha vencido el plazo.
     */
    public function puedeCalificarse(): bool
    {
        $fecha = $this->fechaExperiencia();

        return $this->solicitud?->estado === 'completada'
            && $fecha !== null
            && ! $fecha->isFuture()
            && $this->limiteParaCalificar()?->isFuture();
    }
}
