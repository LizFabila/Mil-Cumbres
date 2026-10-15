{{-- C14 · Olvidé mi contraseña
     Tablas: personas, password_reset_tokens --}}
@extends('layouts.app')

@section('titulo', 'Recuperar contraseña')

@section('contenido')
<div class="acceso">
    <div class="acceso__imagen" style="background-image: url('{{ config('milcumbres.imagenes.acceso') }}')">
        <blockquote>“La montaña no se conquista; se cuida.”</blockquote>
    </div>

    <div class="acceso__form">
        <div>
            <h1 class="titulo-seccion">¿Olvidaste tu contraseña?</h1>
            <p class="texto-suave">Escribe el correo con el que te registraste y te enviaremos un enlace para crear una nueva.</p>

            <x-avisos />

            <form method="POST" action="{{ route('password.email') }}" class="pila mt-3" novalidate>
                @csrf
                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input @class(['control', 'es-invalido' => $errors->has('email')]) type="email" id="email" name="email"
                           value="{{ old('email') }}" autocomplete="email" required autofocus>
                </div>

                <button class="btn btn--primario btn--bloque" type="submit">Enviar enlace</button>
            </form>

            <p class="texto-suave centrado mt-3">
                ¿Ya la recordaste? <a href="{{ route('login') }}">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection
