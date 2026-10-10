<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Pivote experiencias <-> disponibilidad con el cupo por fecha. */
class AsignaDisponibilidad extends Pivot
{
    use SoftDeletes;

    protected $table = 'asigna_disponibilidad';
    protected $primaryKey = 'id_asigna_disponibilidad';
    public $incrementing = true;

    protected $fillable = ['id_experiencia', 'id_disponibilidad', 'cupo'];

    public function experiencia(): BelongsTo
    {
        return $this->belongsTo(Experiencia::class, 'id_experiencia', 'id_experiencia');
    }

    public function disponibilidad(): BelongsTo
    {
        return $this->belongsTo(Disponibilidad::class, 'id_disponibilidad', 'id_disponibilidad');
    }
}
