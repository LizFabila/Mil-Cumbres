{{-- C02 · Catálogo de experiencias
     Tablas: experiencias, zonas, catalogo_experiencias --}}
@extends('layouts.app')

@section('titulo', 'Experiencias')

@section('contenido')
<section class="hero-chico" style="background-image: url('{{ config('milcumbres.imagenes.hero_catalogo') }}')">
    <div class="contenedor">
        <span class="eyebrow eyebrow--claro">Catálogo</span>
        <h1 class="titulo-display">Experiencias</h1>
        <p>Bienestar, naturaleza y cultura con guías locales. Elige una o combina varias en la misma solicitud.</p>
    </div>
</section>

<div class="contenedor">
    <form class="filtros" method="GET" action="{{ route('experiencias.index') }}">
        <div class="campo campo--crece">
            <label for="q">Buscar</label>
            <input class="control" type="search" id="q" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Nombre de la experiencia">
        </div>
        <div class="campo">
            <label for="zona">Zona</label>
            <select class="control" id="zona" name="zona">
                <option value="">Todas</option>
                @foreach ($zonas as $zona)
                    <option value="{{ $zona->id_zona }}" @selected(($filtros['zona'] ?? null) == $zona->id_zona)>{{ $zona->nombre }}</option>
                @endforeach
            </select>
        </div>
        @if (! empty($filtros['tipo']))
            <input type="hidden" name="tipo" value="{{ $filtros['tipo'] }}">
        @endif
        <button class="btn btn--primario" type="submit">Filtrar</button>
    </form>

    <div class="filtros__chips" aria-label="Tipo de experiencia">
        <a href="{{ route('experiencias.index', request()->except('tipo', 'page')) }}"
           @class(['chip', 'chip--activo' => empty($filtros['tipo'])])>Todas</a>
        @foreach ($tipos as $tipo)
            <a href="{{ route('experiencias.index', [...request()->except('page'), 'tipo' => $tipo->id_catalogo_exp]) }}"
               @class(['chip', 'chip--activo' => ($filtros['tipo'] ?? null) == $tipo->id_catalogo_exp])>{{ $tipo->tipo }}</a>
        @endforeach
    </div>

    <p class="texto-suave texto-chico mt-3">{{ $experiencias->total() }} {{ \Illuminate\Support\Str::plural('experiencia', $experiencias->total()) }}</p>

    @if ($experiencias->isEmpty())
        <div class="vacio tarjeta">
            No encontramos experiencias con esos filtros.
            <a href="{{ route('experiencias.index') }}">Ver todas</a>
        </div>
    @else
        <div class="grid-3">
            @foreach ($experiencias as $experiencia)
                <x-exp-tarjeta :experiencia="$experiencia" />
            @endforeach
        </div>
        {{ $experiencias->links() }}
    @endif
</div>
@endsection
