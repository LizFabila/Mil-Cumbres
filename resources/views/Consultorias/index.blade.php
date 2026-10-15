
{{-- C10 · Consultoría
     Tablas: tipo_organizaciones
--}}

@extends('layouts.app')

@section('titulo', 'Consultoría')

@section('descripcion', 'Acompañamiento para empresas y proyectos agrarios que buscan desarrollar turismo regenerativo en su territorio.')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/consultoria-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-consultoria">

    {{-- ========================================= --}}
    {{-- 01 · PORTADA DE CONSULTORÍA --}}
    {{-- ========================================= --}}

    <section class="mc-consultoria-hero">

        <img
            src="{{ config('milcumbres.imagenes.consultoria') }}"
            alt="Paisaje natural que representa el territorio"
            class="mc-consultoria-hero__imagen"
            fetchpriority="high"
        >

        <div class="mc-consultoria-hero__overlay"></div>

        <div class="contenedor mc-consultoria-hero__contenedor">

            <nav class="mc-consultoria-migas"
                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">Inicio</a>
                <span aria-hidden="true">/</span>
                <span>Consultoría</span>

            </nav>

            <div class="mc-consultoria-hero__contenido">

                <span class="mc-consultoria-eyebrow mc-consultoria-eyebrow--claro">
                    <span class="mc-consultoria-raya"></span>
                    CONSULTORÍA · MIL CUMBRES
                </span>


                <h1>
                    Diseñemos turismo
                    que <em>regenere</em>
                    tu territorio.
                </h1>

                <p>
                    Acompañamiento para empresas
                    y proyectos agrarios que quieren
                    abrir sus puertas al turismo
                    sin agotar su entorno.
                </p>

                <div class="mc-consultoria-hero__acciones">

                    <a
                        href="{{ route('consultoria.create') }}"
                        class="mc-consultoria-boton mc-consultoria-boton--crema"
                    >
                        Solicitar consultoría
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a
                        href="#para-quien"
                        class="mc-consultoria-hero__enlace"
                    >
                        Descubre cómo podemos acompañarte
                        <span aria-hidden="true">↓</span>
                    </a>

                </div>

            </div>

            <div class="mc-consultoria-hero__pie">

                <span>
                    NATURALEZA · COMUNIDAD · TERRITORIO
                </span>

                <span>
                    VALLE DE BRAVO, MÉXICO
                </span>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 02 · PRESENTACIÓN DEL SERVICIO --}}
    {{-- ========================================= --}}

    <section class="mc-consultoria-intro">

        <div class="contenedor mc-consultoria-intro__grid">

            <div class="mc-consultoria-intro__titulo">

                <span class="mc-consultoria-eyebrow">
                    <span class="mc-consultoria-raya"></span>
                    NUESTRA VISIÓN
                </span>

                <h2>
                    El turismo puede
                    convertirse en una
                    <em>oportunidad para el lugar.</em>
                </h2>

            </div>

            <div class="mc-consultoria-intro__texto">


                <p>
                    Creemos en proyectos turísticos
                    que consideren las necesidades
                    de las organizaciones y las
                    características de su territorio.
                </p>

                <p>
                    Por eso, nuestro acompañamiento
                    comienza escuchando, continúa
                    con el diseño de propuestas
                    y busca que cada proyecto pueda
                    desarrollar sus propias capacidades.
                </p>

                <a href="{{ route('modelo') }}"
                   class="mc-consultoria-link">

                    Conoce nuestro modelo
                    <span aria-hidden="true">↗</span>

                </a>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 03 · TIPOS DE ORGANIZACIONES --}}
    {{-- ========================================= --}}

    <section class="mc-consultoria-tipos"
             id="para-quien">

        <div class="contenedor">

            <div class="mc-consultoria-seccion-titulo">

                <div>

                    <span class="mc-consultoria-eyebrow">
                        <span class="mc-consultoria-raya"></span>
                        01 / NUESTROS SERVICIOS
                    </span>

                    <h2>
                        Diferentes organizaciones.
                        <em>Un mismo propósito.</em>
                    </h2>

                </div>

                <p>
                    Descubre cómo podemos acompañar
                    a tu organización para diseñar
                    experiencias y proyectos turísticos
                    que valoren el territorio.
                </p>

            </div>


            {{-- TARJETAS DINÁMICAS DESDE LA BD --}}

            <div class="mc-consultoria-tipos__grid">

                @forelse ($tipos as $tipo)

                    @php
                        $esAgrario = $tipo->nombre === 'Agrario';

                        $imagen = config(
                            'milcumbres.imagenes.' .
                            ($esAgrario
                                ? 'consultoria_agrario'
                                : 'consultoria_empresa')
                        );
                    @endphp

                    <article class="mc-consultoria-tipo">

                        {{-- FOTOGRAFÍA --}}

                        <div class="mc-consultoria-tipo__visual">

                            <img
                                src="{{ $imagen }}"
                                alt="Consultoría para organización de tipo {{ $tipo->nombre }}"
                                loading="lazy"
                            >

                            <div class="mc-consultoria-tipo__sombra"></div>

                            <span class="mc-consultoria-tipo__numero">
                                {{ sprintf('%02d', $loop->iteration) }}
                                <span aria-hidden="true">/</span>
                                {{ sprintf('%02d', $tipos->count()) }}
                            </span>

                            <span class="mc-consultoria-tipo__categoria">
                                {{ $esAgrario
                                    ? 'TERRITORIO Y COMUNIDAD'
                                    : 'EMPRESA Y BIENESTAR' }}
                            </span>

                        </div>


                        {{-- INFORMACIÓN --}}

                        <div class="mc-consultoria-tipo__cuerpo">

                            <span class="mc-consultoria-eyebrow">
                                CONSULTORÍA ESPECIALIZADA
                            </span>

                            <h3>
                                {{ $tipo->nombre }}
                            </h3>

                            @if ($esAgrario)

                                <p class="mc-consultoria-tipo__intro">
                                    Acompañamiento a proyectos
                                    agrarios interesados en
                                    complementar sus actividades
                                    mediante experiencias turísticas.
                                </p>

                            @else

                                <p class="mc-consultoria-tipo__intro">
                                    Acompañamiento para empresas
                                    que buscan desarrollar
                                    experiencias de integración,
                                    bienestar e impacto territorial.
                                </p>

                            @endif


                            <div class="mc-consultoria-tipo__servicios">

                                <span>
                                    ¿QUÉ PODEMOS TRABAJAR?
                                </span>

                                <ul>

                                    @if ($esAgrario)

                                        <li>
                                            <span aria-hidden="true">✓</span>
                                            Diagnóstico de suelo,
                                            agua y biodiversidad
                                            del predio.
                                        </li>

                                        <li>
                                            <span aria-hidden="true">✓</span>
                                            Diseño de recorridos
                                            y talleres con la comunidad.
                                        </li>

                                        <li>
                                            <span aria-hidden="true">✓</span>
                                            Modelo de ingresos
                                            complementario
                                            a la producción.
                                        </li>

                                    @else

                                        <li>
                                            <span aria-hidden="true">✓</span>
                                            Experiencias de integración
                                            y bienestar para equipos.
                                        </li>

                                        <li>
                                            <span aria-hidden="true">✓</span>
                                            Programas de voluntariado
                                            con impacto medible.
                                        </li>

                                        <li>
                                            <span aria-hidden="true">✓</span>
                                            Indicadores de huella
                                            y regeneración.
                                        </li>

                                    @endif

                                </ul>

                            </div>


                            {{-- ENLACE CON PRESELECCIÓN ORIGINAL --}}

                            <a
                                href="{{ route('consultoria.create', [
                                    'tipo' => $tipo->id_tipo_organizacion
                                ]) }}"
                                class="mc-consultoria-tipo__boton"
                            >

                                <span>
                                    Solicitar como
                                    {{ \Illuminate\Support\Str::lower($tipo->nombre) }}
                                </span>

                                <span class="mc-consultoria-tipo__flecha"
                                      aria-hidden="true">
                                    ↗
                                </span>

                            </a>

                        </div>

                    </article>

                @empty

                    <div class="mc-consultoria-tipos__vacio">


                        <h3>
                            Próximamente más información.
                        </h3>

                        <p>
                            Todavía no se han registrado
                            tipos de organizaciones.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 04 · FILOSOFÍA DE ACOMPAÑAMIENTO --}}
    {{-- ========================================= --}}

    <section class="mc-consultoria-frase">

        <div class="contenedor mc-consultoria-frase__grid">

            <div class="mc-consultoria-frase__marca"
                 aria-hidden="true">
            </div>

            <div>

                <span class="mc-consultoria-eyebrow
                             mc-consultoria-eyebrow--claro">
                    MÁS ALLÁ DE UNA ACTIVIDAD TURÍSTICA
                </span>

                <blockquote>
                    No se trata solo de recibir
                    visitantes, sino de pensar
                    cómo cada experiencia puede
                    <em>contribuir al territorio.</em>
                </blockquote>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 05 · CÓMO TRABAJAMOS --}}
    {{-- ========================================= --}}

    <section class="mc-consultoria-proceso"
             id="como-trabajamos">

        <div class="contenedor">

            <div class="mc-consultoria-seccion-titulo">

                <div>

                    <span class="mc-consultoria-eyebrow">
                        <span class="mc-consultoria-raya"></span>
                        02 / NUESTRA METODOLOGÍA
                    </span>

                    <h2>
                        Un proceso cercano,
                        <em>de principio a fin.</em>
                    </h2>

                </div>

                <p>
                    Cada proyecto comienza con
                    una necesidad distinta.
                    Trabajamos mediante tres etapas
                    para comprenderla, diseñar
                    y acompañar su desarrollo.
                </p>

            </div>


            <div class="mc-consultoria-proceso__grid">

                {{-- ESCUCHAR --}}

                <article class="mc-consultoria-paso">

                    <img class="mc-consultoria-paso__foto"
                         src="{{ config('milcumbres.imagenes.metodologia.escuchar') }}"
                         alt="Trabajo de campo en el terreno" loading="lazy">

                    <div class="mc-consultoria-paso__top">

                        <span>
                            ETAPA 01
                        </span>

                        <span aria-hidden="true">↗</span>

                    </div>

                    <span class="mc-consultoria-paso__numero">
                        01
                    </span>

                    <span class="mc-consultoria-paso__icono"
                          aria-hidden="true">
                        ◌
                    </span>

                    <h3>
                        Escuchar.
                    </h3>

                    <p>
                        Visitamos el lugar
                        y entendemos la necesidad
                        de la organización.
                    </p>

                    <span class="mc-consultoria-paso__pie">
                        ENTENDER EL CONTEXTO
                    </span>

                </article>


                {{-- DISEÑAR --}}

                <article class="mc-consultoria-paso">

                    <img class="mc-consultoria-paso__foto"
                         src="{{ config('milcumbres.imagenes.metodologia.disenar') }}"
                         alt="Plántulas listas para plantar" loading="lazy">

                    <div class="mc-consultoria-paso__top">

                        <span>
                            ETAPA 02
                        </span>

                        <span aria-hidden="true">↗</span>

                    </div>

                    <span class="mc-consultoria-paso__numero">
                        02
                    </span>

                    <span class="mc-consultoria-paso__icono"
                          aria-hidden="true">
                        ◇
                    </span>

                    <h3>
                        Diseñar.
                    </h3>

                    <p>
                        Proponemos experiencias,
                        capacidades de carga
                        y precios justos.
                    </p>

                    <span class="mc-consultoria-paso__pie">
                        CONSTRUIR LA PROPUESTA
                    </span>

                </article>


                {{-- ACOMPAÑAR --}}

                <article class="mc-consultoria-paso">

                    <img class="mc-consultoria-paso__foto"
                         src="{{ config('milcumbres.imagenes.metodologia.acompanar') }}"
                         alt="Campo al atardecer, seguimiento a largo plazo" loading="lazy">

                    <div class="mc-consultoria-paso__top">

                        <span>
                            ETAPA 03
                        </span>

                        <span aria-hidden="true">↗</span>

                    </div>

                    <span class="mc-consultoria-paso__numero">
                        03
                    </span>


                    <h3>
                        Acompañar.
                    </h3>

                    <p>
                        Damos seguimiento
                        hasta que el proyecto
                        opera por sí solo.
                    </p>

                    <span class="mc-consultoria-paso__pie">
                        IMPULSAR LA AUTONOMÍA
                    </span>

                </article>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 06 · INVITACIÓN FINAL --}}
    {{-- ========================================= --}}

    <section class="mc-consultoria-cta">

        <div class="contenedor mc-consultoria-cta__grid">

            <div class="mc-consultoria-cta__contenido">

                <span class="mc-consultoria-eyebrow
                             mc-consultoria-eyebrow--claro">
                    COMENCEMOS A CONVERSAR
                </span>

                <h2>
                    Tu proyecto puede ser
                    el inicio de
                    <em>algo extraordinario.</em>
                </h2>

                <p>
                    Cuéntanos las necesidades
                    de tu organización.
                    El primer paso es conocer
                    tu propuesta y su contexto.
                </p>

                <a
                    href="{{ route('consultoria.create') }}"
                    class="mc-consultoria-boton
                           mc-consultoria-boton--crema"
                >
                    Solicitar una consultoría

                    <span aria-hidden="true">↗</span>
                </a>

            </div>

            <div class="mc-consultoria-cta__visual">

                <img
                    src="{{ config('milcumbres.imagenes.consultoria') }}"
                    alt="Naturaleza y territorio"
                    loading="lazy"
                >

                <div class="mc-consultoria-cta__visual-sello">
                    <strong>MIL CUMBRES</strong>
                    <small>TURISMO REGENERATIVO</small>
                </div>

            </div>

        </div>

    </section>

</div>

@endsection
