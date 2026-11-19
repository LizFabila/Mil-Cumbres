<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /** Experiencias que se realizan en esta zona (como principal o adicional). */
    public function experienciasAsignadas(): BelongsToMany
    {
        return $this->belongsToMany(Experiencia::class, 'asigna_zona', 'id_zona', 'id_experiencia')
            ->using(AsignaZona::class)
            ->wherePivotNull('deleted_at');
    }
}
