<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReservaController extends Controller
{
    /** Formulario para registrar una reservación a nombre de un cliente. */
    public function create(): View
    {
        return view('Dashboard.reservas.create', [
            'experiencias' => Experiencia::with(['zona', 'disponibilidades'])->orderBy('nombre')->get(),
            'clientes' => Persona::whereHas('rol', fn ($r) => $r->where('nombre', Rol::CLIENTE))
                ->orderBy('nombre')
                ->get(['id_persona', 'nombre', 'ap', 'email']),
        ]);
    }

    /**
     * Crea la solicitud igual que el sitio público: una fila por experiencia,
     * con su fecha, personas y nota, validando cupo y horario.
     * Si no eliges un cliente existente, se registra uno nuevo con correo verificado.
     * Ese cliente entra con «Olvidé mi contraseña».
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cliente_id' => ['nullable', Rule::exists('personas', 'id_persona')
                ->whereNull('deleted_at')
                ->where('id_rol', Rol::where('nombre', Rol::CLIENTE)->value('id_rol'))],
            'nuevo_nombre' => ['required_without:cliente_id', 'nullable', 'string', 'max:60'],
            'nuevo_ap' => ['required_without:cliente_id', 'nullable', 'string', 'max:60'],
            'nuevo_am' => ['nullable', 'string', 'max:60'],
            'nuevo_email' => ['required_without:cliente_id', 'nullable', 'email', 'max:120',
                Rule::unique('personas', 'email')->whereNull('deleted_at')],
            'nuevo_telefono' => ['nullable', 'digits:10'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id_experiencia' => ['required', 'integer', 'distinct',
                Rule::exists('experiencias', 'id_experiencia')->whereNull('deleted_at')],
            'items.*.fecha' => ['required', 'date', 'after:today'],
            'items.*.id_disponibilidad' => ['required', 'integer'],
            'items.*.personas' => ['required', 'integer', 'min:1', 'max:500'],
            'items.*.nota' => ['nullable', 'string', 'max:1000'],
        ], [
            'items.*.id_disponibilidad.required' => 'Elige un horario para cada experiencia.',
            'nuevo_nombre.required_without' => 'Indica el nombre del cliente o elige uno existente.',
            'nuevo_ap.required_without' => 'Indica el apellido del cliente o elige uno existente.',
            'nuevo_email.required_without' => 'Indica el correo del cliente o elige uno existente.',
            'items.*.id_experiencia.distinct' => 'Una experiencia solo puede aparecer una vez en la reservación.',
            'items.*.fecha.after' => 'La fecha debe ser posterior a hoy.',
        ]);

        // Revisa cupo y horario de cada experiencia antes de guardar.
        $filas = collect($datos['items'])->keyBy('id_experiencia');
        $experiencias = Experiencia::with('disponibilidades')
            ->whereIn('id_experiencia', $filas->keys())
            ->get();

        foreach ($experiencias as $exp) {
            $fila = $filas[$exp->id_experiencia];

            if ($error = $exp->errorDeReserva((int) $fila['id_disponibilidad'], $fila['fecha'], (int) $fila['personas'])) {
                throw ValidationException::withMessages(['items' => $error]);
            }
        }

        $solicitud = DB::transaction(function () use ($datos, $filas, $experiencias) {
            $idPersona = $datos['cliente_id'] ?? $this->crearCliente($datos)->id_persona;

            $solicitud = Solicitud::create([
                'fecha_deseada' => $filas->first()['fecha'],
                'id_persona' => $idPersona,
                'cant_persona' => (int) $filas->max('personas'),
                'folio' => Solicitud::generarFolio(),
                'estado' => 'pendiente',
            ]);

            $asignaciones = [];
            foreach ($experiencias as $exp) {
                $fila = $filas[$exp->id_experiencia];
                $asignaciones[$exp->id_experiencia] = [
                    'fecha_deseada' => $fila['fecha'],
                    'cant_persona' => (int) $fila['personas'],
                    'nota' => $fila['nota'] ?? null,
                    'id_disponibilidad' => (int) $fila['id_disponibilidad'],
                ];
            }

            $solicitud->experiencias()->attach($asignaciones);
            $solicitud->recalcularTotal();

            return $solicitud;
        });

        return redirect()
            ->route('admin.solicitudes.show', $solicitud)
            ->with('ok', "Reservación {$solicitud->folio} registrada. Falta el pago para confirmarla.");
    }

    /**
     * Cliente nuevo registrado desde el panel: correo verificado y contraseña aleatoria.
     * email_verified_at no está en $fillable, por eso se asigna con forceFill.
     */
    private function crearCliente(array $datos): Persona
    {
        $persona = new Persona([
            'nombre' => $datos['nuevo_nombre'],
            'ap' => $datos['nuevo_ap'],
            'am' => $datos['nuevo_am'] ?? null,
            'email' => $datos['nuevo_email'],
            'telefono' => $datos['nuevo_telefono'] ?? null,
            // Contraseña aleatoria: el cliente la define con «Olvidé mi contraseña».
            'contrasena' => Str::random(40),
            'id_rol' => Rol::where('nombre', Rol::CLIENTE)->value('id_rol'),
        ]);

        $persona->forceFill(['email_verified_at' => now()])->save();

        return $persona;
    }
}
