
{{-- C13 · Nuestro modelo
     Página informativa sin consultas a tablas
--}}

@extends('layouts.app')

@section('titulo', 'Nuestro modelo')

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/modelo-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-modelo">

    {{-- ========================================= --}}
    {{-- 01 · PORTADA PRINCIPAL --}}
    {{-- ========================================= --}}

    <section class="modelo-hero">

        <div class="modelo-hero__imagen">
            <img
                src="{{ config('milcumbres.imagenes.modelo') }}"
                alt="Paisaje natural que representa el turismo regenerativo"
                fetchpriority="high"
            >
        </div>

        <div class="modelo-hero__overlay"></div>

        <div class="contenedor modelo-hero__contenido">

            <div class="modelo-hero__texto">

                <span class="modelo-eyebrow modelo-eyebrow--light">
                    NUESTRA FILOSOFÍA
                </span>

                <h1>
                    Turismo
                    <em>regenerativo.</em>
                    Una nueva forma
                    de viajar.
                </h1>

                <p>
                    No basta con no dañar: cada visita
                    debe dejar el territorio, y a su gente,
                    un poco mejor.
                </p>

                <div class="modelo-hero__acciones">

                    <a href="#modelo-vision"
                       class="modelo-boton modelo-boton--claro">
                        Descubre nuestra visión
                        <span aria-hidden="true">↘</span>
                    </a>

                    <a href="{{ route('experiencias.index') }}"
                       class="modelo-hero__link">
                        Explorar experiencias
                        <span aria-hidden="true">↗</span>
                    </a>

                </div>

            </div>

            <div class="modelo-hero__detalle">
                <span class="modelo-hero__detalle-num">01</span>
                <span>
                    MIL CUMBRES
                    <small>UNA FORMA DIFERENTE DE CONOCER</small>
                </span>
            </div>

        </div>

        <div class="modelo-hero__inferior">
            <span>VALLE DE BRAVO · MÉXICO</span>
            <span>NATURALEZA / COMUNIDAD / CULTURA</span>
        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 02 · NUESTRA VISIÓN --}}
    {{-- ========================================= --}}

    <section class="modelo-vision" id="modelo-vision">

        <div class="contenedor">

            <div class="modelo-vision__intro">

                <div>
                    <span class="modelo-eyebrow">
                        <span class="modelo-linea"></span>
                        01 / LO QUE NOS MUEVE
                    </span>
                </div>

                <div>

                    <h2>
                        No se trata solo
                        de conocer un lugar.
                        <em>Se trata de conectar
                        con él.</em>
                    </h2>

                    <p>
                        El turismo regenerativo propone
                        ir más allá de reducir los impactos.
                        Buscamos que las experiencias
                        contribuyan al bienestar de las
                        comunidades, al cuidado del entorno
                        y a una conexión más profunda
                        entre las personas y el territorio.
                    </p>

                </div>

            </div>


            {{-- COMPOSICIÓN CON IMAGEN --}}

            <div class="modelo-vision__composicion">

                <div class="modelo-vision__foto">

                    <img
                        src="{{ config('milcumbres.imagenes.tipo.Natural') }}"
                        alt="Naturaleza y biodiversidad"
                        loading="lazy"
                    >

                    <div class="modelo-vision__foto-label">
                        <span>EL TERRITORIO</span>
                        <strong>Nuestra inspiración.</strong>
                    </div>

                </div>

                <div class="modelo-vision__manifiesto">


                    <span class="modelo-eyebrow modelo-eyebrow--light">
                        NUESTRO PROPÓSITO
                    </span>

                    <h3>
                        Viajar con
                        <em>conciencia.</em>
                        Conectar con
                        <em>propósito.</em>
                    </h3>

                    <p>
                        Diseñamos experiencias que buscan
                        restaurar ecosistemas, fortalecer
                        la economía de las comunidades
                        y reconectar a las personas
                        con su entorno.
                    </p>

                    <span class="modelo-vision__firma">
                        MIL CUMBRES · TURISMO REGENERATIVO
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 03 · DE LO SOSTENIBLE A LO REGENERATIVO --}}
    {{-- ========================================= --}}

    <section class="modelo-evolucion">

        <div class="contenedor">

            <div class="modelo-seccion-heading">

                <span class="modelo-eyebrow">
                    <span class="modelo-linea"></span>
                    02 / UNA NUEVA PERSPECTIVA
                </span>

                <h2>
                    Del turismo sostenible
                    <em>al regenerativo.</em>
                </h2>

                <p>
                    Dos enfoques que comparten el interés
                    por el territorio, pero que plantean
                    distintas formas de relacionarnos con él.
                </p>

            </div>

            <div class="modelo-evolucion__grid">

                <article class="modelo-evolucion__card
                                modelo-evolucion__card--sostenible">

                    <span class="modelo-evolucion__numero">01</span>

                    <span class="modelo-evolucion__etiqueta">
                        REDUCIR EL IMPACTO
                    </span>

                    <h3>
                        Turismo
                        sostenible
                    </h3>

                    <p>
                        Busca minimizar los efectos
                        negativos de la actividad turística
                        sobre el medioambiente y las
                        comunidades.
                    </p>

                    <span class="modelo-evolucion__pie">
                        CONSERVAR Y PROTEGER
                    </span>

                </article>


                <div class="modelo-evolucion__transicion"
                     aria-hidden="true">
                    <span>→</span>
                </div>


                <article class="modelo-evolucion__card
                                modelo-evolucion__card--regenerativo">

                    <span class="modelo-evolucion__numero">02</span>

                    <span class="modelo-evolucion__etiqueta">
                        GENERAR UN IMPACTO POSITIVO
                    </span>

                    <h3>
                        Turismo
                        <em>regenerativo</em>
                    </h3>

                    <p>
                        Busca que la experiencia turística
                        contribuya a restaurar ecosistemas,
                        fortalecer comunidades y mejorar
                        la relación con el territorio.
                    </p>

                    <span class="modelo-evolucion__pie">
                        RESTAURAR Y FORTALECER
                    </span>

                </article>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 04 · NUESTROS COMPROMISOS --}}
    {{-- ========================================= --}}

    <section class="modelo-compromisos">

        <div class="contenedor modelo-compromisos__grid">

            <div class="modelo-compromisos__intro">

                <span class="modelo-eyebrow modelo-eyebrow--light">
                    <span class="modelo-linea"></span>
                    LO QUE NOS GUÍA
                </span>

                <h2>
                    Pequeñas acciones.
                    <em>Grandes propósitos.</em>
                </h2>

                <p>
                    Nuestra propuesta de turismo
                    regenerativo se orienta por
                    principios que buscan cuidar
                    tanto el entorno como
                    a quienes lo habitan.
                </p>

            </div>

            <div class="modelo-compromisos__lista">

                <article class="modelo-compromiso">

                    <span class="modelo-compromiso__num">
                        01
                    </span>

                    <div>
                        <h3>Experiencias responsables</h3>

                        <p>
                            Grupos pequeños con cupo
                            máximo por fecha.
                        </p>
                    </div>
                    

                </article>

                <article class="modelo-compromiso">

                    <span class="modelo-compromiso__num">
                        02
                    </span>

                    <div>
                        <h3>Participación comunitaria</h3>

                        <p>
                            Guías y anfitriones
                            de las comunidades locales.
                        </p>
                    </div>


                </article>

                <article class="modelo-compromiso">

                    <span class="modelo-compromiso__num">
                        03
                    </span>

                    <div>
                        <h3>Contribución al territorio</h3>

                        <p>
                            Una parte de cada pago
                            se reinvierte en restauración.
                        </p>
                    </div>


                </article>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 05 · TRES PILARES --}}
    {{-- ========================================= --}}

    <section class="modelo-pilares">

        <div class="contenedor">

            <div class="modelo-pilares__cabecera">

                <div>

                    <span class="modelo-eyebrow">
                        <span class="modelo-linea"></span>
                        03 / LO QUE NOS DEFINE
                    </span>

                    <h2>
                        Tres caminos.
                        <em>Un mismo propósito.</em>
                    </h2>

                </div>

                <p>
                    Nuestro modelo integra tres
                    dimensiones que nos permiten
                    acercarnos a Valle de Bravo
                    de distintas maneras.
                </p>

            </div>


            <div class="modelo-pilares__grid">

                {{-- BIENESTAR --}}

                <article class="modelo-pilar">

                    <div class="modelo-pilar__imagen">

                        <img
                            src="{{ config('milcumbres.imagenes.tipo.Bienestar') }}"
                            alt="Experiencias de bienestar"
                            loading="lazy"
                        >

                        <span class="modelo-pilar__numero">
                            01 / 03
                        </span>

                    </div>

                    <div class="modelo-pilar__contenido">

                        <span class="modelo-pilar__etiqueta">
                            CONECTAR CONTIGO
                        </span>

                        <h3>Bienestar</h3>

                        <p>
                            Prácticas que cuidan cuerpo
                            y mente, inspiradas en
                            saberes tradicionales.
                        </p>


                    </div>

                </article>


                {{-- NATURAL --}}

                <article class="modelo-pilar">

                    <div class="modelo-pilar__imagen">

                        <img
                            src="{{ config('milcumbres.imagenes.tipo.Natural') }}"
                            alt="Experiencias en la naturaleza"
                            loading="lazy"
                        >

                        <span class="modelo-pilar__numero">
                            02 / 03
                        </span>

                    </div>

                    <div class="modelo-pilar__contenido">

                        <span class="modelo-pilar__etiqueta">
                            CONECTAR CON EL ENTORNO
                        </span>

                        <h3>Natural</h3>

                        <p>
                            Recorridos de bajo impacto
                            que financian la conservación
                            de bosques y cuerpos de agua.
                        </p>


                    </div>

                </article>


                {{-- CULTURAL --}}

                <article class="modelo-pilar">

                    <div class="modelo-pilar__imagen">

                        <img
                            src="{{ config('milcumbres.imagenes.tipo.Cultural') }}"
                            alt="Experiencias culturales"
                            loading="lazy"
                        >

                        <span class="modelo-pilar__numero">
                            03 / 03
                        </span>

                    </div>

                    <div class="modelo-pilar__contenido">

                        <span class="modelo-pilar__etiqueta">
                            CONECTAR CON LAS PERSONAS
                        </span>

                        <h3>Cultural</h3>

                        <p>
                            Encuentros con artesanos,
                            cocineras y productores
                            que mantienen viva
                            la identidad local.
                        </p>


                    </div>

                </article>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 06 · INVITACIÓN FINAL --}}
    {{-- ========================================= --}}

    <section class="modelo-final">

        <div class="modelo-final__imagen">

            <img
                src="{{ config('milcumbres.imagenes.hero_inicio_local') ? asset(config('milcumbres.imagenes.hero_inicio_local')) : config('milcumbres.imagenes.hero_inicio') }}"
                alt="Paisaje natural de Valle de Bravo"
                loading="lazy"
            >

        </div>

        <div class="modelo-final__overlay"></div>

        <div class="contenedor modelo-final__contenido">

            <span class="modelo-eyebrow modelo-eyebrow--light">
                EL SIGUIENTE PASO ES TUYO
            </span>

            <h2>
                El próximo viaje
                puede ser
                <em>diferente.</em>
            </h2>

            <p>
                Descubre las experiencias de Mil Cumbres
                y forma parte de una manera de viajar
                que busca conectar con la naturaleza,
                la cultura y las comunidades.
            </p>

            <div class="modelo-final__acciones">

                <a href="{{ route('experiencias.index') }}"
                   class="modelo-boton modelo-boton--claro">

                    Explorar experiencias
                    <span aria-hidden="true">↗</span>

                </a>

                <a href="{{ route('consultoria.index') }}"
                   class="modelo-boton modelo-boton--transparente">

                    Conoce nuestras consultorías
                    <span aria-hidden="true">↗</span>

                </a>

            </div>

        </div>

    </section>

</div>

@endsection
