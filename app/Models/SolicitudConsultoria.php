<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudConsultoria extends Model
{
    use SoftDeletes;

    public const ESTADOS = ['recibida', 'en_proceso', 'atendida', 'cerrada'];

    protected $table = 'solicitud_consultorias';
    protected $primaryKey = 'id_solicitud_consultoria';

    protected $fillable = ['id_persona', 'id_organizacion', 'ubicacion', 'necesidad', 'fecha_solicitud', 'estado'];

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'datetime',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'id_organizacion');
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(SeguimientoConsultoria::class, 'id_solicitud_consultoria', 'id_solicitud_consultoria')
            ->orderByDesc('fecha');
    }

    /** "en_proceso" -> "En proceso" */
    protected function estadoTexto(): Attribute
    {
        return Attribute::get(fn () => ucfirst(str_replace('_', ' ', $this->estado)));
    }
}
