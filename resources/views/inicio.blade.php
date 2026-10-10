{{-- C01 · Inicio
     Tablas: catalogo_experiencias, experiencias --}}
@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
<section class="hero" style="background-image: url('{{ config('milcumbres.imagenes.hero_inicio') }}')">
    <div class="contenedor">
        <span class="eyebrow eyebrow--claro">{{ config('milcumbres.contacto.lugar') }}</span>
        <h1 class="titulo-display">Viaja despacio. Deja la montaña mejor de como la encontraste.</h1>
        <p>Experiencias de bienestar, naturaleza y cultura diseñadas con comunidades locales.</p>
        <div class="fila mt-3">
            <a class="btn btn--primario" href="{{ route('experiencias.index') }}">Ver experiencias</a>
            <a class="btn btn--secundario" href="{{ route('modelo') }}">Conoce nuestro modelo</a>
        </div>
    </div>
</section>

<section class="seccion">
    <div class="contenedor">
        <div class="fila fila--entre fila--abajo">
            <div>
                <span class="eyebrow">Tres formas de vivirlo</span>
                <h2 class="titulo-seccion">¿Qué quieres sentir?</h2>
            </div>
            <a href="{{ route('experiencias.index') }}">Todo el catálogo →</a>
        </div>

        <div class="grid-3 mt-3">
            @foreach ($tipos as $tipo)
                <a class="tipo-tarjeta" href="{{ route('experiencias.index', ['tipo' => $tipo->id_catalogo_exp]) }}">
                    <img src="{{ config("milcumbres.imagenes.tipo.{$tipo->tipo}", config('milcumbres.imagenes.experiencia_default')) }}"
                         alt="Experiencias de tipo {{ $tipo->tipo }}" loading="lazy">
                    <div class="tipo-tarjeta__texto">
                        <h3>{{ $tipo->tipo }}</h3>
                        <span>{{ $tipo->experiencias_count }} {{ \Illuminate\Support\Str::plural('experiencia', $tipo->experiencias_count) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

@if ($destacadas->isNotEmpty())
<section class="seccion seccion--alt">
    <div class="contenedor">
        <span class="eyebrow">Recién agregadas</span>
        <h2 class="titulo-seccion">Experiencias destacadas</h2>
        <div class="grid-3 mt-3">
            @foreach ($destacadas as $experiencia)
                <x-exp-tarjeta :experiencia="$experiencia" />
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="seccion">
    <div class="contenedor">
        <div class="grid-3">
            <div class="pilar">
                <h3>1. Elige</h3>
                <p class="texto-suave">Explora el catálogo y arma tu visita con una o varias experiencias.</p>
            </div>
            <div class="pilar">
                <h3>2. Solicita</h3>
                <p class="texto-suave">Envía tu fecha preferida y el número de personas. Te confirmamos disponibilidad.</p>
            </div>
            <div class="pilar">
                <h3>3. Paga y disfruta</h3>
                <p class="texto-suave">Con la reservación confirmada, sube tu comprobante y listo.</p>
            </div>
        </div>
    </div>
</section>

<section class="seccion seccion--alt">
    <div class="contenedor bloque-imagen">
        <img src="{{ config('milcumbres.imagenes.consultoria') }}" alt="Plántulas en vivero comunitario" loading="lazy">
        <div>
            <span class="eyebrow">Para empresas y productores</span>
            <h2 class="titulo-seccion">Consultoría en turismo regenerativo</h2>
            <p class="texto-suave">Acompañamos a organizaciones y proyectos agrarios a diseñar experiencias que regeneran su territorio.</p>
            <a class="btn btn--secundario" href="{{ route('consultoria.index') }}">Conocer la consultoría</a>
        </div>
    </div>
</section>
@endsection
