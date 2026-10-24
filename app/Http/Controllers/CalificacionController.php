<?php

namespace App\Http\Controllers;

use App\Models\AsignaExperiencia;
use App\Models\AspectoCalificacion;
use App\Models\Calificacion;
use App\Models\CalificacionAspecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/** C16 · Calificar una experiencia vivida. */
class CalificacionController extends Controller
{
    /** Formulario (alta o edición dentro del plazo). */
    public function create(Request $request, AsignaExperiencia $asignacion): View|RedirectResponse
    {
        $this->autorizar($request, $asignacion);

        $calificacion = $asignacion->calificacion()->with('aspectos')->first();

        if ($redireccion = $this->validarDisponibilidad($asignacion, $calificacion)) {
            return $redireccion;
        }

        // Desde el correo se puede llegar con ?estrellas=N para dejarla preseleccionada.
        $preseleccion = (int) $request->query('estrellas');

        return view('Calificaciones.create', [
            'asignacion' => $asignacion,
            'experiencia' => $asignacion->experiencia,
            'solicitud' => $asignacion->solicitud,
            'calificacion' => $calificacion,
            'aspectos' => AspectoCalificacion::ordenados()->get(),
            'puntuaciones' => $calificacion?->aspectos->pluck('puntuacion', 'id_aspecto')->all() ?? [],
            'estrellasIniciales' => $calificacion?->estrellas ?? ($preseleccion >= 1 && $preseleccion <= 5 ? $preseleccion : null),
        ]);
    }

    /**
     * Vista previa del formulario (solo en entorno local), sin reglas de
     * elegibilidad y sin guardar. Usa la experiencia más reciente del usuario.
     */
    public function vistaPrevia(Request $request): View
    {
        abort_unless(app()->environment('local'), 404);

        $asignacion = AsignaExperiencia::whereHas('solicitud', fn ($q) => $q->where('id_persona', $request->user()->id_persona))
            ->latest('id_asigna_exp')
            ->first()
            ?? AsignaExperiencia::latest('id_asigna_exp')->firstOrFail();

        $asignacion->loadMissing(['solicitud', 'experiencia']);

        return view('Calificaciones.create', [
            'asignacion' => $asignacion,
            'experiencia' => $asignacion->experiencia,
            'solicitud' => $asignacion->solicitud,
            'calificacion' => null,
            'aspectos' => AspectoCalificacion::ordenados()->get(),
            'puntuaciones' => [],
            'estrellasIniciales' => null,
            'vistaPrevia' => true,
        ]);
    }

    /** Guarda la calificación nueva o los cambios. */
    public function store(Request $request, AsignaExperiencia $asignacion): RedirectResponse
    {
        $this->autorizar($request, $asignacion);

        $calificacion = $asignacion->calificacion()->first();

        if ($redireccion = $this->validarDisponibilidad($asignacion, $calificacion)) {
            return $redireccion;
        }

        $idsAspectos = AspectoCalificacion::pluck('id_aspecto')->all();

        $datos = $request->validate([
            'estrellas' => ['required', 'integer', 'between:1,5'],
            'recomendacion' => ['nullable', 'integer', 'between:0,10'],
            'aspectos' => ['nullable', 'array'],
            'aspectos.*' => ['nullable', 'integer', 'between:1,5'],
            'comentario' => ['nullable', 'string', 'max:1500'],
            'permite_publicar' => ['nullable', 'boolean'],
        ], [
            'estrellas.required' => 'Elige de 1 a 5 estrellas para calificar la experiencia.',
            'comentario.max' => 'El comentario puede tener hasta 1,500 caracteres.',
        ]);

        DB::transaction(function () use ($request, $asignacion, $calificacion, $datos, $idsAspectos) {
            $valores = [
                'estrellas' => $datos['estrellas'],
                'recomendacion' => $datos['recomendacion'] ?? null,
                'comentario' => filled($datos['comentario'] ?? null) ? trim($datos['comentario']) : null,
                'permite_publicar' => $request->boolean('permite_publicar'),
                // Una edición vuelve a revisión para que el admin vea el texto nuevo.
                'estado' => 'pendiente',
            ];

            if ($calificacion) {
                $calificacion->update($valores);
            } else {
                $calificacion = Calificacion::create($valores + [
                    'id_asigna_exp' => $asignacion->id_asigna_exp,
                    'id_persona' => $request->user()->id_persona,
                    'id_experiencia' => $asignacion->id_experiencia,
                ]);
            }

            // Aspectos: se guardan los calificados y se dan de baja los que se dejaron vacíos.
            foreach ($idsAspectos as $idAspecto) {
                $puntuacion = $datos['aspectos'][$idAspecto] ?? null;
                $actual = CalificacionAspecto::where('id_calificacion', $calificacion->id_calificacion)
                    ->where('id_aspecto', $idAspecto)
                    ->first();

                if ($puntuacion) {
                    $actual
                        ? $actual->update(['puntuacion' => $puntuacion])
                        : CalificacionAspecto::create([
                            'id_calificacion' => $calificacion->id_calificacion,
                            'id_aspecto' => $idAspecto,
                            'puntuacion' => $puntuacion,
                        ]);
                } elseif ($actual) {
                    $actual->delete();
                }
            }
        });

        $nombre = $asignacion->experiencia?->nombre ?? 'tu experiencia';

        return redirect()
            ->route('solicitudes.index')
            ->with('ok', "¡Gracias! Guardamos tu calificación de {$nombre}.");
    }

    /** Solo el dueño de la solicitud puede calificar sus experiencias. */
    private function autorizar(Request $request, AsignaExperiencia $asignacion): void
    {
        $asignacion->loadMissing(['solicitud', 'experiencia']);

        abort_unless(
            $asignacion->solicitud && $asignacion->solicitud->id_persona === $request->user()->id_persona,
            404
        );
    }

    /** Devuelve una redirección con el motivo si hoy no se puede calificar o editar. */
    private function validarDisponibilidad(AsignaExperiencia $asignacion, ?Calificacion $calificacion): ?RedirectResponse
    {
        $volver = fn (string $motivo) => redirect()->route('solicitudes.index')->withErrors(['calificacion' => $motivo]);

        if ($calificacion) {
            return $calificacion->esEditable()
                ? null
                : $volver('Ya calificaste esta experiencia y el plazo para editarla terminó. ¡Gracias por tu opinión!');
        }

        if ($asignacion->solicitud?->estado !== 'completada') {
            return $volver('Podrás calificar esta experiencia cuando la reservación esté completada.');
        }

        if (! $asignacion->puedeCalificarse()) {
            $fecha = $asignacion->fechaExperiencia();

            return $volver($fecha && $fecha->isFuture()
                ? 'Podrás calificar esta experiencia después de vivirla.'
                : 'El plazo para calificar esta experiencia terminó.');
        }

        return null;
    }
}
