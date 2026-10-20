
{{-- C01 · Inicio rediseñado
     Tablas: catalogo_experiencias, experiencias
--}}

@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')

@php
    $mensajesTipo = [
        'Bienestar' => 'Un momento para respirar, descansar y reconectar.',
        'Natural' => 'Explora paisajes, senderos y la vida del bosque.',
        'Cultural' => 'Descubre historias, tradiciones y saberes locales.',
    ];
@endphp

<div class="inicio">

    {{-- ======================================= --}}
    {{-- HERO PRINCIPAL --}}
    {{-- ======================================= --}}

    <section class="inicio-hero">

        <div class="inicio-hero__foto">
            <img
                src="{{ config('milcumbres.imagenes.hero_inicio_local') ? asset(config('milcumbres.imagenes.hero_inicio_local')) : config('milcumbres.imagenes.hero_inicio') }}"
                alt="Paisaje natural de Valle de Bravo"
                fetchpriority="high"
            >
        </div>

        <div class="inicio-hero__sombra"></div>

        <div class="contenedor inicio-hero__contenido">

            <div class="inicio-hero__texto">

                <div class="inicio-hero__ubicacion">
                    <span class="inicio-hero__punto"></span>
                    {{ config('milcumbres.contacto.lugar') }}
                </div>

                <span class="inicio-hero__etiqueta">
                    TURISMO REGENERATIVO · MIL CUMBRES
                </span>

                <h1>
                    Hay lugares que
                    <em>no solo se visitan.</em>
                    Se viven.
                </h1>

                <p class="inicio-hero__descripcion">
                    Descubre Valle de Bravo a través de experiencias
                    de naturaleza, bienestar y cultura.
                    Conecta con el territorio y descubre
                    una forma diferente de viajar.
                </p>

                <div class="inicio-hero__acciones">

                    <a href="{{ route('experiencias.index') }}#catalogo-resultados"
                       class="inicio-btn inicio-btn--claro">
                        Explorar experiencias
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a href="{{ route('modelo') }}"
                       class="inicio-hero__enlace">
                        Conoce nuestra filosofía
                        <span aria-hidden="true">→</span>
                    </a>

                </div>

            </div>

            <div class="inicio-hero__nota">
                <div>
                    <strong>Otra manera de descubrir</strong>
                    <span>
                        Naturaleza, cultura y bienestar
                        en un mismo destino.
                    </span>
                </div>
            </div>

        </div>

        <a class="inicio-hero__scroll"
           href="#explorar"
           aria-label="Ir a las experiencias">
            <span>DESCUBRE MÁS</span>
            <span aria-hidden="true">↓</span>
        </a>

    </section>


    {{-- ======================================= --}}
    {{-- PRESENTACIÓN EDITORIAL --}}
    {{-- ======================================= --}}

    <section class="inicio-intro">

        <div class="contenedor inicio-intro__grid">

            <div class="inicio-intro__etiqueta" data-reveal>
                <span class="inicio-kicker">
                    <span class="inicio-kicker__linea"></span>
                    BIENVENIDO A MIL CUMBRES
                </span>
            </div>

            <div class="inicio-intro__contenido" data-reveal style="--retardo: 120ms">

                <h2>
                    El viaje comienza cuando
                    <em>conectas con el lugar.</em>
                </h2>

                <p>
                    Valle de Bravo es mucho más que un destino.
                    Es un encuentro con paisajes, tradiciones
                    y experiencias que invitan a descubrir
                    el entorno desde otra perspectiva.
                </p>

                <a href="{{ route('modelo') }}"
                   class="inicio-enlace">
                    Descubre nuestro modelo
                    <span aria-hidden="true">↗</span>
                </a>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- CATEGORÍAS DE EXPERIENCIAS --}}
    {{-- ======================================= --}}

    <section class="inicio-categorias" id="explorar">

        <div class="contenedor">

            <div class="inicio-cabecera" data-reveal>

                <div>

                    <span class="inicio-kicker">
                        <span class="inicio-kicker__linea"></span>
                        EXPERIENCIAS PARA TI
                    </span>

                    <h2>
                        Encuentra tu forma
                        <em>de vivir Valle.</em>
                    </h2>

                </div>

                <div class="inicio-cabecera__derecha">

                    <p>
                        Cada experiencia es una oportunidad
                        para descubrir, conectar y crear
                        recuerdos memorables.
                    </p>

                    <a href="{{ route('experiencias.index') }}#catalogo-resultados"
                       class="inicio-enlace">
                        Ver todas las experiencias
                        <span aria-hidden="true">↗</span>
                    </a>

                </div>

            </div>

            <div class="inicio-categorias__grid">

                @foreach ($tipos as $tipo)

                    <a class="inicio-categoria" data-reveal style="--retardo: {{ $loop->index * 110 }}ms"
                       href="{{ route('experiencias.index', ['tipo' => $tipo->id_catalogo_exp]) }}#catalogo-resultados">

                        <img
                            src="{{ config("milcumbres.imagenes.tipo.{$tipo->tipo}", config('milcumbres.imagenes.experiencia_default')) }}"
                            alt="Experiencias de {{ $tipo->tipo }}"
                            loading="lazy"
                        >

                        <div class="inicio-categoria__sombra"></div>

                        <div class="inicio-categoria__superior">

                            <span class="inicio-categoria__cantidad">
                                {{ $tipo->experiencias_count }}
                                {{ \Illuminate\Support\Str::plural('experiencia', $tipo->experiencias_count) }}
                            </span>

                        </div>

                        <div class="inicio-categoria__inferior">

                            <span class="inicio-categoria__nombre">
                                {{ $tipo->tipo }}
                            </span>

                            <p>
                                {{ $mensajesTipo[$tipo->tipo] ?? 'Descubre nuevas experiencias en Valle de Bravo.' }}
                            </p>

                            <div class="inicio-categoria__pie">
                                <span>Explorar categoría</span>
                                <span class="inicio-categoria__flecha"
                                      aria-hidden="true">
                                    ↗
                                </span>
                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- EXPERIENCIAS DESTACADAS --}}
    {{-- ======================================= --}}

    @if ($destacadas->isNotEmpty())

        <section class="inicio-destacadas">

            <div class="contenedor">

                <div class="inicio-cabecera" data-reveal>

                    <div>

                        <span class="inicio-kicker">
                            <span class="inicio-kicker__linea"></span>
                            DESCUBRE ALGO NUEVO
                        </span>

                        <h2>
                            Experiencias que
                            <em>vale la pena vivir.</em>
                        </h2>

                    </div>

                    <div class="inicio-cabecera__derecha">

                        <p>
                            Conoce algunas de nuestras
                            experiencias más recientes
                            y encuentra tu próxima aventura.
                        </p>

                    </div>

                </div>

                <div class="inicio-destacadas__grid">

                    @foreach ($destacadas as $experiencia)

                        <div class="inicio-destacadas__item" data-reveal style="--retardo: {{ $loop->index * 110 }}ms">
                            <x-exp-tarjeta :experiencia="$experiencia" />
                        </div>

                    @endforeach

                </div>

            </div>

        </section>

    @endif


    {{-- ======================================= --}}
    {{-- NUESTRA FORMA DE VIAJAR --}}
    {{-- ======================================= --}}

    <section class="inicio-filosofia">

        <div class="contenedor inicio-filosofia__grid">

            <div class="inicio-filosofia__principal" data-reveal>

                <span class="inicio-kicker inicio-kicker--claro">
                    <span class="inicio-kicker__linea"></span>
                    NUESTRA FILOSOFÍA
                </span>

                <h2>
                    Viajar puede ser
                    <em>algo extraordinario.</em>
                </h2>

                <p>
                    Creemos en una forma de turismo
                    que promueve el acercamiento con
                    la naturaleza, el respeto por
                    las comunidades y la valoración
                    del territorio.
                </p>

            </div>

            <div class="inicio-filosofia__visual" data-reveal style="--retardo: 150ms">


                <div class="inicio-filosofia__palabras">
                    <span>Conocer.</span>
                    <span>Conectar.</span>
                    <em>Valorar.</em>
                </div>

                <span class="inicio-filosofia__pie">
                    MIL CUMBRES / VALLE DE BRAVO
                </span>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- CÓMO RESERVAR --}}
    {{-- ======================================= --}}

    <section class="inicio-pasos">

        <div class="contenedor">

            <div class="inicio-pasos__encabezado" data-reveal>

                <span class="inicio-kicker">
                    <span class="inicio-kicker__linea"></span>
                    PLANEA TU EXPERIENCIA
                </span>

                <h2>
                    Tu próxima aventura
                    <em>está más cerca.</em>
                </h2>

                <p>
                    Reservar una experiencia en Mil Cumbres
                    es sencillo. Solo sigue estos pasos.
                </p>

            </div>

            <div class="inicio-pasos__grid">

                <div class="inicio-paso" data-reveal style="--retardo: 0ms">

                    <figure class="inicio-paso__foto">
                        <img src="{{ config('milcumbres.imagenes.pasos.explora') }}"
                             alt="Paisaje de Valle de Bravo para elegir experiencia" loading="lazy">
                    </figure>


                    <div class="inicio-paso__icono"
                         aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M15.5 8.5l-2 5-5 2 2-5z"/></svg></div>

                    <h3>Explora y elige</h3>

                    <p>
                        Conoce nuestras experiencias
                        y selecciona las actividades
                        que más te interesen.
                    </p>

                </div>

                <div class="inicio-paso" data-reveal style="--retardo: 140ms">

                    <figure class="inicio-paso__foto">
                        <img src="{{ config('milcumbres.imagenes.pasos.solicita') }}"
                             alt="Paisaje de Valle de Bravo para solicitar fecha" loading="lazy">
                    </figure>


                    <div class="inicio-paso__icono"
                         aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/><path d="M8 14h2M12 14h2M8 17h2"/></svg></div>

                    <h3>Solicita tu fecha</h3>

                    <p>
                        Indica cuándo deseas visitarnos
                        y cuántas personas participarán.
                        Confirmaremos la disponibilidad.
                    </p>

                </div>

                <div class="inicio-paso" data-reveal style="--retardo: 280ms">

                    <figure class="inicio-paso__foto">
                        <img src="{{ config('milcumbres.imagenes.pasos.confirma') }}"
                             alt="Personas disfrutando una actividad al aire libre" loading="lazy">
                    </figure>


                    <div class="inicio-paso__icono"
                         aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg></div>

                    <h3>Confirma y disfruta</h3>

                    <p>
                        Una vez confirmada tu solicitud,
                        registra tu pago y prepárate
                        para vivir la experiencia.
                    </p>

                </div>

            </div>

            <div class="inicio-pasos__accion">

                <a href="{{ route('experiencias.index') }}#catalogo-resultados"
                   class="inicio-btn inicio-btn--verde">
                    Encuentra tu experiencia
                    <span aria-hidden="true">↗</span>
                </a>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- CONSULTORÍA --}}
    {{-- ======================================= --}}

    <section class="inicio-consultoria">

        <div class="contenedor">

            <div class="inicio-consultoria__bloque">

                <div class="inicio-consultoria__imagen" data-reveal>

                    <img
                        src="{{ config('milcumbres.imagenes.consultoria') }}"
                        alt="Plántulas y naturaleza para proyectos regenerativos"
                        loading="lazy"
                    >

                    <span class="inicio-consultoria__marca">
                        MIL CUMBRES
                        <small>TURISMO REGENERATIVO</small>
                    </span>

                </div>

                <div class="inicio-consultoria__contenido" data-reveal style="--retardo: 120ms">

                    <span class="inicio-kicker">
                        <span class="inicio-kicker__linea"></span>
                        PARA EMPRESAS Y PRODUCTORES
                    </span>

                    <h2>
                        Construyamos juntos
                        <em>un futuro diferente.</em>
                    </h2>

                    <p>
                        Acompañamos a organizaciones,
                        empresas y proyectos agrarios
                        interesados en desarrollar
                        experiencias de turismo regenerativo
                        y fortalecer su relación con
                        el territorio.
                    </p>

                    <div class="inicio-consultoria__separador"></div>

                    <a href="{{ route('consultoria.index') }}"
                       class="inicio-btn inicio-btn--verde">
                        Descubre nuestras consultorías
                        <span aria-hidden="true">↗</span>
                    </a>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection
