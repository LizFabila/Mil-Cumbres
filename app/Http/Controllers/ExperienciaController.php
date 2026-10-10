<?php

namespace App\Http\Controllers;

use App\Models\CatalogoExperiencia;
use App\Models\Experiencia;
use App\Models\Zona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienciaController extends Controller
{
    /** C02 · Catálogo con filtros por tipo y zona */
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'tipo' => ['nullable', 'integer'],
            'zona' => ['nullable', 'integer'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $experiencias = Experiencia::with(['zona', 'catalogo'])
            ->when($filtros['tipo'] ?? null, fn ($q, $tipo) => $q->where('id_catalogo_exp', $tipo))
            ->when($filtros['zona'] ?? null, fn ($q, $zona) => $q->where('id_zona', $zona))
            ->when($filtros['q'] ?? null, fn ($q, $texto) => $q->where('nombre', 'like', "%{$texto}%"))
            ->orderBy('nombre')
            ->paginate(9)
            ->withQueryString();

        return view('Experiencias.index', [
            'experiencias' => $experiencias,
            'tipos' => CatalogoExperiencia::orderBy('tipo')->get(),
            'zonas' => Zona::orderBy('nombre')->get(),
            'filtros' => $filtros,
        ]);
    }

    /** C03 · Detalle de una experiencia */
    public function show(Experiencia $experiencia, ?string $slug = null): View|RedirectResponse
    {
        // URL canónica: /experiencias/{id}/{slug}
        if ($slug !== $experiencia->slug) {
            return redirect()->route('experiencias.show', [$experiencia, $experiencia->slug], 301);
        }

        $experiencia->load(['zona', 'catalogo', 'disponibilidades' => fn ($q) => $q
            ->orderByRaw("FIELD(dias_semana, 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo')")
            ->orderBy('hora_inicio')]);

        return view('Experiencias.show', [
            'experiencia' => $experiencia,
            'cupoMaximo' => (int) $experiencia->disponibilidades->max(fn ($d) => $d->pivot->cupo),
        ]);
    }
}
