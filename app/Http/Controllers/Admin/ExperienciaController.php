<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsignaDisponibilidad;
use App\Models\CatalogoExperiencia;
use App\Models\Disponibilidad;
use App\Models\Experiencia;
use App\Models\Zona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExperienciaController extends Controller
{
    /** A06 · Experiencias */
    public function index(): View
    {
        $experiencias = Experiencia::with(['zona', 'catalogo'])
            ->withCount('disponibilidades')
            ->orderBy('nombre')
            ->paginate(15);

        return view('Dashboard.experiencias.index', compact('experiencias'));
    }

    /** A07 · Editor (alta) */
    public function create(): View
    {
        return view('Dashboard.experiencias.form', $this->datosFormulario(new Experiencia()));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        DB::transaction(function () use ($request, $datos) {
            $experiencia = Experiencia::create($this->conImagen($request, $datos));
            $this->guardarHorarios($experiencia, $datos['horarios'] ?? []);
        });

        return redirect()->route('admin.experiencias.index')->with('ok', 'Experiencia creada.');
    }

    /** A07 · Editor (edición) */
    public function edit(Experiencia $experiencia): View
    {
        $experiencia->load('disponibilidades');

        return view('Dashboard.experiencias.form', $this->datosFormulario($experiencia));
    }

    public function update(Request $request, Experiencia $experiencia): RedirectResponse
    {
        $datos = $this->validar($request);

        DB::transaction(function () use ($request, $datos, $experiencia) {
            $experiencia->update($this->conImagen($request, $datos, $experiencia));
            $this->guardarHorarios($experiencia, $datos['horarios'] ?? []);
        });

        return redirect()->route('admin.experiencias.index')->with('ok', 'Cambios guardados.');
    }

    /** Borrado lógico (deleted_at). */
    public function destroy(Experiencia $experiencia): RedirectResponse
    {
        $experiencia->delete();

        return back()->with('ok', "\"{$experiencia->nombre}\" se dio de baja.");
    }

    private function datosFormulario(Experiencia $experiencia): array
    {
        return [
            'experiencia' => $experiencia,
            'zonas' => Zona::orderBy('nombre')->get(),
            'tipos' => CatalogoExperiencia::orderBy('tipo')->get(),
            'dias' => Disponibilidad::DIAS,
            'horarios' => old('horarios', $experiencia->disponibilidades->map(fn ($d) => [
                'dia' => $d->dias_semana,
                'inicio' => substr($d->hora_inicio, 0, 5),
                'fin' => substr($d->hora_fin, 0, 5),
                'cupo' => $d->pivot->cupo,
            ])->all()),
        ];
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'id_zona' => ['required', 'exists:zonas,id_zona,deleted_at,NULL'],
            'id_catalogo_exp' => ['required', 'exists:catalogo_experiencias,id_catalogo_exp,deleted_at,NULL'],
            'resumen' => ['nullable', 'string', 'max:2000'],
            'temporada' => ['nullable', 'string', 'max:50'],
            'duracion' => ['required', 'integer', 'min:1', 'max:65535'],
            'precio' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'requisitos' => ['nullable', 'string', 'max:2000'],
            'imagen_url' => ['nullable', 'url', 'max:255'],
            'imagen_archivo' => ['nullable', 'image', 'max:4096'],
            'horarios' => ['nullable', 'array'],
            'horarios.*.dia' => ['required', Rule::in(Disponibilidad::DIAS)],
            'horarios.*.inicio' => ['required', 'date_format:H:i'],
            'horarios.*.fin' => ['required', 'date_format:H:i', 'after:horarios.*.inicio'],
            'horarios.*.cupo' => ['required', 'integer', 'min:1', 'max:65535'],
        ], [
            'horarios.*.fin.after' => 'La hora de fin debe ser mayor que la de inicio.',
        ]);
    }

    /** Archivo subido > URL escrita > imagen que ya tenía. */
    private function conImagen(Request $request, array $datos, ?Experiencia $actual = null): array
    {
        $campos = collect($datos)->except(['imagen_url', 'imagen_archivo', 'horarios'])->all();

        if ($request->hasFile('imagen_archivo')) {
            if ($actual?->imagen && ! str_starts_with($actual->imagen, 'http')) {
                Storage::disk('public')->delete($actual->imagen);
            }
            $campos['imagen'] = $request->file('imagen_archivo')->store('experiencias', 'public');
        } elseif (! empty($datos['imagen_url'])) {
            $campos['imagen'] = $datos['imagen_url'];
        }

        return $campos;
    }

    /**
     * Sincroniza asigna_disponibilidad respetando el borrado lógico:
     * reutiliza o crea el registro de disponibilidad (día + horario) y da de baja
     * (deleted_at) las asignaciones que ya no vienen en el formulario.
     */
    private function guardarHorarios(Experiencia $experiencia, array $horarios): void
    {
        $vigentes = [];

        foreach ($horarios as $h) {
            $disp = Disponibilidad::firstOrCreate([
                'dias_semana' => $h['dia'],
                'hora_inicio' => $h['inicio'].':00',
                'hora_fin' => $h['fin'].':00',
            ]);

            $asigna = AsignaDisponibilidad::updateOrCreate(
                ['id_experiencia' => $experiencia->id_experiencia, 'id_disponibilidad' => $disp->id_disponibilidad],
                ['cupo' => $h['cupo']],
            );
            $vigentes[] = $asigna->id_asigna_disponibilidad;
        }

        AsignaDisponibilidad::where('id_experiencia', $experiencia->id_experiencia)
            ->whereNotIn('id_asigna_disponibilidad', $vigentes)
            ->get()
            ->each->delete();
    }
}
