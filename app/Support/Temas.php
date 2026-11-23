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
            // Regla 60-30-10 con el azul del logotipo (#0090FC y marino #0B1A33):
            // 60 % azul: franja superior, títulos, secciones oscuras; barra del menú en azul casi blanco.
            // 30 % naranja: botones de acción, palabras resaltadas y detalles.
            // 10 % acento: blanco. Las fotos conservan su color natural.
            'azul' => [
                'nombre' => 'Azul y naranja',
                'descripcion' => 'Regla 60-30-10: 60 % azul del logotipo en títulos y secciones oscuras con barra en azul casi blanco, 30 % naranja en botones y resaltados, y 10 % blanco como acento.',
                'archivo' => 'azul',
                'colores' => self::coloresAzul('#FFFFFF', '#0B2545'),
            ],
            // Regla 60-30-10: 60 % fondos claros (blanco y verde niebla),
            // 30 % verde teal profundo (barras, títulos y secciones oscuras),
            // 10 % naranja (botones, enlaces destacados y acentos).
            // Sin «texto»: los textos conservan los colores originales del diseño verde.
            'teal' => [
                'nombre' => 'Bosque teal',
                'descripcion' => 'Regla 60-30-10: 60 % fondos claros en verde niebla, 30 % verde teal profundo en barras, títulos y secciones oscuras, y 10 % naranja en botones y acentos.',
                'colores' => [
                    'oscuro' => '#0F4038',
                    'principal' => '#1D6F5E',
                    'principal-claro' => '#237A67',
                    'secundario' => '#C2541F',
                    'acento' => '#F2894A',
                    'accion' => '#D9622B',
                    'accion-hover' => '#C2541F',
                    'sobre-accion' => '#FFFFFF',
                    'fondo' => '#FFFFFF',
                    'fondo-alt' => '#EAF2EE',
                    'lateral' => '#E6F0EC',
                    'lateral-2' => '#D2E5DD',
                    'lateral-texto' => '#0F4038',
                ],
            ],
        ];
    }

    /** Colores de las paletas Azul y naranja; solo cambia el acento (10 %) y el texto que va sobre él. */
    private static function coloresAzul(string $acento, string $sobreAcento): array
    {
        return [
            // 60 % azul. «Oscuro» también es el color de los títulos: marino para que se lea sobre blanco.
            'oscuro' => '#0B2545',
            'sobre-oscuro' => '#FFFFFF',
            'principal' => '#0B78D0',
            'principal-claro' => '#0A64AE',   // texto y enlaces sobre fondo blanco
            'sobre-principal' => '#FFFFFF',
            'activo' => '#0B78D0',
            'sobre-activo' => '#FFFFFF',
            // 30 % naranja
            'accion' => '#F2841F',
            'accion-hover' => '#E06F0C',
            'sobre-accion' => '#0B2545',
            'secundario' => '#C2560E',        // palabras resaltadas sobre fondo claro
            // 10 % acento
            'acento' => $acento,
            'sobre-acento' => $sobreAcento,
            // Fondos claros en azul niebla
            'fondo' => '#FFFFFF',
            'fondo-alt' => '#EEF5FC',
            'lateral' => '#EAF3FC',
            'lateral-2' => '#D3E6F8',
            'lateral-texto' => '#0B2545',
        ];
    }

    /** Claves guardadas que ya no existen y la paleta que las reemplaza. */
    private const REEMPLAZOS = ['naranja' => 'azul', 'azul-dorado' => 'azul'];

    /** Nombre de los archivos CSS de la paleta activa (varias paletas pueden compartirlos). */
    public static function archivo(): string
    {
        return self::catalogo()[self::clave()]['archivo'] ?? self::clave();
    }

    /** Hoja con las variables --tm-* de la paleta activa (temas/<archivo>.css). */
    public static function hojaTema(): string
    {
        return self::hoja('temas/'.self::archivo().'.css');
    }

    /** Hoja de ajustes propia de la paleta activa (temas/<archivo>-ajustes.css), si existe. */
    public static function hojaAjustes(): ?string
    {
        $ruta = 'temas/'.self::archivo().'-ajustes.css';

        return is_file(public_path('css/'.$ruta)) ? self::hoja($ruta) : null;
    }

    /** Clave del tema activo. Si la columna aún no existe o el valor no es válido, usa el actual. */
    public static function clave(): string
    {
        $clave = (string) ConfiguracionSitio::actual()->tema;
        $clave = self::REEMPLAZOS[$clave] ?? $clave;

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
