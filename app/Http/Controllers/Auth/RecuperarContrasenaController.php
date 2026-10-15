<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Support\ReglasContrasena;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Throwable;

class RecuperarContrasenaController extends Controller
{
    /** C14 · Olvidé mi contraseña: pide el correo. */
    public function create(): View
    {
        return view('Login.olvide-contrasena');
    }

    /** Envía el enlace para restablecer la contraseña. */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']], [
            'email.required' => 'Escribe tu correo electrónico.',
            'email.email' => 'Escribe un correo electrónico válido.',
        ]);

        try {
            $estado = Password::sendResetLink($request->only('email'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['email' => 'No pudimos enviar el correo. Revisa tu conexión a internet e inténtalo de nuevo.']);
        }

        if ($estado === Password::RESET_THROTTLED) {
            return back()->withInput()
                ->withErrors(['email' => 'Ya te enviamos un enlace hace un momento. Espera un minuto antes de pedir otro.']);
        }

        // Mismo mensaje exista o no la cuenta, para no revelar qué correos están registrados.
        return back()->with('ok', 'Si el correo está registrado, te enviamos un enlace para restablecer tu contraseña. Revisa tu bandeja de entrada.');
    }

    /** C14 · Formulario para escribir la nueva contraseña (llega desde el enlace del correo). */
    public function edit(Request $request, string $token): View
    {
        return view('Login.restablecer-contrasena', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Guarda la nueva contraseña. */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'contrasena' => ReglasContrasena::reglas(),
        ], ReglasContrasena::mensajes());

        // El broker de Laravel espera la clave "password"; la columna real es "contrasena".
        $estado = Password::reset(
            [
                'email' => $request->input('email'),
                'token' => $request->input('token'),
                'password' => $request->input('contrasena'),
            ],
            function (Persona $persona, string $contrasena) {
                $persona->forceFill(['contrasena' => $contrasena])->save();
            }
        );

        if ($estado !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'El enlace no es válido o ya expiró. Pide uno nuevo desde "¿Olvidaste tu contraseña?".']);
        }

        return redirect()->route('login')
            ->with('ok', 'Tu contraseña se actualizó. Ya puedes iniciar sesión.');
    }
}
