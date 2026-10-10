{{-- C10 · Consultoría
     Tablas: tipo_organizaciones --}}
@extends('layouts.app')

@section('titulo', 'Consultoría')

@section('contenido')
<section class="hero-chico" style="background-image: url('{{ config('milcumbres.imagenes.consultoria') }}')">
    <div class="contenedor">
        <span class="eyebrow eyebrow--claro">Consultoría</span>
        <h1 class="titulo-display">Diseñemos turismo que regenere tu territorio</h1>
        <p>Acompañamiento para empresas y proyectos agrarios que quieren abrir sus puertas sin agotar su entorno.</p>
        <a class="btn btn--primario mt-2" href="{{ route('consultoria.create') }}">Solicitar consultoría</a>
    </div>
</section>

<section class="seccion">
    <div class="contenedor">
        <span class="eyebrow">¿Para quién es?</span>
        <h2 class="titulo-seccion">Dos tipos de organización, un mismo enfoque</h2>

        <div class="grid-2 mt-3">
            @foreach ($tipos as $tipo)
                @php
                    $esAgrario = $tipo->nombre === 'Agrario';
                    $imagen = config('milcumbres.imagenes.'.($esAgrario ? 'consultoria_agrario' : 'consultoria_empresa'));
                @endphp
                <article class="exp-tarjeta">
                    <img class="exp-tarjeta__img" src="{{ $imagen }}" alt="" loading="lazy">
                    <div class="exp-tarjeta__cuerpo">
                        <h3>{{ $tipo->nombre }}</h3>
                        @if ($esAgrario)
                            <ul class="lista-check texto-suave">
                                <li>Diagnóstico de suelo, agua y biodiversidad del predio.</li>
                                <li>Diseño de recorridos y talleres con la comunidad.</li>
                                <li>Modelo de ingresos complementario a la producción.</li>
                            </ul>
                        @else
                            <ul class="lista-check texto-suave">
                                <li>Experiencias de integración y bienestar para equipos.</li>
                                <li>Programas de voluntariado con impacto medible.</li>
                                <li>Indicadores de huella y regeneración.</li>
                            </ul>
                        @endif
                        <a class="btn btn--secundario btn--chico mt-2" href="{{ route('consultoria.create', ['tipo' => $tipo->id_tipo_organizacion]) }}">
                            Solicitar como {{ \Illuminate\Support\Str::lower($tipo->nombre) }}
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="seccion seccion--alt">
    <div class="contenedor">
        <h2 class="titulo-seccion">Cómo trabajamos</h2>
        <div class="grid-3 mt-3">
            <div class="pilar"><h3>Escuchar</h3><p class="texto-suave">Visitamos el lugar y entendemos la necesidad de la organización.</p></div>
            <div class="pilar"><h3>Diseñar</h3><p class="texto-suave">Proponemos experiencias, capacidades de carga y precios justos.</p></div>
            <div class="pilar"><h3>Acompañar</h3><p class="texto-suave">Damos seguimiento hasta que el proyecto opera por sí solo.</p></div>
        </div>
    </div>
</section>
@endsection
