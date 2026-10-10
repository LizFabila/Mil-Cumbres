<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PagoController extends Controller
{
    /** A03 · Pagos por revisar */
    public function index(Request $request): View
    {
        $estado = $request->validate([
            'estado' => ['nullable', Rule::in(Pago::ESTADOS)],
        ])['estado'] ?? 'pendiente';

        $pagos = Pago::with('solicitud.persona')
            ->where('estado', $estado)
            ->latest('fecha')
            ->paginate(15)
            ->withQueryString();

        $conteos = Pago::selectRaw('estado, COUNT(*) as total')->groupBy('estado')->pluck('total', 'estado');

        return view('Dashboard.pagos.index', compact('pagos', 'estado', 'conteos'));
    }

    /** Aprobar o rechazar un comprobante. */
    public function update(Request $request, Pago $pago): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(['aprobado', 'rechazado'])],
        ]);

        $pago->update($datos);

        return back()->with('ok', "Pago {$pago->referencia} {$datos['estado']}.");
    }
}
