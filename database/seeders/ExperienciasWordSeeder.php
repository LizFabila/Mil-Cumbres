<?php

namespace Database\Seeders;

use App\Models\Experiencia;
use App\Models\ExperienciaImagen;
use App\Models\Zona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Carga las 13 experiencias del documento "Experiencias Mil Cumbres 2026".
 * Los datos que aún no llegan son provisionales: precio por confirmar, duración de 180 min
 * y zona por confirmar. Las experiencias anteriores se dan de baja (borrado lógico, no se pierden).
 *
 *   php artisan db:seed --class=ExperienciasWordSeeder
 */
class ExperienciasWordSeeder extends Seeder
{
    private const CARPETA_FOTOS = 'img/hero-experiencias/word/';

    /** Fotos de galería: se toman de la carpeta de heroes (no son la portada del Word). */
    private const CARPETA_GALERIA = 'img/hero-experiencias/';

    private const OBS = 'Ropa y zapatos cómodos, protección para el sol, termo con agua';

    public function run(): void
    {
        $experiencias = $this->experiencias();

        // 1) Dar de baja las experiencias que no son del Word.
        Experiencia::whereNotIn('nombre', array_column($experiencias, 'nombre'))
            ->get()
            ->each->delete();

        // 2) Crear o reactivar las del Word.
        foreach ($experiencias as $e) {
            $zona = Zona::firstOrCreate(['nombre' => $e['zona']]);

            $exp = Experiencia::withTrashed()->updateOrCreate(
                ['nombre' => $e['nombre']],
                [
                    'id_zona' => $zona->id_zona,
                    'id_catalogo_exp' => null,
                    'resumen' => $e['resumen'],
                    'duracion' => 180,
                    'precio' => 0,
                    'requisitos' => $e['requisitos'],
                    'imagen' => $this->copiarFoto($e['foto']),
                    'deleted_at' => null,
                ],
            );

            $exp->sincronizarZonas([$zona->id_zona]);
            $this->cargarGaleria($exp, $e['nombre']);
        }
    }

    /**
     * Reemplaza la galería que cargó este seeder (rutas experiencias/galeria/word/).
     * No toca las imágenes que haya subido el administrador desde el panel.
     */
    private function cargarGaleria(Experiencia $exp, string $nombre): void
    {
        // Si la experiencia ya tiene galería activa, no se crea ni se reemplaza nada.
        if ($exp->imagenes()->exists()) {
            return;
        }

        ExperienciaImagen::withTrashed()
            ->where('id_experiencia', $exp->id_experiencia)
            ->where('ruta', 'like', 'experiencias/galeria/word/%')
            ->forceDelete();

        foreach (array_values($this->galeriaPorExperiencia()[$nombre] ?? []) as $i => $archivo) {
            $origen = public_path(self::CARPETA_GALERIA.$archivo);

            if (! File::exists($origen)) {
                continue;
            }

            $destino = 'experiencias/galeria/word/'.$exp->id_experiencia.'-'.($i + 1).'-'.$archivo;
            Storage::disk('public')->put($destino, File::get($origen));

            $exp->imagenes()->create(['ruta' => $destino, 'orden' => $i + 1]);
        }
    }

    /** Dos fotos de galería por experiencia (hay 15 fotos para 13 experiencias, así que algunas se repiten). */
    private function galeriaPorExperiencia(): array
    {
        return [
            'Chi Kung: Meditación en Movimiento' => ['sec-09.jpg', 'exp-01.jpg'],
            'Hiking para Equipos' => ['sec-10.jpg', 'exp-03.jpg'],
            'Shinrin-yoku: Baño de Bosque' => ['exp-01.jpg', 'sec-09.jpg'],
            'Terapia de Naturaleza' => ['sec-11.jpg', 'sec-13.jpg'],
            'Senderismo Bosques de Valle' => ['exp-01.jpg', 'exp-03.jpg'],
            'La Monarca de las Migraciones' => ['sec-08.jpg', 'exp-09.jpg'],
            'Recolección de Hongos' => ['exp-04.jpg', 'exp-05.jpg'],
            'Del Bosque a tu Mesa' => ['sec-12.jpg', 'exp-06.jpg'],
            'Agua Sagrada en el Mundo Mazahua' => ['exp-07.jpg', 'sec-13.jpg'],
            'Safari Fotográfico de Vida Silvestre' => ['exp-05.jpg', 'exp-09.jpg'],
            'Puebleando en Valle' => ['exp-06.jpg', 'exp-08.jpg'],
            'Guía Local Especializado' => ['exp-07.jpg', 'sec-13.jpg'],
            'El Cielo en la Tierra: Luciérnagas' => ['exp-02.jpg', 'exp-01.jpg'],
        ];
    }

