<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Pivote experiencias <-> zonas: zonas donde se realiza cada experiencia. */
class AsignaZona extends Pivot
{
    use SoftDeletes;

    protected $table = 'asigna_zona';
    protected $primaryKey = 'id_asigna_zona';
    public $incrementing = true;

    protected $fillable = ['id_experiencia', 'id_zona'];
}
