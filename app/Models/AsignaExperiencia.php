<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    protected function casts(): array
    {
        return [
            'fecha_deseada' => 'date',
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
}
