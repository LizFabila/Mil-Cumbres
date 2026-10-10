<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Empresa, Agrario. */
class TipoOrganizacion extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_organizaciones';
    protected $primaryKey = 'id_tipo_organizacion';

    protected $fillable = ['nombre'];

    public function organizaciones(): HasMany
    {
        return $this->hasMany(Organizacion::class, 'id_tipo_organizacion', 'id_tipo_organizacion');
    }
}
