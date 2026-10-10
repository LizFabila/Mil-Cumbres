<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use App\Models\Pago;
use App\Models\Solicitud;
use App\Models\SolicitudConsultoria;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResumenController extends Controller
{
    /** A08 · Resumen */
    public function index(): View
    {
        $inicioMes = now()->startOfMonth();

        $kpis = [
            'pendientes' => Solicitud::where('estado', 'pendiente')->count(),
            'confirmadas_mes' => Solicitud::where('estado', 'confirmada')->where('updated_at', '>=', $inicioMes)->count(),
            'pagos_por_revisar' => Pago::where('estado', 'pendiente')->count(),
            'ingresos_mes' => (float) Solicitud::whereHas('pagos', fn ($q) => $q->where('estado', 'aprobado')->where('fecha', '>=', $inicioMes))->sum('total'),
            'consultorias_abiertas' => SolicitudConsultoria::whereIn('estado', ['recibida', 'en_proceso'])->count(),
            'personas_proximas' => (int) Solicitud::where('estado', 'confirmada')
                ->whereBetween('fecha_deseada', [today(), today()->addDays(30)])->sum('cant_persona'),
        ];

        // Experiencias más solicitadas (solicitudes no canceladas).
        $top = Experiencia::query()
            ->select('experiencias.id_experiencia', 'experiencias.nombre')
            ->selectRaw('COUNT(s.id_solicitud) as solicitudes, COALESCE(SUM(s.cant_persona),0) as personas')
            ->join('asigna_experiencia as ae', fn ($j) => $j->on('ae.id_experiencia', '=', 'experiencias.id_experiencia')->whereNull('ae.deleted_at'))
            ->join('solicitudes as s', fn ($j) => $j->on('s.id_solicitud', '=', 'ae.id_solicitud')->whereNull('s.deleted_at')->where('s.estado', '!=', 'cancelada'))
            ->groupBy('experiencias.id_experiencia', 'experiencias.nombre')
            ->orderByDesc('solicitudes')
            ->take(5)
            ->get();

        $proximas = Solicitud::with(['persona', 'experiencias'])
            ->where('estado', 'confirmada')
            ->whereDate('fecha_deseada', '>=', today())
            ->orderBy('fecha_deseada')
            ->take(6)
            ->get();

        $porEstado = Solicitud::select('estado', DB::raw('COUNT(*) as total'))->groupBy('estado')->pluck('total', 'estado');

        return view('Dashboard.resumen', compact('kpis', 'top', 'proximas', 'porEstado'));
    }
}
