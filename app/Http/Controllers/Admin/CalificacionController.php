<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AspectoCalificacion;
use App\Models\Calificacion;
use App\Models\CalificacionAspecto;
use App\Models\Experiencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** A11 · Opiniones de clientes: métricas y moderación. */
class CalificacionController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'estado' => ['nullable', Rule::in([...Calificacion::ESTADOS, 'atencion'])],
            'experiencia' => ['nullable', 'integer'],
            'estrellas' => ['nullable', 'integer', 'between:1,5'],
        ]);

        // Métricas: todas las calificaciones no ocultas (filtradas por experiencia si aplica).
        $base = Calificacion::query()
            ->vigentes()
            ->when($filtros['experiencia'] ?? null, fn ($q, $v) => $q->where('id_experiencia', $v));

        $metricas = Calificacion::metricas($base);

        $aspectos = AspectoCalificacion::ordenados()->get()->map(fn ($a) => [
            'nombre' => $a->nombre,
            'promedio' => CalificacionAspecto::where('id_aspecto', $a->id_aspecto)
                ->whereIn('id_calificacion', (clone $base)->select('id_calificacion'))
                ->avg('puntuacion'),
        ]);

        $porAtender = Calificacion::requierenAtencion()->count();

        $calificaciones = Calificacion::with(['persona', 'experiencia', 'asignacion.solicitud', 'aspectos.aspecto'])
            ->when($filtros['experiencia'] ?? null, fn ($q, $v) => $q->where('id_experiencia', $v))
            ->when($filtros['estrellas'] ?? null, fn ($q, $v) => $q->where('estrellas', $v))
            ->when($filtros['estado'] ?? null, fn ($q, $v) => $v === 'atencion'
                ? $q->requierenAtencion()
                : $q->where('estado', $v))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $conteos = Calificacion::selectRaw('estado, COUNT(*) as total')->groupBy('estado')->pluck('total', 'estado');

        return view('Dashboard.calificaciones.index', [
            'calificaciones' => $calificaciones,
            'metricas' => $metricas,
            'aspectos' => $aspectos,
            'porAtender' => $porAtender,
            'conteos' => $conteos,
            'filtros' => $filtros,
            'experiencias' => Experiencia::whereIn('id_experiencia', Calificacion::select('id_experiencia'))->orderBy('nombre')->get(['id_experiencia', 'nombre']),
        ]);
    }

    /** Publicar, ocultar o regresar a pendiente. */
    public function estado(Request $request, Calificacion $calificacion): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(Calificacion::ESTADOS)],
        ]);

        if ($datos['estado'] === 'publicada' && ! $calificacion->permite_publicar) {
            return back()->withErrors(['estado' => 'El cliente no dio permiso para publicar esta opinión; solo cuenta para las métricas.']);
        }

        $calificacion->update($datos);

        $textos = ['publicada' => 'publicada en el sitio', 'oculta' => 'ocultada', 'pendiente' => 'regresada a revisión'];

        return back()->with('ok', 'Opinión '.$textos[$datos['estado']].'.');
    }

    /** Respuesta pública de Mil Cumbres (vacía = se elimina). */
    public function respuesta(Request $request, Calificacion $calificacion): RedirectResponse
    {
        $datos = $request->validate([
            'respuesta' => ['nullable', 'string', 'max:1000'],
        ]);

        $texto = filled($datos['respuesta'] ?? null) ? trim($datos['respuesta']) : null;

        $calificacion->update([
            'respuesta' => $texto,
            'fecha_respuesta' => $texto ? now() : null,
        ]);

        return back()->with('ok', $texto ? 'Respuesta guardada.' : 'Respuesta eliminada.');
    }
}
