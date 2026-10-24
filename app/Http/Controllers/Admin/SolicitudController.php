<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Notifications\InvitacionCalificar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class SolicitudController extends Controller
{
    /** A01 · Solicitudes */
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'estado' => ['nullable', Rule::in(Solicitud::ESTADOS)],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date'],
            'q' => ['nullable', 'string', 'max:60'],
        ]);

        $solicitudes = Solicitud::with(['persona', 'experiencias'])
            ->when($filtros['estado'] ?? null, fn ($q, $v) => $q->where('estado', $v))
            ->when($filtros['desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_deseada', '>=', $v))
            ->when($filtros['hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_deseada', '<=', $v))
            ->when($filtros['q'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('folio', 'like', "%{$v}%")
                ->orWhereHas('persona', fn ($p) => $p
                    ->where('nombre', 'like', "%{$v}%")
                    ->orWhere('email', 'like', "%{$v}%"))))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $conteos = Solicitud::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return view('Dashboard.solicitudes.index', compact('solicitudes', 'filtros', 'conteos'));
    }

    /** A02 · Detalle de solicitud */
    public function show(Solicitud $solicitud): View
    {
        $solicitud->load([
            'persona',
            'experiencias.zona',
            'experiencias.disponibilidades',
            'pagos' => fn ($q) => $q->latest('fecha'),
        ]);

        // Cupo restante por experiencia en la fecha solicitada.
        $cupos = $solicitud->experiencias->mapWithKeys(fn ($exp) => [
            $exp->id_experiencia => [
                'cupo' => $exp->cupoParaFecha($solicitud->fecha_deseada),
                'ocupado' => $exp->personasReservadas(
                    $solicitud->fecha_deseada,
                    $solicitud->id_solicitud
                ),
            ],
        ]);

        return view('Dashboard.solicitudes.show', compact('solicitud', 'cupos'));
    }

    /** Cambiar el estado de una solicitud */
    public function estado(Request $request, Solicitud $solicitud): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(Solicitud::ESTADOS)],
        ]);

        $seCompleta = $datos['estado'] === 'completada' && $solicitud->estado !== 'completada';

        $solicitud->update($datos);

        $mensaje = "Solicitud {$solicitud->folio} marcada como {$datos['estado']}.";

        // Al completarse, se invita al cliente a calificar las experiencias que ya vivió.
        if ($seCompleta && $solicitud->persona) {
            $solicitud->load('experiencias');

            if (InvitacionCalificar::experienciasVividas($solicitud)->isEmpty()) {
                $mensaje .= ' No se envió la invitación para calificar porque la fecha de la experiencia aún no llega;'
                    .' el cliente verá el aviso en «Mis solicitudes» a partir de esa fecha.';
            } else {
                try {
                    $solicitud->persona->notify(new InvitacionCalificar($solicitud));
                    $mensaje .= ' Se envió al cliente la invitación para calificar.';
                } catch (Throwable $e) {
                    report($e);
                    $mensaje .= ' No se pudo enviar el correo de invitación; el cliente verá el aviso en «Mis solicitudes».';
                }
            }
        }

        return back()->with('ok', $mensaje);
    }

    /** Retirar una solicitud cancelada del panel administrativo */
    public function destroy(Solicitud $solicitud): RedirectResponse
    {
        // Solo se permite retirar solicitudes canceladas.
        if ($solicitud->estado !== 'cancelada') {
            return back()->withErrors([
                'eliminar' => 'Solo se pueden eliminar solicitudes canceladas.',
            ]);
        }

        $folio = $solicitud->folio;

        // Eliminación lógica: conserva el registro en la base de datos.
        $solicitud->delete();

        return redirect()
            ->route('admin.solicitudes.index')
            ->with('ok', "La solicitud {$folio} fue retirada correctamente.");
    }
}
