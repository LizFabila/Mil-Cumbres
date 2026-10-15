<?php

namespace App\Support;

/**
 * Reglas de contraseña segura compartidas por el registro (C05)
 * y el restablecimiento de contraseña (C14).
 * Deben coincidir con los requisitos que muestra public/js/validar-contrasena.js.
 */
class ReglasContrasena
{
    public static function reglas(): array
    {
        return [
            'required',
            'string',
            'confirmed',
            'min:8',
            'regex:/[a-zA-Z]/',   // al menos una letra
            'regex:/[A-Z]/',      // al menos una mayúscula
            'regex:/[0-9]/',      // al menos un número
            'regex:/[^A-Za-z0-9]/', // al menos un carácter especial
        ];
    }

    public static function mensajes(): array
    {
        return [
            'contrasena.required' => 'Escribe una contraseña.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.regex' => 'La contraseña debe tener al menos una letra, una letra mayúscula, un número y un carácter especial.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
