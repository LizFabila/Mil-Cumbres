
{{-- C02 · Catálogo de experiencias
     Tablas: experiencias, zonas, catalogo_experiencias
--}}

@extends('layouts.app')

@section('titulo', 'Explora nuestras experiencias')

@section('descripcion',
    'Descubre experiencias de naturaleza, bienestar y cultura en Valle de Bravo.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ \App\Support\Temas::hoja('catalogo-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    $tipoActivo = (string) ($filtros['tipo'] ?? '');
    $zonaActiva = (string) ($filtros['zona'] ?? '');
    $busqueda = $filtros['q'] ?? '';

    $hayFiltros = $tipoActivo !== ''
        || $zonaActiva !== ''
        || trim((string) $busqueda) !== '';

    $mensajesTipo = [
        'Natural' => 'Senderos, paisajes y aventuras al aire libre.',
        'Bienestar' => 'Momentos de equilibrio y conexión personal.',
        'Cultural' => 'Tradiciones, historia y saberes locales.',
    ];
@endphp

<div class="mc-catalogo">

    {{-- ========================================= --}}
    {{-- 04 · CATÁLOGO Y FILTROS --}}
    {{-- ========================================= --}}

    <section class="mc-cat-explorar"
             id="explorar-experiencias">

        <div class="contenedor">

            <div class="mc-cat-layout mc-cat-layout--completo">

                {{-- ================================= --}}
                {{-- RESULTADOS (a todo el ancho) --}}
                {{-- ================================= --}}

                <div class="mc-cat-resultados" id="catalogo-resultados">

                    <div class="mc-cat-resultados__cabecera">

                        <div>

                            <span class="mc-cat-resultados__etiqueta">
                                EXPLORA NUESTRA SELECCIÓN
                            </span>

                            <h3>
                                Experiencias disponibles
                            </h3>

                        </div>

                        <span class="mc-cat-resultados__conteo">

                            <strong>
                                {{ $experiencias->total() }}
                            </strong>

                            {{ \Illuminate\Support\Str::plural(
                                'resultado',
                                $experiencias->total()
                            ) }}

                        </span>

                    </div>


                    {{-- FILTROS DE TIPO --}}

                    <div class="mc-cat-chips"
                         aria-label="Tipo de experiencia">

                        <a
                            href="{{ route(
                                'experiencias.index',
                                request()->except('tipo', 'page')
                            ) }}#catalogo-resultados"

                            @class([
                                'mc-cat-chip',
                                'mc-cat-chip--activo' => $tipoActivo === ''
                            ])
                        >
                            Todas las experiencias
                        </a>

                        @foreach ($tipos as $tipo)

                            <a
                                href="{{ route('experiencias.index', array_merge(
                                    request()->except('tipo', 'page'),
                                    ['tipo' => $tipo->id_catalogo_exp]
                                )) }}#catalogo-resultados"

                                @class([
                                    'mc-cat-chip',
                                    'mc-cat-chip--activo' =>
                                        $tipoActivo === (string) $tipo->id_catalogo_exp
                                ])
                            >
                                {{ $tipo->tipo }}
                            </a>

                        @endforeach

                    </div>


                    {{-- INDICADOR DE FILTROS ACTIVOS --}}

                    @if ($hayFiltros)

                        <div class="mc-cat-filtros-activos">

                            <span>
                                Estás viendo resultados filtrados
                            </span>

                            <a href="{{ route('experiencias.index') }}#catalogo-resultados">
                                Ver todos
                            </a>

                        </div>

                    @endif


                    {{-- ================================= --}}
                    {{-- LISTADO DE EXPERIENCIAS --}}
                    {{-- ================================= --}}

                    @if ($experiencias->isEmpty())

                        <div class="mc-cat-vacio">


                            <h3>
                                Aún no encontramos
                                tu próxima aventura.
                            </h3>

                            <p>
                                No hay experiencias que coincidan
                                con los filtros seleccionados.
                                Prueba con otra categoría,
                                zona o término de búsqueda.
                            </p>

                            <a href="{{ route('experiencias.index') }}"
                               class="mc-cat-boton mc-cat-boton--verde">

                                Explorar todas
                                <span aria-hidden="true">↗</span>

                            </a>

                        </div>

                    @else

                        <div class="mc-cat-grid">

                            @foreach ($experiencias as $experiencia)

                                <article class="mc-cat-card">

                                    {{-- FOTOGRAFÍA --}}

                                    <a
                                        class="mc-cat-card__imagen"
                                        href="{{ route(
                                            'experiencias.show',
                                            [
                                                $experiencia->id_experiencia,
                                                $experiencia->slug
                                            ]
                                        ) }}"
                                    >

                                        <img
                                            src="{{ $experiencia->imagen_url }}"
                                            alt="{{ $experiencia->nombre }}"
                                            loading="lazy"
                                        >

                                        <span class="mc-cat-card__sombra"></span>

                                        {{-- TIPO --}}
                                        @if ($experiencia->catalogo)

                                            <span class="mc-cat-card__tipo">
                                                {{ $experiencia->catalogo->tipo }}
                                            </span>

                                        @endif

                                        <span class="mc-cat-card__imagen-pie">

                                            <span>
                                                MIL CUMBRES
                                            </span>

                                            <span aria-hidden="true">↗</span>

                                        </span>

                                    </a>


                                    {{-- INFORMACIÓN --}}

                                    <div class="mc-cat-card__contenido">

                                        @if ($experiencia->zonas_texto)

                                            <span class="mc-cat-card__ubicacion">

                                                <span aria-hidden="true">⌖</span>

                                                {{ $experiencia->zonas_texto }}

                                            </span>

                                        @endif

                                        <h3>

                                            <a href="{{ route(
                                                'experiencias.show',
                                                [
                                                    $experiencia->id_experiencia,
                                                    $experiencia->slug
                                                ]
                                            ) }}">
                                                {{ $experiencia->nombre }}
                                            </a>

                                        </h3>

                                        <p class="mc-cat-card__descripcion">

                                            {{ \Illuminate\Support\Str::limit(
                                                $experiencia->resumen
                                                ?? 'Descubre todos los detalles de esta experiencia.',
                                                120
                                            ) }}

                                        </p>


                                        {{-- DETALLES --}}

                                        <div class="mc-cat-card__detalles">

                                            <div>
                                                <span aria-hidden="true">◷</span>
                                                {{ $experiencia->duracion_texto }}
                                            </div>

                                            <div>
                                                Experiencia local
                                            </div>

                                        </div>


                                        {{-- PRECIO Y ACCIÓN --}}

                                        <div class="mc-cat-card__pie">

                                            <div class="mc-cat-card__precio">

                                                <small>
                                                    PRECIO POR PERSONA
                                                </small>

                                                <strong>
                                                    {{ $experiencia->precio_texto }}
                                                </strong>

                                            </div>

                                            <a
                                                href="{{ route(
                                                    'experiencias.show',
                                                    [
                                                        $experiencia->id_experiencia,
                                                        $experiencia->slug
                                                    ]
                                                ) }}"
                                                class="mc-cat-card__boton"
                                                aria-label="Ver detalles de {{ $experiencia->nombre }}"
                                            >
                                                <span aria-hidden="true">↗</span>
                                            </a>

                                        </div>

                                    </div>

                                </article>

                            @endforeach

                        </div>


                        {{-- PAGINACIÓN ORIGINAL --}}

                        <div class="mc-cat-paginacion">
                            {{ $experiencias->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 05 · INVITACIÓN FINAL --}}
    {{-- ========================================= --}}

    <section class="mc-cat-final">

        <img
            src="{{ config('milcumbres.imagenes.modelo') }}"
            alt="Entorno natural de Valle de Bravo"
            loading="lazy"
        >

        <div class="mc-cat-final__overlay"></div>

        <div class="contenedor mc-cat-final__contenido">

            <span class="mc-cat-etiqueta mc-cat-etiqueta--clara">
                UNA NUEVA FORMA DE DESCUBRIR
            </span>

            <h2>
                No solo visites.
                <em>Vive la experiencia.</em>
            </h2>

            <p>
                Conoce nuestra propuesta de turismo
                regenerativo y descubre cómo
                buscamos conectar a las personas,
                las comunidades y el territorio.
            </p>

            <div class="mc-cat-final__acciones">

                <a href="{{ route('modelo') }}"
                   class="mc-cat-boton mc-cat-boton--blanco">
                    Conoce nuestro modelo
                    <span aria-hidden="true">↗</span>
                </a>

                <a href="{{ route('consultoria.index') }}"
                   class="mc-cat-final__enlace">
                    Explorar consultorías →
                </a>

            </div>

        </div>

    </section>

</div>

@endsection