    /** Copia la foto del Word al disco público y devuelve su ruta relativa. */
    private function copiarFoto(string $archivo): string
    {
        $origen = public_path(self::CARPETA_FOTOS.$archivo);
        $destino = 'experiencias/word/'.$archivo;

        if (File::exists($origen)) {
            Storage::disk('public')->put($destino, File::get($origen));
        }

        return $destino;
    }

    private function experiencias(): array
    {
        return [
            ['nombre' => 'Chi Kung: Meditación en Movimiento', 'zona' => 'Monte Alto', 'foto' => 'chi-kung.jpg',
                'resumen' => 'Respiración consciente y movimiento suave en el bosque para soltar tensiones y recuperar presencia.',
                'requisitos' => null],
            ['nombre' => 'Hiking para Equipos', 'zona' => 'Monte Alto', 'foto' => 'hiking-equipos.jpg',
                'resumen' => 'Recorrido de 2 a 3 km que fortalece la confianza, la colaboración y el sentido de equipo.',
                'requisitos' => null],
            ['nombre' => 'Shinrin-yoku: Baño de Bosque', 'zona' => 'Monte Alto', 'foto' => 'shinrin-yoku.jpg',
                'resumen' => 'Inmersión sensorial en el bosque de niebla para salir del modo automático y recuperar claridad.',
                'requisitos' => self::OBS],
            ['nombre' => 'Terapia de Naturaleza', 'zona' => 'Monte Alto', 'foto' => 'terapia-naturaleza.jpg',
                'resumen' => 'Espacio seguro para conectar con la naturaleza de forma consciente, a través de los sentidos.',
                'requisitos' => null],
            ['nombre' => 'Senderismo Bosques de Valle', 'zona' => 'Monte Alto', 'foto' => 'senderismo-bosques-valle.jpg',
                'resumen' => 'Recorrido guiado por senderos de bosque, con guías locales y paisajes de la región de la monarca.',
                'requisitos' => self::OBS],
            ['nombre' => 'La Monarca de las Migraciones', 'zona' => 'El Santuario', 'foto' => 'monarca-migraciones.jpg',
                'resumen' => 'Avistamiento y contemplación de mariposas monarca en el santuario, con la comunidad local.',
                'requisitos' => self::OBS],
            ['nombre' => 'Recolección de Hongos', 'zona' => 'Monte Alto', 'foto' => 'recoleccion-hongos.jpg',
                'resumen' => 'Recorrido guiado para identificar y recolectar hongos silvestres comestibles, con saberes tradicionales.',
                'requisitos' => self::OBS],
            ['nombre' => 'Del Bosque a tu Mesa', 'zona' => 'Monte Alto', 'foto' => 'del-bosque-a-tu-mesa.jpg',
                'resumen' => 'Servicio de alimentos con ingredientes locales y de temporada, servido en el bosque.',
                'requisitos' => null],
            ['nombre' => 'Agua Sagrada en el Mundo Mazahua', 'zona' => 'Monte Alto', 'foto' => 'agua-sagrada-mazahua.jpg',
                'resumen' => 'Saberes tradicionales de la comunidad mazahua, su relación con el bosque y la laguna.',
                'requisitos' => self::OBS],
            ['nombre' => 'Safari Fotográfico de Vida Silvestre', 'zona' => 'Monte Alto', 'foto' => 'safari-fotografico.jpg',
                'resumen' => 'Práctica guiada por un fotógrafo profesional para retratar flora, fauna y paisaje.',
                'requisitos' => self::OBS],
            ['nombre' => 'Puebleando en Valle', 'zona' => 'Centro Histórico', 'foto' => 'puebleando-valle.jpg',
                'resumen' => 'Paseo cultural por el centro de Valle de Bravo: mercados, iglesias y comida local.',
                'requisitos' => self::OBS],
            ['nombre' => 'Guía Local Especializado', 'zona' => 'Monte Alto', 'foto' => 'guia-local.jpg',
                'resumen' => 'Guías científicos locales con formación en turismo regenerativo e interpretación ambiental.',
                'requisitos' => self::OBS],
            ['nombre' => 'El Cielo en la Tierra: Luciérnagas', 'zona' => 'Monte Alto', 'foto' => 'luciernagas.jpg',
                'resumen' => 'Recorrido nocturno por el bosque de niebla para observar luciérnagas y su bioluminiscencia.',
                'requisitos' => 'Ropa y zapatos cómodos, prendas de frío, chamarra impermeable, lámpara'],
        ];
    }
}
