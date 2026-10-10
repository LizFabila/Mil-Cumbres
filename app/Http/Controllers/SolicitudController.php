<?php

namespace App\Http\Controllers;

use App\Models\Experiencia;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SolicitudController extends Controller
{
    /** C06 · Solicitar experiencia (puede llegar con ?experiencia=&fecha=&personas= desde C03) */
    public function create(Request $request): View
    {
        $experiencias = Experiencia::with(['zona', 'catalogo', 'disponibilidades'])->orderBy('nombre')->get();

        return view('Solicitudes.create', [
            'experiencias' => $experiencias,
            'seleccionadas' => array_map('intval', (array) $request->query('experiencia', [])),
            'fecha' => $request->query('fecha'),
            'personas' => $request->query('personas', 1),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'experiencias' => ['required', 'array', 'min:1'],
            'experiencias.*' => ['integer', 'exists:experiencias,id_experiencia,deleted_at,NULL'],
            'fecha_deseada' => ['required', 'date', 'after:today'],
            'cant_persona' => ['required', 'integer', 'min:1', 'max:500'],
            'nota' => ['nullable', 'string', 'max:1000'],
        ], [
            'experiencias.required' => 'Elige al menos una experiencia.',
            'fecha_deseada.after' => 'La fecha debe ser posterior a hoy.',
        ]);

        $experiencias = Experiencia::with('disponibilidades')->findMany($datos['experiencias']);
        $this->validarCupo($experiencias, $datos['fecha_deseada'], (int) $datos['cant_persona']);

        $solicitud = DB::transaction(function () use ($request, $datos, $experiencias) {
            $solicitud = Solicitud::create([
                'fecha_deseada' => $datos['fecha_deseada'],
                'id_persona' => $request->user()->id_persona,
                'cant_persona' => $datos['cant_persona'],
                'nota' => $datos['nota'] ?? null,
                'folio' => Solicitud::generarFolio(),
                'estado' => 'pendiente',
            ]);

            $solicitud->experiencias()->attach($experiencias->modelKeys());
            $solicitud->recalcularTotal();

            return $solicitud;
        });

        return redirect()->route('solicitudes.acuse', $solicitud);
    }

    /** C07 · Acuse */
    public function acuse(Request $request, Solicitud $solicitud): View
    {
        $this->autorizar($request, $solicitud);
        $solicitud->load('experiencias.zona');

        return view('Solicitudes.acuse', compact('solicitud'));
    }

    /** C08 · Mis solicitudes */
    public function index(Request $request): View
    {
        $solicitudes = $request->user()->solicitudes()
            ->with(['experiencias', 'ultimoPago'])
            ->latest()
            ->paginate(10);

        return view('Solicitudes.index', compact('solicitudes'));
    }

    public function cancelar(Request $request, Solicitud $solicitud): RedirectResponse
    {
        $this->autorizar($request, $solicitud);
        abort_unless($solicitud->estado === 'pendiente', 422, 'Solo se pueden cancelar solicitudes pendientes.');

        $solicitud->update(['estado' => 'cancelada']);

        return back()->with('ok', "La solicitud {$solicitud->folio} fue cancelada.");
    }

    private function autorizar(Request $request, Solicitud $solicitud): void
    {
        abort_unless($solicitud->id_persona === $request->user()->id_persona, 404);
    }

    /** La fecha debe caer en un día con horario y no rebasar el cupo de ninguna experiencia. */
    private function validarCupo($experiencias, string $fecha, int $personas): void
    {
        foreach ($experiencias as $exp) {
            $cupo = $exp->cupoParaFecha($fecha);

            if ($cupo === 0) {
                throw ValidationException::withMessages([
                    'fecha_deseada' => "\"{$exp->nombre}\" no tiene horario ese día de la semana.",
                ]);
            }

            $libres = $cupo - $exp->personasReservadas($fecha);
            if ($personas > $libres) {
                throw ValidationException::withMessages([
                    'cant_persona' => "\"{$exp->nombre}\" solo tiene {$libres} lugares disponibles para esa fecha.",
                ]);
            }
        }
    }
}
