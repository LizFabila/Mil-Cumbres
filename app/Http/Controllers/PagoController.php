<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagoController extends Controller
{
    /** C09 · Registrar pago (solo cuando la solicitud ya fue confirmada) */
    public function create(Request $request, Solicitud $solicitud): View
    {
        $this->autorizar($request, $solicitud);
        $solicitud->load(['experiencias', 'pagos' => fn ($q) => $q->latest('fecha')]);

        return view('Pago.create', compact('solicitud'));
    }

    public function store(Request $request, Solicitud $solicitud): RedirectResponse
    {
        $this->autorizar($request, $solicitud);
        abort_unless($solicitud->puedePagar(), 422, 'Esta solicitud no admite un nuevo pago.');

        $datos = $request->validate([
            'referencia' => ['required', 'string', 'max:50', 'unique:pagos,referencia'],
            'comprobante' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'referencia.unique' => 'Esa referencia bancaria ya fue registrada.',
        ]);

        Pago::create([
            'id_solicitud' => $solicitud->id_solicitud,
            'referencia' => $datos['referencia'],
            'comprobante' => $request->file('comprobante')->store('comprobantes', 'public'),
            'estado' => 'pendiente',
            'fecha' => now(),
        ]);

        return redirect()->route('solicitudes.index')
            ->with('ok', "Recibimos tu comprobante del folio {$solicitud->folio}. Lo revisaremos en menos de 24 h.");
    }

    private function autorizar(Request $request, Solicitud $solicitud): void
    {
        abort_unless($solicitud->id_persona === $request->user()->id_persona, 404);
    }
}
