<?php

namespace Database\Seeders;

use App\Models\AsignaDisponibilidad;
use App\Models\CatalogoExperiencia;
use App\Models\Disponibilidad;
use App\Models\Experiencia;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\TipoOrganizacion;
use App\Models\Zona;
use Illuminate\Database\Seeder;

/**
 * Datos de ejemplo para ver las vistas con contenido.
 * Se puede correr varias veces: usa firstOrCreate / updateOrCreate.
 *
 *   php artisan db:seed
 *
 * Usuario administrador de prueba:
 *   correo:     admin@milcumbres.test
 *   contraseña: la de ADMIN_PASSWORD en .env, o "MilCumbres2026!" si no existe.
 *
 * Usuario cliente de prueba:
 *   correo:     cliente@milcumbres.test
 *   contraseña: la de CLIENTE_PASSWORD en .env, o "Cliente2026!" si no existe.
 */
class MilCumbresSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin = Rol::firstOrCreate(['nombre' => Rol::ADMINISTRADOR]);
        $cliente = Rol::firstOrCreate(['nombre' => Rol::CLIENTE]);

        // Catálogos (el .sql ya los inserta; esto solo asegura que existan)
        foreach (['Bienestar', 'Natural', 'Cultural'] as $tipo) {
            CatalogoExperiencia::firstOrCreate(['tipo' => $tipo]);
        }
        foreach (['Empresa', 'Agrario'] as $tipo) {
            TipoOrganizacion::firstOrCreate(['nombre' => $tipo]);
        }

        Persona::firstOrCreate(
            ['email' => 'admin@milcumbres.test'],
            [
                'nombre' => 'Administración',
                'ap' => 'Mil Cumbres',
                'contrasena' => env('ADMIN_PASSWORD', 'MilCumbres2026!'),
                'id_rol' => $admin->id_rol,
            ],
        );

        Persona::firstOrCreate(
            ['email' => 'cliente@milcumbres.test'],
            [
                'nombre' => 'Cliente',
                'ap' => 'De Prueba',
                'telefono' => '7220000000',
                'contrasena' => env('CLIENTE_PASSWORD', 'Cliente2026!'),
                'id_rol' => $cliente->id_rol,
            ],
        );

        $zonas = collect(['Monte Alto', 'Avándaro', 'Presa Valle de Bravo', 'Centro Histórico', 'El Santuario'])
            ->mapWithKeys(fn ($n) => [$n => Zona::firstOrCreate(['nombre' => $n])->id_zona]);

        $tipos = CatalogoExperiencia::pluck('id_catalogo_exp', 'tipo');

        $img = fn (string $id) => "https://images.unsplash.com/photo-{$id}?w=1600&q=80&auto=format&fit=crop";

        $experiencias = [
            [
                'nombre' => 'Aviturismo guiado',
                'tipo' => 'Natural', 'zona' => 'Monte Alto',
                'resumen' => 'Una experiencia para observar aves y reconectar con el entorno natural.',
                'temporada' => 'Octubre a marzo', 'duracion' => 180, 'precio' => 380,
                'requisitos' => 'Ropa en capas, calzado cerrado y binoculares (prestamos dos pares por grupo). Edad mínima 8 años.',
                'imagen' => $img('1444464666168-49d633b86797'),
                'horarios' => [['Sabado', '07:00', '10:00', 12], ['Domingo', '07:00', '10:00', 12]],
            ],
            [
                'nombre' => 'Sendero del bosque de niebla',
                'tipo' => 'Natural', 'zona' => 'Avándaro',
                'resumen' => 'Caminata interpretativa entre pinos y encinos hasta el puente colgante.',
                'temporada' => 'Todo el año', 'duracion' => 240, 'precio' => 450,
                'requisitos' => 'Condición física moderada, agua (1 L) e impermeable en temporada de lluvias.',
                'imagen' => $img('1447752875215-b2761acb3c5d'),
                'horarios' => [['Viernes', '08:00', '12:00', 15], ['Sabado', '08:00', '12:00', 15]],
            ],
            [
                'nombre' => 'Kayak al amanecer en la presa',
                'tipo' => 'Natural', 'zona' => 'Presa Valle de Bravo',
                'resumen' => 'Remada tranquila con guía mientras sale el sol sobre el lago.',
                'temporada' => 'Marzo a noviembre', 'duracion' => 120, 'precio' => 520,
                'requisitos' => 'Saber nadar. Incluye chaleco salvavidas y equipo.',
                'imagen' => $img('1501785888041-af3ef285b470'),
                'horarios' => [['Sabado', '06:30', '08:30', 8], ['Domingo', '06:30', '08:30', 8]],
            ],
            [
                'nombre' => 'Yoga frente al valle',
                'tipo' => 'Bienestar', 'zona' => 'El Santuario',
                'resumen' => 'Sesión de yoga y respiración al aire libre para todos los niveles.',
                'temporada' => 'Todo el año', 'duracion' => 90, 'precio' => 280,
                'requisitos' => 'Ropa cómoda. Prestamos tapete.',
                'imagen' => $img('1545205597-3d9d02c29597'),
                'horarios' => [['Miercoles', '08:00', '09:30', 14], ['Sabado', '09:00', '10:30', 14]],
            ],
            [
                'nombre' => 'Temazcal tradicional',
                'tipo' => 'Bienestar', 'zona' => 'El Santuario',
                'resumen' => 'Baño de vapor ceremonial guiado por una temazcalera de la comunidad.',
                'temporada' => 'Todo el año', 'duracion' => 150, 'precio' => 650,
                'requisitos' => 'No recomendado para embarazadas ni personas con hipertensión. Traer traje de baño y toalla.',
                'imagen' => $img('1600334089648-b0d9d3028eb2'),
                'horarios' => [['Viernes', '17:00', '19:30', 10], ['Domingo', '16:00', '18:30', 10]],
            ],
            [
                'nombre' => 'Recorrido por el pueblo mágico',
                'tipo' => 'Cultural', 'zona' => 'Centro Histórico',
                'resumen' => 'Historias, arquitectura y oficios del centro de Valle de Bravo.',
                'temporada' => 'Todo el año', 'duracion' => 120, 'precio' => 250,
                'requisitos' => 'Calzado cómodo.',
                'imagen' => $img('1518105779142-d975f22f1b0a'),
                'horarios' => [['Jueves', '11:00', '13:00', 20], ['Sabado', '11:00', '13:00', 20]],
            ],
            [
                'nombre' => 'Taller de papel picado',
                'tipo' => 'Cultural', 'zona' => 'Centro Histórico',
                'resumen' => 'Aprende la técnica tradicional con artesanas locales y llévate tu pieza.',
                'temporada' => 'Septiembre a noviembre', 'duracion' => 150, 'precio' => 320,
                'requisitos' => 'Ninguno. Material incluido.',
                'imagen' => $img('1512813195386-6cf811ad3542'),
                'horarios' => [['Sabado', '16:00', '18:30', 12]],
            ],
            [
                'nombre' => 'Cocina de temporada',
                'tipo' => 'Cultural', 'zona' => 'Avándaro',
                'resumen' => 'Cocinamos con productos de milpa y huerto, y comemos juntos.',
                'temporada' => 'Todo el año', 'duracion' => 180, 'precio' => 690,
                'requisitos' => 'Avísanos de alergias en la nota de la solicitud.',
                'imagen' => $img('1414235077428-338989a2e8c0'),
                'horarios' => [['Domingo', '13:00', '16:00', 16]],
            ],
        ];

        foreach ($experiencias as $e) {
            $exp = Experiencia::updateOrCreate(
                ['nombre' => $e['nombre']],
                [
                    'id_zona' => $zonas[$e['zona']],
                    'id_catalogo_exp' => $tipos[$e['tipo']],
                    'resumen' => $e['resumen'],
                    'temporada' => $e['temporada'],
                    'duracion' => $e['duracion'],
                    'precio' => $e['precio'],
                    'requisitos' => $e['requisitos'],
                    'imagen' => $e['imagen'],
                ],
            );

            foreach ($e['horarios'] as [$dia, $inicio, $fin, $cupo]) {
                $disp = Disponibilidad::firstOrCreate([
                    'dias_semana' => $dia,
                    'hora_inicio' => "{$inicio}:00",
                    'hora_fin' => "{$fin}:00",
                ]);

                AsignaDisponibilidad::updateOrCreate(
                    ['id_experiencia' => $exp->id_experiencia, 'id_disponibilidad' => $disp->id_disponibilidad],
                    ['cupo' => $cupo],
                );
            }
        }
    }
}
