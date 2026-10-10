<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarioController extends Controller
{
    /** A05 · Calendario mensual de solicitudes (pendientes y confirmadas) */
    public function index(Request $request): View
    {
        $mes = $request->filled('mes')
            ? Carbon::createFromFormat('Y-m', $request->query('mes'))->startOfMonth()
            : now()->startOfMonth();

        $solicitudes = Solicitud::with(['persona', 'experiencias'])
            ->whereBetween('fecha_deseada', [$mes->copy()->startOfMonth(), $mes->copy()->endOfMonth()])
            ->whereIn('estado', ['pendiente', 'confirmada', 'completada'])
            ->orderBy('fecha_deseada')
            ->get()
            ->groupBy(fn ($s) => $s->fecha_deseada->format('Y-m-d'));

        // Celdas del mes empezando en lunes.
        $inicio = $mes->copy()->startOfWeek(Carbon::MONDAY);
        $fin = $mes->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $dias = [];
        for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
            $dias[] = $d->copy();
        }

        return view('Dashboard.calendario', compact('mes', 'dias', 'solicitudes'));
    }
}
