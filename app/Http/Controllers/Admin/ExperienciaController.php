<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsignaDisponibilidad;
use App\Models\Disponibilidad;
use App\Models\Experiencia;
use App\Models\Zona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExperienciaController extends Controller
{
    /** A06 · Experiencias */
    public function index(): View
    {
        $experiencias = Experiencia::with(['zona', 'zonas', 'catalogo'])
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
        $this->validarGaleria($request, null);

        DB::transaction(function () use ($request, $datos) {
            $experiencia = Experiencia::create($this->conImagen($request, $datos));
            $experiencia->sincronizarZonas($datos['zonas']);
            $this->guardarHorarios($experiencia, $datos['horarios'] ?? []);
            $experiencia->sincronizarGrupos($datos['grupos'] ?? []);
            $this->guardarGaleria($request, $experiencia);
        });

        return redirect()->route('admin.experiencias.index')->with('ok', 'Experiencia creada.');
    }

    /** A07 · Editor (edición) */
    public function edit(Experiencia $experiencia): View
    {
        $experiencia->load(['disponibilidades', 'zonas', 'imagenes', 'grupos']);

        return view('Dashboard.experiencias.form', $this->datosFormulario($experiencia));
    }

    public function update(Request $request, Experiencia $experiencia): RedirectResponse
    {
        $datos = $this->validar($request);
        $this->validarGaleria($request, $experiencia);

        DB::transaction(function () use ($request, $datos, $experiencia) {
            $experiencia->update($this->conImagen($request, $datos, $experiencia));
            $experiencia->sincronizarZonas($datos['zonas']);
            $this->guardarHorarios($experiencia, $datos['horarios'] ?? []);
            $experiencia->sincronizarGrupos($datos['grupos'] ?? []);
            $this->guardarGaleria($request, $experiencia);
        });

        return redirect()->route('admin.experiencias.index')->with('ok', 'Cambios guardados.');
    }

    /**
     * Galería: agrega las imágenes nuevas y quita las marcadas, sin tocar el resto de la experiencia.
     * Regresa a la misma página de edición (con el mismo límite y tamaño máximo que el formulario).
     */
    public function subirGaleria(Request $request, Experiencia $experiencia): RedirectResponse
    {
        $request->validate([
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'max:4096'],
            'imagenes_eliminar' => ['nullable', 'array'],
            'imagenes_eliminar.*' => ['integer'],
        ]);
        $this->validarGaleria($request, $experiencia);

        DB::transaction(fn () => $this->guardarGaleria($request, $experiencia));

        return redirect()->route('admin.experiencias.edit', $experiencia)->with('ok', 'Galería actualizada.');
    }

    /**
     * Portada: sube solo la foto de portada (ya recortada) o la URL pegada, y regresa a esta misma edición.
     * Mismas reglas que el formulario: imagen de hasta 4096 KB o URL válida.
     */
    public function subirPortada(Request $request, Experiencia $experiencia): RedirectResponse
    {
        $datos = $request->validate([
            'imagen_archivo' => ['nullable', 'image', 'max:4096'],
            'imagen_url' => ['nullable', 'url', 'max:255'],
        ]);

        if (! $request->hasFile('imagen_archivo') && empty($datos['imagen_url'])) {
            throw ValidationException::withMessages(['imagen_archivo' => 'Elige una foto o pega una URL.']);
        }

        $anterior = $experiencia->imagen;
        $nueva = $request->hasFile('imagen_archivo')
            ? $request->file('imagen_archivo')->store('experiencias', 'public')
            : $datos['imagen_url'];

        $experiencia->update(['imagen' => $nueva]);

        // Se borra la portada anterior solo si era un archivo local.
        if ($anterior && ! str_starts_with($anterior, 'http') && $anterior !== $nueva) {
            Storage::disk('public')->delete($anterior);
        }

        return redirect()->route('admin.experiencias.edit', $experiencia)->with('ok', 'Portada actualizada.');
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
            'dias' => Disponibilidad::DIAS,
            'horarios' => old('horarios', $experiencia->disponibilidades->map(fn ($d) => [
                'dia' => $d->dias_semana,
                'inicio' => substr($d->hora_inicio, 0, 5),
                'fin' => substr($d->hora_fin, 0, 5),
                'cupo' => $d->pivot->cupo,
            ])->all()),
            'grupos' => old('grupos', $experiencia->grupos->map(fn ($g) => [
                'personas' => $g->personas,
                'precio' => (float) $g->precio,
            ])->values()->all()),
        ];
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'zonas' => ['required', 'array', 'min:1'],
            'zonas.*' => ['integer', 'distinct', 'exists:zonas,id_zona,deleted_at,NULL'],
            'zona_principal' => ['nullable', 'integer'],
            'resumen' => ['nullable', 'string', 'max:2000'],
            'temporada' => ['nullable', 'string', 'max:50'],
            'duracion' => ['required', 'numeric', 'min:0.25', 'max:999.99'],
            'precio' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'tipo_precio' => ['required', Rule::in(array_keys(Experiencia::TIPOS_PRECIO))],
            'requisitos' => ['nullable', 'string', 'max:2000'],
            'imagen_url' => ['nullable', 'url', 'max:255'],
            'imagen_archivo' => ['nullable', 'image', 'max:4096'],
            'imagenes' => ['nullable', 'array'],
            'imagenes.*' => ['image', 'max:4096'],
            'imagenes_eliminar' => ['nullable', 'array'],
            'imagenes_eliminar.*' => ['integer'],
            'horarios' => ['nullable', 'array'],
            'horarios.*.dia' => ['required', Rule::in(Disponibilidad::DIAS)],
            'horarios.*.inicio' => ['required', 'date_format:H:i'],
            'horarios.*.fin' => ['required', 'date_format:H:i', 'after:horarios.*.inicio'],
            'horarios.*.cupo' => ['required', 'integer', 'min:1', 'max:65535'],
            'grupos' => ['nullable', 'array'],
            'grupos.*.personas' => ['required', 'integer', 'min:1', 'max:999', 'distinct'],
            'grupos.*.precio' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ], [
            'grupos.*.personas.required' => 'Indica el número de personas de cada grupo.',
            'grupos.*.personas.distinct' => 'Hay dos grupos con el mismo número de personas.',
            'grupos.*.precio.required' => 'Indica el precio total de cada grupo.',
            'horarios.*.fin.after' => 'La hora de fin debe ser mayor que la de inicio.',
            'zonas.required' => 'Elige al menos una zona.',
            'zonas.min' => 'Elige al menos una zona.',
        ]);

        return $this->normalizarPrecios($datos);
    }

    /**
     * Según el tipo de precio: 'persona' descarta los grupos; 'grupo' guarda el precio por persona en 0.
     * La experiencia nunca se queda sin costo: hace falta precio por persona o al menos un grupo con precio.
     */
    private function normalizarPrecios(array $datos): array
    {
        $tipo = $datos['tipo_precio'];
        $grupos = $tipo === 'persona' ? [] : ($datos['grupos'] ?? []);
        $precio = $tipo === 'grupo' ? 0.0 : (float) ($datos['precio'] ?? 0);
        $hayGrupoConPrecio = collect($grupos)->contains(fn ($g) => (float) ($g['precio'] ?? 0) > 0);

        if ($tipo === 'persona' && $precio <= 0) {
            throw ValidationException::withMessages(['precio' => 'Indica el precio por persona, o elige precio por grupo.']);
        }

        if ($tipo === 'grupo' && ! $hayGrupoConPrecio) {
            throw ValidationException::withMessages(['grupos' => 'Agrega al menos un grupo con precio.']);
        }

        if ($tipo === 'ambos' && $precio <= 0 && ! $hayGrupoConPrecio) {
            throw ValidationException::withMessages([
                'precio' => 'Pon precio por persona o al menos un grupo con precio.',
            ]);
        }

        $datos['precio'] = $precio;
        $datos['grupos'] = $grupos;

        return $datos;
    }

    /** Archivo subido > URL escrita > imagen que ya tenía. */
    private function conImagen(Request $request, array $datos, ?Experiencia $actual = null): array
    {
        $campos = collect($datos)->except(['imagen_url', 'imagen_archivo', 'horarios', 'grupos', 'zonas', 'zona_principal'])->all();

        // Zona principal: la marcada como principal si está entre las elegidas; si no, la primera.
        $zonas = array_map('intval', $datos['zonas']);
        $principal = (int) ($datos['zona_principal'] ?? 0);
        $campos['id_zona'] = in_array($principal, $zonas, true) ? $principal : $zonas[0];

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

    /** Galería: lo que queda (sin lo que se quita) más lo nuevo no puede pasar de Experiencia::MAX_IMAGENES. */
    private function validarGaleria(Request $request, ?Experiencia $actual): void
    {
        $quitar = array_map('intval', $request->input('imagenes_eliminar', []));
        $quedan = $actual
            ? $actual->imagenes()->whereNotIn('id_experiencia_imagen', $quitar)->count()
            : 0;
        $total = $quedan + count($request->file('imagenes', []));

        if ($total > Experiencia::MAX_IMAGENES) {
            throw ValidationException::withMessages([
                'imagenes' => 'Una experiencia puede tener máximo '.Experiencia::MAX_IMAGENES
                    .' imágenes en la galería. Con estos cambios tendría '.$total
                    .'; quita algunas o sube menos.',
            ]);
        }
    }

    /** Da de baja las imágenes marcadas y agrega las nuevas al final de la galería. */
    private function guardarGaleria(Request $request, Experiencia $experiencia): void
    {
        $quitar = array_map('intval', $request->input('imagenes_eliminar', []));

        if ($quitar) {
            $experiencia->imagenes()->whereIn('id_experiencia_imagen', $quitar)->get()->each->delete();
        }

        $orden = (int) $experiencia->imagenes()->max('orden');

        foreach ($request->file('imagenes', []) as $archivo) {
            $experiencia->imagenes()->create([
                'ruta' => $archivo->store('experiencias/galeria', 'public'),
                'orden' => ++$orden,
            ]);
        }
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
