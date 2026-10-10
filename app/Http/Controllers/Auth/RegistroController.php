<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

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
            'contrasena' => ['required', 'confirmed', Password::min(8)],
            'aviso' => ['accepted'],
        ], [
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'aviso.accepted' => 'Debes aceptar el aviso de privacidad.',
        ]);

        $rolCliente = Rol::firstOrCreate(['nombre' => Rol::CLIENTE]);

        $persona = Persona::create([
            ...collect($datos)->except(['aviso'])->all(),
            'id_rol' => $rolCliente->id_rol,
        ]);

        Auth::login($persona);
        $request->session()->regenerate();

        return redirect()->intended(route('solicitudes.index'))
            ->with('ok', '¡Bienvenido! Tu cuenta quedó lista.');
    }
}
