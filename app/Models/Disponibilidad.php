<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/** Un registro = un día de la semana con su horario. */
class Disponibilidad extends Model
{
    use SoftDeletes;

    /** Valores del ENUM dias_semana, en orden ISO (1 = lunes). */
    public const DIAS = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

    protected $table = 'disponibilidad';
    protected $primaryKey = 'id_disponibilidad';

    protected $fillable = ['dias_semana', 'hora_inicio', 'hora_fin'];

    public function experiencias(): BelongsToMany
    {
        return $this->belongsToMany(Experiencia::class, 'asigna_disponibilidad', 'id_disponibilidad', 'id_experiencia')
            ->using(AsignaDisponibilidad::class)
            ->withPivot('id_asigna_disponibilidad', 'cupo')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    public static function diaDeFecha(Carbon $fecha): string
    {
        return self::DIAS[$fecha->dayOfWeekIso - 1];
    }

    /** "Miercoles" -> "Miércoles", "Sabado" -> "Sábado" para mostrar. */
    protected function diaTexto(): Attribute
    {
        return Attribute::get(fn () => str_replace(['Miercoles', 'Sabado'], ['Miércoles', 'Sábado'], $this->dias_semana));
    }

    /** "07:00 – 10:00" */
    protected function horario(): Attribute
    {
        return Attribute::get(fn () => substr($this->hora_inicio, 0, 5).' – '.substr($this->hora_fin, 0, 5));
    }
}
