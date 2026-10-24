<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Catálogo de aspectos del servicio: guía, organización, seguridad, precio, entorno. */
class AspectoCalificacion extends Model
{
    use SoftDeletes;

    protected $table = 'aspectos_calificacion';
    protected $primaryKey = 'id_aspecto';

    protected $fillable = ['nombre', 'descripcion', 'orden'];

    public function puntuaciones(): HasMany
    {
        return $this->hasMany(CalificacionAspecto::class, 'id_aspecto', 'id_aspecto');
    }

    public function scopeOrdenados(Builder $query): Builder
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }
}
