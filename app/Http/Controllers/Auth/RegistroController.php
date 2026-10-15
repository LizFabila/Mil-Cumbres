<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use App\Support\ReglasContrasena;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class RegistroController extends Controller
{
    /** C05 · Registro */
    public function create(): View
    {
        return view('Register.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:60'],
            'ap' => ['required', 'string', 'max:60'],
            'am' => ['nullable', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:120',
                Rule::unique('personas', 'email')->whereNull('deleted_at')],
            'telefono' => ['nullable', 'digits:10'],
            'contrasena' => ReglasContrasena::reglas(),
            'aviso' => ['accepted'],
        ], [
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'aviso.accepted' => 'Debes aceptar el aviso de privacidad.',
            ...ReglasContrasena::mensajes(),
        ]);

        $rolCliente = Rol::firstOrCreate(['nombre' => Rol::CLIENTE]);

        $persona = Persona::create([
            ...collect($datos)->except(['aviso'])->all(),
            'id_rol' => $rolCliente->id_rol,
        ]);

        // Envía el correo de verificación (C15). La sesión se abre hasta que la persona verifique.
        $request->session()->put('correo_por_verificar', $persona->email);

        try {
            event(new Registered($persona));
        } catch (Throwable $e) {
            // Sin internet o con Gmail mal configurado la cuenta sí se crea; se puede reenviar después.
            report($e);

            return redirect()->route('verification.notice')
                ->withErrors(['email' => 'Tu cuenta se creó, pero no pudimos enviar el correo. Intenta con "Reenviar correo".']);
        }

        return redirect()->route('verification.notice');
    }
}
