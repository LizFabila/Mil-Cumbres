<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\SolicitudConsultoria;
use App\Models\TipoOrganizacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConsultoriaController extends Controller
{
    /** C10 · Consultoría (información) */
    public function index(): View
    {
        return view('Consultorias.index', [
            'tipos' => TipoOrganizacion::orderBy('id_tipo_organizacion')->get(),
        ]);
    }

    /** C11 · Solicitar consultoría */
    public function create(Request $request): View
    {
        return view('Consultorias.create', [
            'tipos' => TipoOrganizacion::orderBy('id_tipo_organizacion')->get(),
            'tipoElegido' => $request->integer('tipo') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'id_tipo_organizacion' => ['required', 'exists:tipo_organizaciones,id_tipo_organizacion,deleted_at,NULL'],
            'organizacion' => ['required', 'string', 'max:120'],
            'ubicacion' => ['required', 'string', 'max:200'],
            'necesidad' => ['required', 'string', 'min:20', 'max:3000'],
        ], [
            'necesidad.min' => 'Cuéntanos un poco más (mínimo 20 caracteres).',
        ]);

        $consultoria = DB::transaction(function () use ($request, $datos) {
            // Reutiliza la organización si ya existe con el mismo nombre y tipo.
            $organizacion = Organizacion::firstOrCreate([
                'nombre' => $datos['organizacion'],
                'id_tipo_organizacion' => $datos['id_tipo_organizacion'],
            ]);

            return SolicitudConsultoria::create([
                'id_persona' => $request->user()->id_persona,
                'id_organizacion' => $organizacion->id_organizacion,
                'ubicacion' => $datos['ubicacion'],
                'necesidad' => $datos['necesidad'],
                'fecha_solicitud' => now(),
                'estado' => 'recibida',
            ]);
        });

        return redirect()->route('consultoria.acuse', $consultoria);
    }

    /** C12 · Acuse de consultoría */
    public function acuse(Request $request, SolicitudConsultoria $consultoria): View
    {
        abort_unless($consultoria->id_persona === $request->user()->id_persona, 404);
        $consultoria->load('organizacion.tipo');

        return view('Consultorias.acuse', compact('consultoria'));
    }
}
