{{-- C15 · Verificar correo electrónico
     Tablas: personas --}}
@extends('layouts.app')

@section('titulo', 'Verifica tu correo')

@section('contenido')
<div class="acceso">
    <div class="acceso__imagen" style="background-image: url('{{ config('milcumbres.imagenes.acceso') }}')">
        <blockquote>Una cuenta para solicitar, pagar y seguir tus experiencias.</blockquote>
    </div>

    <div class="acceso__form">
        <div>
            <h1 class="titulo-seccion">Revisa tu correo</h1>
            <p class="texto-suave">
                Te enviamos un enlace a <strong>{{ $correo }}</strong>.
                Ábrelo para confirmar que el correo es tuyo y activar tu cuenta. Hasta entonces no podrás iniciar sesión.
            </p>
            <p class="texto-suave">Si no lo ves en unos minutos, revisa la carpeta de spam o promociones.</p>

            <x-avisos />

            <form method="POST" action="{{ route('verification.send') }}" class="pila mt-3">
                @csrf
                <button class="btn btn--primario btn--bloque" type="submit">Reenviar correo</button>
            </form>

            <p class="texto-suave centrado mt-3">
                ¿Escribiste mal tu correo? <a href="{{ route('registro') }}">Regístrate de nuevo</a>
                · <a href="{{ route('login') }}">Ir a iniciar sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection
