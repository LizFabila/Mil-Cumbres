<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizacion extends Model
{
    use SoftDeletes;

    protected $table = 'organizaciones';
    protected $primaryKey = 'id_organizacion';

    protected $fillable = ['id_tipo_organizacion', 'nombre'];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoOrganizacion::class, 'id_tipo_organizacion', 'id_tipo_organizacion');
    }

    public function solicitudesConsultoria(): HasMany
    {
        return $this->hasMany(SolicitudConsultoria::class, 'id_organizacion', 'id_organizacion');
    }
}
