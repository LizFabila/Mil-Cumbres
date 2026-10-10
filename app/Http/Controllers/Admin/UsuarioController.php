<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    /** A09 · Usuarios */
    public function index(Request $request): View
    {
        $q = $request->query('q');

        $personas = Persona::with('rol')
            ->withCount('solicitudes')
            ->when($q, fn ($query) => $query->where(fn ($w) => $w
                ->where('nombre', 'like', "%{$q}%")
                ->orWhere('ap', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")))
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('Dashboard.usuarios', [
            'personas' => $personas,
            'roles' => Rol::orderBy('nombre')->get(),
            'q' => $q,
        ]);
    }

    /** Cambiar rol. */
    public function update(Request $request, Persona $persona): RedirectResponse
    {
        $datos = $request->validate([
            'id_rol' => ['required', 'exists:roles,id_rol,deleted_at,NULL'],
        ]);

        abort_if($persona->is($request->user()), 422, 'No puedes cambiar tu propio rol.');

        $persona->update($datos);

        return back()->with('ok', "Rol de {$persona->nombre_completo} actualizado.");
    }

    /** Baja lógica. */
    public function destroy(Request $request, Persona $persona): RedirectResponse
    {
        abort_if($persona->is($request->user()), 422, 'No puedes darte de baja a ti mismo.');

        $persona->delete();

        return back()->with('ok', "{$persona->nombre_completo} fue dado de baja.");
    }
}
