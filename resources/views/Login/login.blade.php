{{-- C04 · Iniciar sesión
     Tablas: personas, roles --}}
@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="acceso">
    <div class="acceso__imagen" style="background-image: url('{{ config('milcumbres.imagenes.acceso') }}')">
        <blockquote>“La montaña no se conquista; se cuida.”</blockquote>
    </div>

    <div class="acceso__form">
        <div>
            <h1 class="titulo-seccion">Inicia sesión</h1>
            <p class="texto-suave">Para enviar solicitudes y dar seguimiento a tus reservaciones.</p>

            <x-avisos />

            <form method="POST" action="{{ route('login.store') }}" class="pila mt-3" novalidate>
                @csrf
                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input @class(['control', 'es-invalido' => $errors->has('email')]) type="email" id="email" name="email"
                           value="{{ old('email') }}" autocomplete="email" required autofocus>
                </div>

                <div class="campo">
                    <label for="contrasena">Contraseña</label>
                    <div class="control-con-boton">
                        <input @class(['control', 'es-invalido' => $errors->has('contrasena')]) type="password" id="contrasena"
                               name="contrasena" autocomplete="current-password" required>
                        <button type="button" class="ver-contrasena" data-ver="contrasena" aria-pressed="false">Mostrar</button>
                    </div>
                    @error('contrasena') <span class="error">{{ $message }}</span> @enderror
                </div>

                <button class="btn btn--primario btn--bloque" type="submit">Ingresar</button>
            </form>

            <p class="texto-suave centrado mt-3">
                ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/ver-contrasena.js') }}" defer></script>
@endpush
