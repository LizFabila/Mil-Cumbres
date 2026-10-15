
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
      href="{{ asset('css/catalogo-milcumbres.css') }}">
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
    {{-- 01 · HERO PRINCIPAL --}}
    {{-- ========================================= --}}

    <section class="mc-cat-hero">

        <img
            class="mc-cat-hero__imagen"
            src="{{ config('milcumbres.imagenes.hero_catalogo') }}"
            alt="Paisaje natural de Valle de Bravo"
            fetchpriority="high"
        >

        <div class="mc-cat-hero__overlay"></div>

        <div class="contenedor mc-cat-hero__contenido">

            <div class="mc-cat-hero__principal">

                <div class="mc-cat-hero__breadcrumb">

                    <a href="{{ route('inicio') }}">
                        Inicio
                    </a>

                    <span aria-hidden="true">/</span>

                    <span>Experiencias</span>

                </div>

                <span class="mc-cat-etiqueta mc-cat-etiqueta--clara">
                    <span class="mc-cat-etiqueta__linea"></span>
                    MIL CUMBRES · VALLE DE BRAVO
                </span>

                <h1>
                    Descubre.
                    <em>Conecta.</em>
                    Vive el lugar.
                </h1>

                <p>
                    Paisajes que inspiran, tradiciones
                    que permanecen y momentos que
                    merecen ser vividos.
                    Encuentra tu próxima experiencia
                    en Valle de Bravo.
                </p>

                <a href="#explorar-experiencias"
                   class="mc-cat-boton mc-cat-boton--blanco">

                    Explorar experiencias

                    <span aria-hidden="true">↘</span>

                </a>

            </div>

            <div class="mc-cat-hero__detalle">


                <strong>
                    Tu próxima historia comienza aquí.
                </strong>

                <span>
                    NATURALEZA / BIENESTAR / CULTURA
                </span>

            </div>

        </div>

        <div class="mc-cat-hero__inferior">
            <div class="contenedor">
                <span>EXPLORA VALLE DE BRAVO</span>
                <span>UNA FORMA DIFERENTE DE VIAJAR</span>
            </div>
        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 02 · INTRODUCCIÓN --}}
    {{-- ========================================= --}}

    <section class="mc-cat-intro">

        <div class="contenedor mc-cat-intro__grid">

            <div>

                <span class="mc-cat-etiqueta">
                    <span class="mc-cat-etiqueta__linea"></span>
                    EXPERIENCIAS MIL CUMBRES
                </span>

                <h2>
                    Elige cómo quieres
                    <em>vivir el destino.</em>
                </h2>

            </div>

            <div class="mc-cat-intro__derecha">

                <p>
                    Desde un recorrido entre bosques
                    hasta un encuentro con las tradiciones
                    locales. Explora nuestras actividades
                    y encuentra una experiencia
                    que conecte contigo.
                </p>

                <span class="mc-cat-intro__firma">
                    DESCUBRE · CONECTA · DISFRUTA
                </span>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 03 · CATEGORÍAS FOTOGRÁFICAS --}}
    {{-- ========================================= --}}

    <section class="mc-cat-categorias">

        <div class="contenedor">

            <div class="mc-cat-categorias__encabezado">

                <div>

                    <span class="mc-cat-etiqueta">
                        <span class="mc-cat-etiqueta__linea"></span>
                        ENCUENTRA TU CAMINO
                    </span>

                    <h2>
                        Tres maneras de
                        <em>explorar Valle.</em>
                    </h2>

                </div>

                {{-- BOTÓN "TODAS": quita el filtro de categoría --}}
                <a href="{{ route('experiencias.index') }}#explorar-experiencias"
                   @class([
                       'mc-cat-todas',
                       'mc-cat-todas--activa' => $tipoActivo === ''
                   ])
                   @if ($tipoActivo === '') aria-current="true" @endif>

                    Todas las experiencias
                    <span aria-hidden="true">↗</span>

                </a>

            </div>

            <div class="mc-cat-categorias__grid">

                @foreach ($tipos as $tipo)

                    @php
                        $categoriaSeleccionada =
                            $tipoActivo === (string) $tipo->id_catalogo_exp;
                    @endphp

                    <a
                        href="{{ route('experiencias.index', array_merge(
                            request()->except('tipo', 'page'),
                            ['tipo' => $tipo->id_catalogo_exp]
                        )) }}#explorar-experiencias"

                        @class([
                            'mc-cat-categoria',
                            'mc-cat-categoria--activa' => $categoriaSeleccionada
                        ])
                    >

                        <img
                            src="{{ config(
                                "milcumbres.imagenes.tipo.{$tipo->tipo}",
                                config('milcumbres.imagenes.experiencia_default')
                            ) }}"
                            alt="Categoría de experiencias {{ $tipo->tipo }}"
                            loading="lazy"
                        >

                        <span class="mc-cat-categoria__sombra"></span>

                        <div class="mc-cat-categoria__superior">

                            <span>
                                {{ sprintf('%02d', $loop->iteration) }}
                                / EXPLORA
                            </span>

                            <span class="mc-cat-categoria__flecha"
                                  aria-hidden="true">
                                ↗
                            </span>

                        </div>

                        <div class="mc-cat-categoria__inferior">

                            <span class="mc-cat-categoria__etiqueta">
                                EXPERIENCIAS DE
                            </span>

                            <h3>
                                {{ $tipo->tipo }}
                            </h3>

                            <p>
                                {{ $mensajesTipo[$tipo->tipo] ?? 'Descubre nuevas experiencias.' }}
                            </p>

                            <div class="mc-cat-categoria__pie">
                                Descubrir actividades
                                <span aria-hidden="true">→</span>
                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 04 · CATÁLOGO Y FILTROS --}}
    {{-- ========================================= --}}

    <section class="mc-cat-explorar"
             id="explorar-experiencias">

        <div class="contenedor">

            <div class="mc-cat-explorar__encabezado">

                <div>

                    <span class="mc-cat-etiqueta">
                        <span class="mc-cat-etiqueta__linea"></span>
                        ENCUENTRA TU EXPERIENCIA
                    </span>

                    <h2>
                        Tu próxima aventura
                        <em>te espera.</em>
                    </h2>

                </div>

                <p>
                    Busca por nombre, selecciona
                    una zona o explora nuestras
                    categorías disponibles.
                </p>

            </div>


            <div class="mc-cat-layout">

                {{-- ================================= --}}
                {{-- PANEL DE FILTROS --}}
                {{-- ================================= --}}

                <aside class="mc-cat-sidebar">

                    <div class="mc-cat-filtros">

                        <div class="mc-cat-filtros__titulo">


                            <div>

                                <span class="mc-cat-filtros__subtitulo">
                                    PERSONALIZA TU BÚSQUEDA
                                </span>

                                <h3>Encuentra tu plan</h3>

                            </div>

                        </div>

                        <form
                            method="GET"
                            action="{{ route('experiencias.index') }}"
                            class="mc-cat-filtros__form"
                        >

                            {{-- BUSCADOR --}}

                            <div class="mc-cat-campo">

                                <label for="mc-busqueda">
                                    ¿Qué te gustaría hacer?
                                </label>

                                <div class="mc-cat-input-wrap">

                                    <input
                                        type="search"
                                        id="mc-busqueda"
                                        name="q"
                                        value="{{ $busqueda }}"
                                        placeholder="Buscar experiencias..."
                                    >

                                    <span aria-hidden="true">⌕</span>

                                </div>

                            </div>


                            {{-- ZONA --}}

                            <div class="mc-cat-campo">

                                <label for="mc-zona">
                                    ¿Dónde quieres explorar?
                                </label>

                                <select id="mc-zona" name="zona">

                                    <option value="">
                                        Todas las zonas
                                    </option>

                                    @foreach ($zonas as $zona)

                                        <option
                                            value="{{ $zona->id_zona }}"
                                            @selected(
                                                $zonaActiva === (string) $zona->id_zona
                                            )
                                        >
                                            {{ $zona->nombre }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- CONSERVAR TIPO SELECCIONADO --}}

                            @if ($tipoActivo !== '')

                                <input
                                    type="hidden"
                                    name="tipo"
                                    value="{{ $tipoActivo }}"
                                >

                            @endif


                            <button
                                type="submit"
                                class="mc-cat-boton mc-cat-boton--verde"
                            >
                                Buscar experiencias
                                <span aria-hidden="true">↗</span>
                            </button>

                            @if ($hayFiltros)

                                <a
                                    href="{{ route('experiencias.index') }}#explorar-experiencias"
                                    class="mc-cat-filtros__limpiar"
                                >
                                    Limpiar todos los filtros
                                </a>

                            @endif

                        </form>

                    </div>


                    {{-- BLOQUE EDITORIAL LATERAL --}}

                    <div class="mc-cat-sidebar__nota">


                        <strong>
                            Cada viaje tiene
                            una historia.
                        </strong>

                        <p>
                            Descubre experiencias
                            de naturaleza, bienestar
                            y cultura diseñadas
                            para conocer Valle de Bravo.
                        </p>

                        <a href="{{ route('modelo') }}">
                            Nuestra filosofía
                            <span aria-hidden="true">↗</span>
                        </a>

                    </div>

                </aside>


                {{-- ================================= --}}
                {{-- RESULTADOS --}}
                {{-- ================================= --}}

                <div class="mc-cat-resultados">

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
                            ) }}#explorar-experiencias"

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
                                )) }}#explorar-experiencias"

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

                            <a href="{{ route('experiencias.index') }}#explorar-experiencias">
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

                                        @if ($experiencia->zona)

                                            <span class="mc-cat-card__ubicacion">

                                                <span aria-hidden="true">⌖</span>

                                                {{ $experiencia->zona->nombre }}

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
