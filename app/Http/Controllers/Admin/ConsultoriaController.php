<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudConsultoria;
use App\Models\TipoOrganizacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConsultoriaController extends Controller
{
    /** A04 · Consultorías */
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'estado' => ['nullable', Rule::in(SolicitudConsultoria::ESTADOS)],
            'tipo' => ['nullable', 'integer'],
        ]);

        $consultorias = SolicitudConsultoria::with(['persona', 'organizacion.tipo'])
            ->withCount('seguimientos')
            ->when($filtros['estado'] ?? null, fn ($q, $v) => $q->where('estado', $v))
            ->when($filtros['tipo'] ?? null, fn ($q, $v) => $q->whereHas('organizacion', fn ($o) => $o->where('id_tipo_organizacion', $v)))
            ->latest('fecha_solicitud')
            ->paginate(15)
            ->withQueryString();

        return view('Dashboard.consultorias.index', [
            'consultorias' => $consultorias,
            'filtros' => $filtros,
            'tipos' => TipoOrganizacion::all(),
        ]);
    }

    public function show(SolicitudConsultoria $consultoria): View
    {
        $consultoria->load(['persona', 'organizacion.tipo', 'seguimientos']);

        return view('Dashboard.consultorias.show', compact('consultoria'));
    }

    public function estado(Request $request, SolicitudConsultoria $consultoria): RedirectResponse
    {
        $consultoria->update($request->validate([
            'estado' => ['required', Rule::in(SolicitudConsultoria::ESTADOS)],
        ]));

        return back()->with('ok', 'Estado actualizado.');
    }

    public function seguimiento(Request $request, SolicitudConsultoria $consultoria): RedirectResponse
    {
        $datos = $request->validate([
            'comentario' => ['required', 'string', 'max:2000'],
        ]);

        $consultoria->seguimientos()->create([
            'comentario' => $datos['comentario'],
            'fecha' => now(),
        ]);

        if ($consultoria->estado === 'recibida') {
            $consultoria->update(['estado' => 'en_proceso']);
        }

        return back()->with('ok', 'Seguimiento agregado.');
    }
}
