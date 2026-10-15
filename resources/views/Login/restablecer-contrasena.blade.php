{{-- C14 · Restablecer contraseña (se abre desde el enlace del correo)
     Tablas: personas, password_reset_tokens --}}
@extends('layouts.app')

@section('titulo', 'Nueva contraseña')

@section('contenido')
<div class="acceso">
    <div class="acceso__imagen" style="background-image: url('{{ config('milcumbres.imagenes.acceso') }}')">
        <blockquote>“La montaña no se conquista; se cuida.”</blockquote>
    </div>

    <div class="acceso__form">
        <div>
            <h1 class="titulo-seccion">Crea una nueva contraseña</h1>
            <p class="texto-suave">Elige una contraseña segura que no uses en otros sitios.</p>

            <x-avisos />

            <form method="POST" action="{{ route('password.update') }}" class="pila mt-3" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input @class(['control', 'es-invalido' => $errors->has('email')]) type="email" id="email" name="email"
                           value="{{ old('email', $email) }}" autocomplete="email" required>
                </div>

                <div class="campo">
                    <label for="contrasena">Nueva contraseña</label>
                    <div class="control-con-boton">
                        <input @class(['control', 'es-invalido' => $errors->has('contrasena')]) type="password" id="contrasena"
                               name="contrasena" minlength="8" autocomplete="new-password" required autofocus aria-describedby="requisitos-contrasena">
                        <button type="button" class="ver-contrasena" data-ver="contrasena" aria-pressed="false">Mostrar</button>
                    </div>
                    <x-requisitos-contrasena para="contrasena" />
                </div>

                <div class="campo">
                    <label for="contrasena_confirmation">Confirmar contraseña</label>
                    <input class="control" type="password" id="contrasena_confirmation" name="contrasena_confirmation"
                           autocomplete="new-password" required>
                    <span class="error" id="coincide-contrasena" hidden>Las contraseñas no coinciden.</span>
                </div>

                <button class="btn btn--primario btn--bloque" type="submit">Guardar contraseña</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/ver-contrasena.js') }}" defer></script>
    <script src="{{ asset('js/validar-contrasena.js') }}" defer></script>
@endpush
