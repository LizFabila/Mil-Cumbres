<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use SoftDeletes;

    public const ESTADOS = ['pendiente', 'aprobado', 'rechazado'];

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';

    protected $fillable = ['comprobante', 'id_solicitud', 'estado', 'fecha', 'referencia'];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id_solicitud');
    }

    protected function comprobanteUrl(): Attribute
    {
        return Attribute::get(fn () => $this->comprobante ? asset('storage/'.$this->comprobante) : null);
    }
}
