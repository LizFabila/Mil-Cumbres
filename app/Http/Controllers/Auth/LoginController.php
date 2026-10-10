<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /** C04 · Iniciar sesión */
    public function create(): View
    {
        return view('Login.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'contrasena' => ['required', 'string'],
        ]);

        // La clave "password" es la que Auth compara contra getAuthPassword().
        if (! Auth::attempt(['email' => $datos['email'], 'password' => $datos['contrasena']])) {
            throw ValidationException::withMessages([
                'email' => 'El correo o la contraseña no son correctos.',
            ]);
        }

        $request->session()->regenerate();

        $destino = Auth::user()->esAdmin() ? route('admin.resumen') : route('solicitudes.index');

        return redirect()->intended($destino);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
