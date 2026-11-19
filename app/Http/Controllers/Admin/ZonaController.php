<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ZonaController extends Controller
{
    /** Zonas: catálogo que se usa al crear cada experiencia. */
    public function index(): View
    {
        $zonas = Zona::withCount('experienciasAsignadas as experiencias_count')->orderBy('nombre')->get();

        return view('Dashboard.zonas.index', compact('zonas'));
    }

    public function create(): View
    {
        return view('Dashboard.zonas.form', ['zona' => new Zona()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        Zona::create($datos);

        return redirect()->route('admin.zonas.index')->with('ok', 'Zona creada.');
    }

    public function edit(Zona $zona): View
    {
        return view('Dashboard.zonas.form', compact('zona'));
    }

    public function update(Request $request, Zona $zona): RedirectResponse
    {
        $zona->update($this->validar($request, $zona));

        return redirect()->route('admin.zonas.index')->with('ok', 'Cambios guardados.');
    }

    /** Borrado lógico, solo si ninguna experiencia activa usa la zona. */
    public function destroy(Zona $zona): RedirectResponse
    {
        $enUso = $zona->experienciasAsignadas()->count();

        if ($enUso > 0) {
            return back()->withErrors([
                'eliminar' => "No se puede quitar \"{$zona->nombre}\": {$enUso} experiencia(s) la usan. Cámbialas de zona primero.",
            ]);
        }

        $nombre = $zona->nombre;
        $zona->delete();

        return back()->with('ok', "La zona \"{$nombre}\" se quitó.");
    }

    private function validar(Request $request, ?Zona $zona = null): array
    {
        return $request->validate([
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('zonas', 'nombre')
                    ->ignore($zona?->id_zona, 'id_zona')
                    ->whereNull('deleted_at'),
            ],
        ], [
            'nombre.unique' => 'Ya existe una zona con ese nombre.',
        ]);
    }
}
