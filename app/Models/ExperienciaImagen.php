<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Imagen de la galería de una experiencia (máximo Experiencia::MAX_IMAGENES). */
class ExperienciaImagen extends Model
{
    use SoftDeletes;

    protected $table = 'experiencia_imagenes';
    protected $primaryKey = 'id_experiencia_imagen';

    protected $fillable = ['id_experiencia', 'ruta', 'orden'];

    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/'.$this->ruta));
    }
}
