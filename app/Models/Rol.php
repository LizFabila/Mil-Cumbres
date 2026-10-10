<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use SoftDeletes;

    public const ADMINISTRADOR = 'Administrador';
    public const CLIENTE = 'Cliente';

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';

    protected $fillable = ['nombre'];

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_rol', 'id_rol');
    }
}
