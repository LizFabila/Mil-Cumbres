<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zona extends Model
{
    use SoftDeletes;

    protected $table = 'zonas';
    protected $primaryKey = 'id_zona';

    protected $fillable = ['nombre'];

    public function experiencias(): HasMany
    {
        return $this->hasMany(Experiencia::class, 'id_zona', 'id_zona');
    }
}
