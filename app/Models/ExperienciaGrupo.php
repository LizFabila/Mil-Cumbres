<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Precio de un grupo de una experiencia. El precio es el TOTAL del grupo (MXN), no por persona. */
class ExperienciaGrupo extends Model
{
    use SoftDeletes;

    protected $table = 'experiencia_grupos';
    protected $primaryKey = 'id_experiencia_grupo';

    protected $fillable = ['id_experiencia', 'personas', 'precio'];

    protected function casts(): array
    {
        return [
            'personas' => 'integer',
            'precio' => 'decimal:2',
        ];
    }
}
