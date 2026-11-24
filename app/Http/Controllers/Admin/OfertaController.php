<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use App\Models\Oferta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/** Ofertas: descuentos por tiempo limitado que se muestran en el botón «Ofertas» del sitio. */
class OfertaController extends Controller
{
    public function index(): View
    {
        $orden = ['activa' => 0, 'programada' => 1, 'vencida' => 2];

        // Primero las activas, luego las programadas y al final las vencidas (las más recientes arriba).
        $ofertas = Oferta::with('experiencia')
            ->orderByDesc('termina')
            ->get()
            ->sortBy(fn ($o) => $orden[$o->estado])
            ->values();

        return view('Dashboard.ofertas.index', compact('ofertas'));
    }

    public function create(Request $request): View
    {
        $oferta = new Oferta([
            'modo' => 'porcentaje',
            'id_experiencia' => $request->integer('experiencia') ?: null,
            'inicia' => today(),
            'termina' => today()->addDays(7),
        ]);

        return view('Dashboard.ofertas.form', ['oferta' => $oferta, 'experiencias' => $this->experiencias()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Oferta::create($this->validar($request));

        return redirect()->route('admin.ofertas.index')->with('ok', 'Oferta guardada.');
    }

    public function edit(Oferta $oferta): View
    {
        return view('Dashboard.ofertas.form', ['oferta' => $oferta, 'experiencias' => $this->experiencias()]);
    }

    public function update(Request $request, Oferta $oferta): RedirectResponse
    {
        $oferta->update($this->validar($request, $oferta));

        return redirect()->route('admin.ofertas.index')->with('ok', 'Cambios guardados.');
    }

    /**
     * Terminar antes de tiempo. Una oferta activa se cierra con fecha de ayer (queda en el historial
     * como vencida); una programada que aún no empieza se quita (borrado lógico).
     * Las reservaciones que ya se hicieron conservan su precio de oferta.
     */
    public function destroy(Oferta $oferta): RedirectResponse
    {
        if ($oferta->estado === 'programada') {
            $oferta->delete();

            return back()->with('ok', 'La oferta programada se quitó.');
        }

        $ayer = today()->subDay();
        $oferta->update([
            'termina' => $ayer,
            'inicia' => $oferta->inicia->gt($ayer) ? $ayer : $oferta->inicia,
        ]);

        return back()->with('ok', 'La oferta se terminó y ya no aparece en el sitio.');
    }

    private function experiencias()
    {
        return Experiencia::with(['zona', 'zonas', 'catalogo'])->orderBy('nombre')->get();
    }

    private function validar(Request $request, ?Oferta $oferta = null): array
    {
        $datos = $request->validate([
            'id_experiencia' => [
                'required', 'integer',
                Rule::exists('experiencias', 'id_experiencia')->whereNull('deleted_at'),
            ],
            'aplica_a' => ['required', Rule::in(array_keys(Oferta::APLICA))],
            'grupo_personas' => ['nullable', 'integer', 'min:1', 'max:999'],
            'modo' => ['required', Rule::in(array_keys(Oferta::MODOS))],
            'valor' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'etiqueta' => ['nullable', 'string', 'max:40'],
            'inicia' => ['required', 'date'],
            'termina' => ['required', 'date', 'after_or_equal:inicia', 'after_or_equal:today'],
        ], [
            'id_experiencia.required' => 'Elige la experiencia.',
            'valor.required' => 'Indica el descuento.',
            'termina.after_or_equal' => 'La fecha de término no puede ser antes del inicio ni antes de hoy.',
        ]);

        $experiencia = Experiencia::with('grupos')->find($datos['id_experiencia']);
        $precio = (float) $experiencia->precio;

        if ($datos['aplica_a'] === 'grupo') {
            $grupo = $experiencia->grupos->firstWhere('personas', (int) ($datos['grupo_personas'] ?? 0));

            if (! $grupo) {
                throw ValidationException::withMessages([
                    'grupo_personas' => 'Elige un grupo de esta experiencia. Si no aparece, da de alta el grupo en la experiencia.',
                ]);
            }

            $base = (float) $grupo->precio;
            $datos['grupo_personas'] = (int) $grupo->personas;
        } else {
            $base = $precio;
            $datos['grupo_personas'] = null;
        }

        if (! in_array($datos['modo'], Oferta::MODOS_POR_APLICA[$datos['aplica_a']], true)) {
            throw ValidationException::withMessages([
                'modo' => 'Ese tipo de descuento no aplica a '.Oferta::APLICA[$datos['aplica_a']].'.',
            ]);
        }

        if ($datos['modo'] === 'porcentaje' && ($datos['valor'] < 1 || $datos['valor'] > 90)) {
            throw ValidationException::withMessages(['valor' => 'El descuento debe ser de 1 % a 90 %.']);
        }

        if ($datos['modo'] === 'precio' && $datos['valor'] >= $precio) {
            throw ValidationException::withMessages([
                'valor' => 'El precio de oferta debe ser menor al precio normal ($'.number_format($precio, 2).').',
            ]);
        }

        if ($datos['modo'] === 'cantidad' && $datos['valor'] >= $base) {
            throw ValidationException::withMessages([
                'valor' => 'El monto debe ser menor al total del grupo ($'.number_format($base, 2).').',
            ]);
        }

        // Una experiencia no puede tener dos ofertas que se crucen en fechas para el mismo destino
        // (persona individual, o el mismo grupo). Una oferta de grupo no choca con la de persona.
        $choca = Oferta::where('id_experiencia', $datos['id_experiencia'])
            ->where('aplica_a', $datos['aplica_a'])
            ->where(fn ($q) => $datos['grupo_personas'] === null
                ? $q->whereNull('grupo_personas')
                : $q->where('grupo_personas', $datos['grupo_personas']))
            ->when($oferta, fn ($q) => $q->whereKeyNot($oferta->getKey()))
            ->whereDate('inicia', '<=', $datos['termina'])
            ->whereDate('termina', '>=', $datos['inicia'])
            ->first();

        if ($choca) {
            throw ValidationException::withMessages([
                'inicia' => 'Esta experiencia ya tiene una oferta para ese mismo destino del '.$choca->inicia->translatedFormat('j M')
                    .' al '.$choca->termina->translatedFormat('j M Y').'. Cambia las fechas o termina esa oferta.',
            ]);
        }

        $datos['etiqueta'] = filled($datos['etiqueta'] ?? null) ? trim($datos['etiqueta']) : null;

        return $datos;
    }
}
