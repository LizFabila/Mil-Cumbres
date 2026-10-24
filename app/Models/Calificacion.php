<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Calificación de una experiencia vivida (una por fila de asigna_experiencia).
 *  - estrellas:      satisfacción general (CSAT) de 1 a 5. Obligatoria.
 *  - recomendacion:  probabilidad de recomendar (NPS) de 0 a 10. Opcional.
 *  - estado:         moderación del admin (pendiente / publicada / oculta).
 */
class Calificacion extends Model
{
    use SoftDeletes;

    public const ESTADOS = ['pendiente', 'publicada', 'oculta'];

    public const ETIQUETAS_ESTRELLAS = [
        1 => 'Muy insatisfecho',
        2 => 'Insatisfecho',
        3 => 'Regular',
        4 => 'Satisfecho',
        5 => 'Muy satisfecho',
    ];

    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';

    protected $fillable = [
        'id_asigna_exp', 'id_persona', 'id_experiencia',
        'estrellas', 'recomendacion', 'comentario', 'permite_publicar',
        'estado', 'respuesta', 'fecha_respuesta',
    ];

    protected function casts(): array
    {
        return [
            'estrellas' => 'integer',
            'recomendacion' => 'integer',
            'permite_publicar' => 'boolean',
            'fecha_respuesta' => 'datetime',
        ];
    }

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(AsignaExperiencia::class, 'id_asigna_exp', 'id_asigna_exp');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function experiencia(): BelongsTo
    {
        return $this->belongsTo(Experiencia::class, 'id_experiencia', 'id_experiencia');
    }

    public function aspectos(): HasMany
    {
        return $this->hasMany(CalificacionAspecto::class, 'id_calificacion', 'id_calificacion');
    }

    /** Opiniones visibles en el sitio: aprobadas por el admin y con permiso del cliente. */
    public function scopePublicadas(Builder $query): Builder
    {
        return $query->where('estado', 'publicada')->where('permite_publicar', true);
    }

    /** Cuentan para promedios y métricas: todas menos las ocultas por moderación. */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where('estado', '!=', 'oculta');
    }

    /** Calificaciones bajas (2 estrellas o menos) que aún no tienen respuesta. */
    public function scopeRequierenAtencion(Builder $query): Builder
    {
        return $query->where('estrellas', '<=', 2)
            ->whereNull('respuesta')
            ->where('estado', '!=', 'oculta');
    }

    /** El cliente puede editarla durante unos días después de enviarla. */
    public function esEditable(): bool
    {
        return $this->created_at
            && $this->created_at->copy()->addDays((int) config('milcumbres.calificaciones.dias_para_editar', 7))->isFuture();
    }

    /** "Kevin M." — nombre y la inicial del apellido para mostrar en público. */
    protected function nombrePublico(): Attribute
    {
        return Attribute::get(function () {
            $persona = $this->persona;
            if (! $persona) {
                return 'Visitante';
            }
            $inicial = mb_substr((string) $persona->ap, 0, 1);

            return trim(explode(' ', trim($persona->nombre))[0].' '.($inicial ? mb_strtoupper($inicial).'.' : ''));
        });
    }

    /**
     * Métricas sobre un conjunto de calificaciones.
     *  - promedio: media de estrellas.
     *  - csat:     % de calificaciones con 4 o 5 estrellas.
     *  - nps:      % promotores (9–10) − % detractores (0–6), de −100 a +100.
     */
    public static function metricas(Builder $query): array
    {
        $filas = (clone $query)->get(['estrellas', 'recomendacion']);
        $total = $filas->count();

        $conNps = $filas->whereNotNull('recomendacion');
        $totalNps = $conNps->count();

        return [
            'total' => $total,
            'promedio' => $total ? round($filas->avg('estrellas'), 1) : null,
            'csat' => $total ? (int) round($filas->where('estrellas', '>=', 4)->count() / $total * 100) : null,
            'nps' => $totalNps
                ? (int) round(($conNps->where('recomendacion', '>=', 9)->count() - $conNps->where('recomendacion', '<=', 6)->count()) / $totalNps * 100)
                : null,
            'promotores' => $totalNps ? (int) round($conNps->where('recomendacion', '>=', 9)->count() / $totalNps * 100) : null,
            'detractores' => $totalNps ? (int) round($conNps->where('recomendacion', '<=', 6)->count() / $totalNps * 100) : null,
            'distribucion' => collect([5, 4, 3, 2, 1])->mapWithKeys(fn ($n) => [$n => $filas->where('estrellas', $n)->count()])->all(),
        ];
    }
}
