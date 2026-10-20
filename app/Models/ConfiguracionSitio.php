<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

/** Identidad del sitio: nombre, lema y logotipo (una sola fila). */
class ConfiguracionSitio extends Model
{
    protected $table = 'configuracion_sitio';
    protected $primaryKey = 'id_configuracion';

    protected $fillable = ['nombre', 'lema', 'logo'];

    private static ?self $actual = null;

    /**
     * Devuelve la configuración guardada. Si la tabla todavía no existe
     * (alguien no ha corrido la migración), usa los valores de siempre.
     */
    public static function actual(): self
    {
        if (static::$actual) {
            return static::$actual;
        }

        try {
            $config = static::query()->first();
        } catch (QueryException) {
            $config = null;
        }

        return static::$actual = $config ?? new static([
            'nombre' => 'Mil Cumbres',
            'lema' => 'Turismo Regenerativo',
        ]);
    }

    /** Logotipo subido, o el PNG original si no se ha cambiado. */
    protected function logoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->logo
            ? Storage::disk('public')->url($this->logo)
            : asset('img/logo-mil-cumbres.png'));
    }
}
