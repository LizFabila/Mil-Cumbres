<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    /** A09 · Usuarios: cartera de clientes (predeterminada) o lista de registrados. */
    public function index(Request $request): View
    {
        $q = $request->query('q');
        $vista = $request->query('vista') === 'registrados' ? 'registrados' : 'cartera';

        if ($vista === 'cartera') {
            return view('Dashboard.usuarios', [
                'vista' => $vista,
                'q' => $q,
                'cartera' => $this->cartera($q),
            ]);
        }

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
            'vista' => $vista,
            'personas' => $personas,
            'roles' => Rol::orderBy('nombre')->get(),
            'q' => $q,
        ]);
    }

    /**
     * Cartera de clientes: clasifica a cada cliente por tipo de servicio
     * (experiencias / consultorías) y por frecuencia (esporádico / frecuente).
     * Las solicitudes canceladas no cuentan para experiencias.
     */
    private function cartera(?string $q): array
    {
        $umbral = max(2, (int) config('milcumbres.cartera.umbral_frecuente', 3));

        $clientes = Persona::query()
            ->whereHas('rol', fn ($r) => $r->where('nombre', Rol::CLIENTE))
            ->withCount([
                'solicitudes as experiencias_count' => fn ($s) => $s->where('estado', '!=', 'cancelada'),
                'solicitudesConsultoria as consultorias_count',
            ])
            ->withMax(
                ['solicitudes as ultima_experiencia' => fn ($s) => $s->where('estado', '!=', 'cancelada')],
                'created_at'
            )
            ->withMax('solicitudesConsultoria as ultima_consultoria', 'fecha_solicitud')
            ->when($q, fn ($query) => $query->where(fn ($w) => $w
                ->where('nombre', 'like', "%{$q}%")
                ->orWhere('ap', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")))
            ->orderBy('nombre')
            ->get();

        return [
            'experiencias' => $this->segmentar(
                $clientes->filter(fn ($p) => $p->experiencias_count > 0),
                'experiencias_count',
                'ultima_experiencia',
                $umbral,
            ),
            'consultorias' => $this->segmentar(
                $clientes->filter(fn ($p) => $p->consultorias_count > 0),
                'consultorias_count',
                'ultima_consultoria',
                $umbral,
            ),
        ];
    }

    /** Separa un grupo de clientes en frecuentes (>= umbral) y esporádicos (< umbral). */
    private function segmentar(Collection $personas, string $conteo, string $ultima, int $umbral): array
    {
        $fila = fn ($p) => [
            'nombre' => $p->nombre_completo,
            'email' => $p->email,
            'telefono' => $p->telefono,
            'total' => (int) $p->{$conteo},
            'ultima' => $p->{$ultima} ? Carbon::parse($p->{$ultima}) : null,
        ];

        $ordenadas = $personas->sortByDesc($conteo)->values();

        return [
            'frecuentes' => $ordenadas->filter(fn ($p) => $p->{$conteo} >= $umbral)->map($fila)->values(),
            'esporadicos' => $ordenadas->filter(fn ($p) => $p->{$conteo} < $umbral)->map($fila)->values(),
        ];
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
