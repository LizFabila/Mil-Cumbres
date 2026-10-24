<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Puntuación (1 a 5) de un aspecto dentro de una calificación. */
class CalificacionAspecto extends Model
{
    use SoftDeletes;

    protected $table = 'calificacion_aspectos';
    protected $primaryKey = 'id_calificacion_aspecto';

    protected $fillable = ['id_calificacion', 'id_aspecto', 'puntuacion'];

    protected function casts(): array
    {
        return ['puntuacion' => 'integer'];
    }

    public function calificacion(): BelongsTo
    {
        return $this->belongsTo(Calificacion::class, 'id_calificacion', 'id_calificacion');
    }

    public function aspecto(): BelongsTo
    {
        return $this->belongsTo(AspectoCalificacion::class, 'id_aspecto', 'id_aspecto');
    }
}
