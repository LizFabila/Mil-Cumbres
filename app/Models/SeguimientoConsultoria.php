<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguimientoConsultoria extends Model
{
    use SoftDeletes;

    protected $table = 'seguimiento_consultorias';
    protected $primaryKey = 'id_seguimiento_consultoria';

    protected $fillable = ['id_solicitud_consultoria', 'comentario', 'fecha'];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudConsultoria::class, 'id_solicitud_consultoria', 'id_solicitud_consultoria');
    }
}
