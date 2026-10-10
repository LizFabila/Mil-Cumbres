<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Tipo de experiencia: Bienestar, Natural, Cultural. */
class CatalogoExperiencia extends Model
{
    use SoftDeletes;

    protected $table = 'catalogo_experiencias';
    protected $primaryKey = 'id_catalogo_exp';

    protected $fillable = ['tipo', 'descripcion'];

    public function experiencias(): HasMany
    {
        return $this->hasMany(Experiencia::class, 'id_catalogo_exp', 'id_catalogo_exp');
    }
}
