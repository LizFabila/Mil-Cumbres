<?php

namespace App\Http\Controllers;

use App\Models\Experiencia;
use App\Models\Pago;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class SolicitudController extends Controller
{
    /** C06 · Solicitar experiencia (puede llegar con ?experiencia=&fecha=&personas= desde C03) */
    public function create(Request $request): View
    {
        $experiencias = Experiencia::with(['zona', 'catalogo', 'disponibilidades'])->orderBy('nombre')->get();

        return view('Solicitudes.create', [
            'experiencias' => $experiencias,
            'seleccionadas' => array_map('intval', (array) $request->query('experiencias', [])),
            'fechas' => (array) $request->query('fechas', []),
            'personas' => (array) $request->query('personas', []),
            'notas' => (array) $request->query('notas', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'experiencias' => ['required', 'array', 'min:1'],
            'experiencias.*' => [
                'integer',
                'distinct',
                'exists:experiencias,id_experiencia,deleted_at,NULL'
            ],
            'fechas' => ['required', 'array'],
            'fechas.*' => ['required', 'date', 'after:today'],
            'personas' => ['required', 'array'],
            'personas.*' => ['required', 'integer', 'min:1', 'max:500'],
            'notas' => ['nullable', 'array'],
            'notas.*' => ['nullable', 'string', 'max:1000'],
        ], [
            'experiencias.required' => 'Elige al menos una experiencia.',
            'fechas.required' => 'Selecciona una fecha para cada experiencia.',
            'fechas.*.required' => 'Debes elegir una fecha para cada experiencia.',
            'fechas.*.after' => 'Las fechas deben ser posteriores a hoy.',
            'personas.*.required' => 'Indica el número de personas de cada experiencia.',
            'personas.*.min' => 'Debe haber al menos una persona por experiencia.',
        ]);

        $experiencias = Experiencia::with('disponibilidades')
            ->whereIn('id_experiencia', $datos['experiencias'])
            ->get();

        $fechas = $datos['fechas'];
        $personas = $datos['personas'];
        $notas = $datos['notas'] ?? [];

        foreach ($experiencias as $exp) {
            $id = $exp->id_experiencia;
            $fecha = $fechas[$id] ?? null;
            $cantidad = (int) ($personas[$id] ?? 0);

            if (!$fecha) {
                throw ValidationException::withMessages([
                    "fechas.$id" => "Selecciona una fecha para {$exp->nombre}.",
                ]);
            }

            $cupo = $exp->cupoParaFecha($fecha);

            if ($cupo <= 0) {
                throw ValidationException::withMessages([
                    "fechas.$id" => "{$exp->nombre} no tiene horario disponible ese día de la semana.",
                ]);
            }

            $libres = $cupo - $exp->personasReservadas($fecha);

            if ($cantidad > $libres) {
                throw ValidationException::withMessages([
                    "personas.$id" => "{$exp->nombre} solo tiene {$libres} lugares disponibles para esa fecha.",
                ]);
            }
        }

        $primeraFecha = $fechas[$experiencias->first()->id_experiencia];

        // cant_persona de la solicitud queda como el grupo más grande (referencia para administración).
        $cantPersonas = max(array_map(
            fn ($id) => (int) $personas[$id],
            $datos['experiencias']
        ));

        $solicitud = DB::transaction(function () use (
            $request,
            $experiencias,
            $fechas,
            $personas,
            $notas,
            $primeraFecha,
            $cantPersonas
        ) {
            $solicitud = Solicitud::create([
                'fecha_deseada' => $primeraFecha,
                'id_persona' => $request->user()->id_persona,
                'cant_persona' => $cantPersonas,
                'folio' => Solicitud::generarFolio(),
                'estado' => 'pendiente',
            ]);

            $asignaciones = [];

            foreach ($experiencias as $exp) {
                $id = $exp->id_experiencia;

                $asignaciones[$id] = [
                    'fecha_deseada' => $fechas[$id],
                    'cant_persona' => (int) $personas[$id],
                    'nota' => $notas[$id] ?? null,
                ];
            }

            $solicitud->experiencias()->attach($asignaciones);
            $solicitud->recalcularTotal();

            return $solicitud;
        });

        // Después de enviar la solicitud sigue el pago; el folio se muestra al terminar.
        return redirect()->route('pagos.create', $solicitud);
    }

    /** C07 · Acuse */
    public function acuse(Request $request, Solicitud $solicitud): View
    {
        $this->autorizar($request, $solicitud);
        $solicitud->load('experiencias.zona');

        return view('Solicitudes.acuse', compact('solicitud'));
    }

    /** C08 · Mis solicitudes */
    
/** C08 · Mis solicitudes y consultorías */

/** C08 · Mis solicitudes y consultorías */
public function index(Request $request): View
{
    // 1. OBTENER RESERVACIONES DEL USUARIO

    $solicitudes = $request->user()->solicitudes()
        ->with(['experiencias', 'ultimoPago'])
        ->latest()
        ->paginate(10);


    // 2. OBTENER CONSULTORÍAS DEL USUARIO

    $consultorias = DB::table('solicitud_consultorias as sc')
        ->leftJoin(
            'organizaciones as o',
            'sc.id_organizacion',
            '=',
            'o.id_organizacion'
        )
        ->where(
            'sc.id_persona',
            $request->user()->id_persona
        )
        ->whereNull('sc.deleted_at')
        ->select(
            'sc.id_solicitud_consultoria',
            'sc.ubicacion',
            'sc.necesidad',
            'sc.fecha_solicitud',
            'sc.estado',
            'o.nombre as nombre_organizacion'
        )
        ->orderByDesc('sc.fecha_solicitud')
        ->get();


    // 2b. OBTENER PAGOS DE MIS RESERVACIONES

    $pagos = Pago::with('solicitud')
        ->whereHas('solicitud', function ($query) use ($request) {
            $query->where('id_persona', $request->user()->id_persona);
        })
        ->latest('fecha')
        ->get();


    // 3. OBTENER COMENTARIOS DE SEGUIMIENTO

    $seguimientos = DB::table('seguimiento_consultorias')
        ->whereIn(
            'id_solicitud_consultoria',
            $consultorias->pluck('id_solicitud_consultoria')
        )
        ->whereNull('deleted_at')
        ->select(
            'id_seguimiento_consultoria',
            'id_solicitud_consultoria',
            'comentario',
            'fecha'
        )
        ->orderByDesc('fecha')
        ->orderByDesc('id_seguimiento_consultoria')
        ->get()
        ->groupBy('id_solicitud_consultoria');


    // 4. ENVIAR DATOS A LA VISTA

    return view('Solicitudes.index', compact(
        'solicitudes',
        'consultorias',
        'seguimientos',
        'pagos'
    ));
}



    public function cancelar(Request $request, Solicitud $solicitud): RedirectResponse
    {
        $this->autorizar($request, $solicitud);
        abort_unless($solicitud->estado === 'pendiente', 422, 'Solo se pueden cancelar solicitudes pendientes.');

        $solicitud->update(['estado' => 'cancelada']);

        return back()->with('ok', "La solicitud {$solicitud->folio} fue cancelada.");
    }

    private function autorizar(Request $request, Solicitud $solicitud): void
    {
        abort_unless($solicitud->id_persona === $request->user()->id_persona, 404);
    }

    /** La fecha debe caer en un día con horario y no rebasar el cupo de ninguna experiencia. */
    private function validarCupo($experiencias, string $fecha, int $personas): void
    {
        foreach ($experiencias as $exp) {
            $cupo = $exp->cupoParaFecha($fecha);

            if ($cupo === 0) {
                throw ValidationException::withMessages([
                    'fecha_deseada' => "\"{$exp->nombre}\" no tiene horario ese día de la semana.",
                ]);
            }

            $libres = $cupo - $exp->personasReservadas($fecha);
            if ($personas > $libres) {
                throw ValidationException::withMessages([
                    'cant_persona' => "\"{$exp->nombre}\" solo tiene {$libres} lugares disponibles para esa fecha.",
                ]);
            }
        }
    }
}
