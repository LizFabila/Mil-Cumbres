{{-- C13 · Nuestro modelo (página informativa, sin tablas) --}}
@extends('layouts.app')

@section('titulo', 'Nuestro modelo')

@section('contenido')
<section class="hero-chico" style="background-image: url('{{ config('milcumbres.imagenes.modelo') }}')">
    <div class="contenedor">
        <span class="eyebrow eyebrow--claro">Nuestro modelo</span>
        <h1 class="titulo-display">Turismo regenerativo</h1>
        <p>No basta con no dañar: cada visita debe dejar el territorio, y a su gente, un poco mejor.</p>
    </div>
</section>

<section class="seccion">
    <div class="contenedor bloque-imagen">
        <div>
            <span class="eyebrow">Qué nos mueve</span>
            <h2 class="titulo-seccion">Del turismo sostenible al regenerativo</h2>
            <p class="texto-suave">
                El turismo sostenible busca reducir el impacto. Nosotros diseñamos experiencias que suman:
                restauran ecosistemas, fortalecen la economía de las comunidades y reconectan a las personas con su entorno.
            </p>
            <ul class="lista-check">
                <li>Grupos pequeños con cupo máximo por fecha.</li>
                <li>Guías y anfitriones de las comunidades locales.</li>
                <li>Una parte de cada pago se reinvierte en restauración.</li>
            </ul>
        </div>
        <img src="{{ config('milcumbres.imagenes.tipo.Natural') }}" alt="Martín pescador posado en una rama" loading="lazy">
    </div>
</section>

<section class="seccion seccion--alt">
    <div class="contenedor">
        <h2 class="titulo-seccion">Tres pilares</h2>
        <div class="grid-3 mt-3">
            <div class="pilar">
                <h3>Bienestar</h3>
                <p class="texto-suave">Prácticas que cuidan cuerpo y mente, inspiradas en saberes tradicionales.</p>
            </div>
            <div class="pilar">
                <h3>Natural</h3>
                <p class="texto-suave">Recorridos de bajo impacto que financian la conservación de bosques y cuerpos de agua.</p>
            </div>
            <div class="pilar">
                <h3>Cultural</h3>
                <p class="texto-suave">Encuentros con artesanos, cocineras y productores que mantienen viva la identidad local.</p>
            </div>
        </div>
    </div>
</section>

<section class="seccion">
    <div class="contenedor centrado">
        <h2 class="titulo-seccion">¿Listo para vivirlo?</h2>
        <div class="fila mt-3 fila--centro">
            <a class="btn btn--primario" href="{{ route('experiencias.index') }}">Ver experiencias</a>
            <a class="btn btn--secundario" href="{{ route('consultoria.index') }}">Consultoría</a>
        </div>
    </div>
</section>
@endsection
