<?php

namespace App\Support;

use App\Models\ConfiguracionSitio;

/**
 * Paletas de colores del sitio. El administrador elige una en "Apariencia" y la
 * elección se guarda en configuracion_sitio.tema. Cada paleta se publica como
 * variables CSS (--tema-*) que usan el panel, la vista del cliente y la página pública.
 */
final class Temas
{
    public const PREDETERMINADO = 'actual';

    /** Acento de las paletas A, B y C: naranja de la escala #FFA726 a #FFF3E0. */
    private const NARANJA_SUAVE = '#FFCC80';

    public static function catalogo(): array
    {
        return [
            'actual' => [
                'nombre' => 'Actual',
                'descripcion' => 'Verde bosque, azul acero y crema. Es el tema con el que se ha visto el sitio hasta ahora.',
                'colores' => [
                    'oscuro' => '#143D36',
                    'principal' => '#176954',
                    'principal-claro' => '#1B6B57',
                    'secundario' => '#2F6690',
                    'acento' => '#00DD00',
                    'fondo' => '#FFFFFF',
                    'fondo-alt' => '#F3F7F5',
                    'lateral' => '#E7F0E9',
                    'lateral-2' => '#D8E8DD',
                    'lateral-texto' => '#244D40',
                ],
            ],
            'paleta-a' => [
                'nombre' => 'Paleta A',
                'descripcion' => 'Fondo blanco, azul brillante y verde. Contraste alto y aspecto más digital.',
                'colores' => [
                    'oscuro' => '#000E26',
                    'principal' => '#0068C9',
                    'principal-claro' => '#267FD1',
                    'secundario' => '#2E7D5B',
                    'acento' => self::NARANJA_SUAVE,
                    'fondo' => '#FFFFFF',
                    'fondo-alt' => '#F2F6FA',
                    'lateral' => '#E6F0FA',
                    'lateral-2' => '#CCE1F4',
                    'lateral-texto' => '#000E26',
                    'accion' => '#FFA726',
                    'accion-hover' => '#FFB74D',
                    'sobre-accion' => '#000E26',
                ],
            ],
            'paleta-b' => [
                'nombre' => 'Paleta B',
                'descripcion' => 'Fondo crema, verde y azul sobrio. Es la más cálida y la que mejor combina con la naturaleza del sitio.',
                'recomendada' => true,
                'colores' => [
                    'oscuro' => '#17231E',
                    'principal' => '#3A7556',
                    'principal-claro' => '#588A6F',
                    'secundario' => '#1F5F8B',
                    'acento' => self::NARANJA_SUAVE,
                    'fondo' => '#F6F4EE',
                    'fondo-alt' => '#EEEBE3',
                    'lateral' => '#EBF1EE',
                    'lateral-2' => '#D8E3DD',
                    'lateral-texto' => '#17231E',
                    'accion' => '#FFA726',
                    'accion-hover' => '#FFB74D',
                    'sobre-accion' => '#17231E',
                ],
            ],
            'paleta-c' => [
                'nombre' => 'Paleta C',
                'descripcion' => 'Fondo crema con verde profundo y azul acero. Más serena y formal.',
                'colores' => [
                    'oscuro' => '#14302A',
                    'principal' => '#1B4D40',
                    'principal-claro' => '#3D685D',
                    'secundario' => '#2F6E9E',
                    'acento' => self::NARANJA_SUAVE,
                    'fondo' => '#F5F2EA',
                    'fondo-alt' => '#EDE9DF',
                    'lateral' => '#E8EDEC',
                    'lateral-2' => '#D1DBD9',
                    'lateral-texto' => '#14302A',
                    'accion' => '#FFA726',
                    'accion-hover' => '#FFB74D',
                    'sobre-accion' => '#14302A',
                ],
            ],
            'naranja' => [
                'nombre' => 'Naranja',
                'descripcion' => 'Barra superior y fondos oscuros en marrón ámbar, menú en naranja tierra, fondos blancos, tarjetas crema y botones en naranja miel.',
                'colores' => [
                    // «Oscuro» también es el color de títulos y de las secciones oscuras del sitio:
                    // debe ser oscuro de verdad para que se lean sobre fondos blancos y crema.
                    'oscuro' => '#542E06',
                    'sobre-oscuro' => '#FFFFFF',
                    'principal' => '#D9730D',
                    // Se usa como color de texto en muchas vistas: naranja profundo para que se lea sobre blanco.
                    'principal-claro' => '#B35F0F',
                    'sobre-principal' => '#FFFFFF',
                    'activo' => '#D9730D',
                    'sobre-activo' => '#FFFFFF',
                    'accion' => '#F28A22',
                    'accion-hover' => '#E07B00',
                    'sobre-accion' => '#1F1F1F',
                    'secundario' => '#A34A06',
                    'acento' => '#FFCC80',
                    'fondo' => '#FFFFFF',
                    'fondo-alt' => '#FDF3EA',
                    'lateral' => '#FDF3EA',
                    'lateral-2' => '#FDE3D3',
                    'lateral-texto' => '#1F1F1F',
                    'texto' => '#1F1F1F',
                ],
            ],
        ];
    }

    /** Clave del tema activo. Si la columna aún no existe o el valor no es válido, usa el actual. */
    public static function clave(): string
    {
        $clave = (string) ConfiguracionSitio::actual()->tema;

        return array_key_exists($clave, self::catalogo()) ? $clave : self::PREDETERMINADO;
    }

    /** Bloque <style> con las variables --tema-* del tema activo. */
    public static function etiqueta(): string
    {
        $colores = self::catalogo()[self::clave()]['colores'];

        $declaraciones = '';
        foreach ($colores as $nombre => $valor) {
            $declaraciones .= "--tema-{$nombre}: {$valor};";
        }

        return '<style id="mc-tema">:root{'.$declaraciones.'}</style>';
    }

    /** URL de una hoja de public/css con versión (fecha de modificación) para no mostrar una copia vieja. */
    public static function hoja(string $ruta): string
    {
        $archivo = public_path('css/'.$ruta);
        $version = is_file($archivo) ? filemtime($archivo) : time();

        return asset('css/'.$ruta).'?v='.$version;
    }
}
