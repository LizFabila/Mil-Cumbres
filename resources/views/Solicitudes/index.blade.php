{{-- C08 · Mis solicitudes y consultorías

     Tablas: solicitudes, asigna_experiencia,

     experiencias, pagos, solicitud_consultorias,

     organizaciones

\--}}

@extends('layouts.app')

@section('titulo', 'Mis solicitudes')

@section('descripcion',

    'Consulta tus solicitudes de experiencias, pagos y consultorías en Mil Cumbres.'

)

@push('estilos')

<link rel="stylesheet"

      href="{{ asset('css/mis-solicitudes-milcumbres.css') }}">

@endpush

@section('contenido')

@php

    $totalReservaciones = $solicitudes->total();

    $totalConsultorias = $consultorias->count();

@endphp

<div class="mc-c08">

    {{-- ======================================== --}}

    {{-- 01 · PORTADA PERSONAL --}}

    {{-- ======================================== --}}

    <section class="mc-c08__hero">

        <div class="mc-c08__hero-overlay"></div>

        <div class="contenedor mc-c08__hero-contenido">

            <nav class="mc-c08__migas"

                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">

                    Inicio

                </a>

                <span>/</span>

                <span>Mis solicitudes</span>

            </nav>

            <span class="mc-c08__etiqueta

                         mc-c08__etiqueta--clara">

                MIL CUMBRES / MI ESPACIO

            </span>

            <h1>

                Tus experiencias,

                <em>en un solo lugar.</em>

            </h1>

            <p>

                Hola, {{ auth()->user()->nombre }}.

                Aquí puedes consultar tus reservaciones,

                revisar sus pagos y conocer el seguimiento

                de las consultorías que has solicitado.

            </p>

            <a

                href="{{ route('solicitudes.create') }}"

                class="mc-c08__boton mc-c08__boton--claro"

            >

                Solicitar nueva experiencia

                <span aria-hidden="true">↗</span>

            </a>

        </div>

        <div class="mc-c08__hero-pie">

            <div class="contenedor">

                <span>

                    MI ESPACIO · MIL CUMBRES

                </span>

                <span>

                    NATURALEZA / BIENESTAR / CULTURA

                </span>

            </div>

        </div>

    </section>

    {{-- ======================================== --}}

    {{-- 02 · RESUMEN PERSONAL --}}

    {{-- ======================================== --}}

    <section class="mc-c08__resumen">

        <div class="contenedor">

            <div class="mc-c08__bienvenida">

                <div>

                    <span class="mc-c08__etiqueta">

                        <span class="mc-c08__linea"></span>

                        TU ACTIVIDAD EN MIL CUMBRES

                    </span>

                    <h2>

                        Sigue el camino

                        <em>de tus experiencias.</em>

                    </h2>

                </div>

                <p>

                    Consulta tus solicitudes y revisa

                    en qué etapa se encuentra cada una.

                    Toda la información se obtiene de

                    los registros de tu cuenta.

                </p>

            </div>

            <div class="mc-c08__estadisticas">

                <a href="#mis-reservaciones"

                   class="mc-c08__estadistica">

                    <span class="mc-c08__estadistica-top">

                        EXPERIENCIAS

                        <span aria-hidden="true">↗</span>

                    </span>

                    <strong>

                        {{ $totalReservaciones }}

                    </strong>

                    <span class="mc-c08__estadistica-nombre">

                        {{ $totalReservaciones === 1

                            ? 'Solicitud de experiencia'

                            : 'Solicitudes de experiencias' }}

                    </span>

                    <span class="mc-c08__estadistica-pie">

                        Consultar mis reservaciones →

                    </span>

                </a>

                <a href="#mis-consultorias"

                   class="mc-c08__estadistica

                          mc-c08__estadistica--verde">

                    <span class="mc-c08__estadistica-top">

                        CONSULTORÍAS

                        <span aria-hidden="true">↗</span>

                    </span>

                    <strong>

                        {{ $totalConsultorias }}

                    </strong>

                    <span class="mc-c08__estadistica-nombre">

                        {{ $totalConsultorias === 1

                            ? 'Consultoría registrada'

                            : 'Consultorías registradas' }}

                    </span>

                    <span class="mc-c08__estadistica-pie">

                        Consultar seguimientos →

                    </span>

                </a>

            </div>

        </div>

    </section>

    {{-- ======================================== --}}

    {{-- 03 · CONTENIDO PERSONAL --}}

    {{-- ======================================== --}}

    <section class="mc-c08__contenido">

        <div class="contenedor">

            <x-avisos />

            {{-- ENLACES INTERNOS --}}

            <nav class="mc-c08__navegacion"

                 aria-label="Apartados de mis solicitudes">

                @if (request('seccion') !== 'consultorias')
                    <a href="#mis-reservaciones">
                        Mis reservaciones
                    </a>
                @endif

                @if (request('seccion') !== 'consultorias')
                    <a href="#mis-pagos">
                        Mis pagos
                    </a>
                @endif

                @if (request('seccion') !== 'reservaciones')
                    <a href="#mis-consultorias">
                        Mis consultorías
                    </a>
                @endif

                <a href="{{ route('experiencias.index') }}">

                    <span aria-hidden="true">↗</span>

                    Explorar experiencias

                </a>

            </nav>

            {{-- ==================================== --}}

            {{-- 04 · RESERVACIONES --}}

            {{-- ==================================== --}}

            @if (request('seccion') !== 'consultorias')

            <section

                class="mc-c08__apartado"

                id="mis-reservaciones"

            >

                <div class="mc-c08__encabezado">

                    <div>

                        <span class="mc-c08__etiqueta">

                            <span class="mc-c08__linea"></span>

                            EXPERIENCIAS TURÍSTICAS

                        </span>

                        <h2>

                            Mis

                            <em>reservaciones.</em>

                        </h2>

                        <p>

                            Consulta las experiencias

                            que has solicitado y revisa

                            el estado de cada reservación.

                        </p>

                    </div>

                    <a

                        href="{{ route('solicitudes.create') }}"

                        class="mc-c08__enlace"

                    >

                        Nueva solicitud

                        <span aria-hidden="true">↗</span>

                    </a>

                </div>

                {{-- SIN RESERVACIONES --}}

                @if ($solicitudes->isEmpty())

                    <div class="mc-c08__vacio">

                        <h3>

                            Tu próxima historia

                            está por comenzar.

                        </h3>

                        <p>

                            Todavía no tienes solicitudes

                            de experiencias. Explora

                            nuestras actividades

                            y encuentra tu próximo destino.

                        </p>

                        <a

                            href="{{ route('experiencias.index') }}"

                            class="mc-c08__boton mc-c08__boton--verde"

                        >

                            Descubrir experiencias

                            <span aria-hidden="true">↗</span>

                        </a>

                    </div>

                @else

                    <div class="mc-c08__reservaciones">

                        @foreach ($solicitudes as $s)

                            <article class="mc-c08__reserva">

                                {{-- CABECERA DEL EXPEDIENTE --}}

                                <div class="mc-c08__reserva-top">

                                    <div class="mc-c08__reserva-identidad">

                                        <span class="mc-c08__micro">

                                            FOLIO DE SOLICITUD

                                        </span>

                                        <strong class="mc-c08__folio">

                                            {{ $s->folio }}

                                        </strong>

                                    </div>

                                    <div class="mc-c08__estados">

                                        <div class="mc-c08__estado-grupo">

                                            <span class="mc-c08__micro">

                                                SOLICITUD

                                            </span>

                                            <x-estado :valor="$s->estado" />

                                        </div>

                                        @if ($s->ultimoPago)

                                            <div class="mc-c08__estado-grupo">

                                                <span class="mc-c08__micro">

                                                    PAGO

                                                </span>

                                                <x-estado

                                                    :valor="$s->ultimoPago->estado"

                                                />

                                            </div>

                                        @endif

                                    </div>

                                </div>

                                {{-- EXPERIENCIAS --}}

                                <div class="mc-c08__reserva-cuerpo">

                                    @if ($s->experiencias->first()?->imagen_url)

                                        <div class="mc-c08__reserva-foto">

                                            <img

                                                src="{{ $s->experiencias->first()->imagen_url }}"

                                                alt=""

                                                loading="lazy"

                                            >

                                        </div>

                                    @endif

                                    <div class="mc-c08__reserva-info">

                                        <span class="mc-c08__etiqueta">

                                            EXPERIENCIAS SELECCIONADAS

                                        </span>

                                        <h3>

                                            {{ $s->experiencias

                                                ->pluck('nombre')

                                                ->implode(' + ') }}

                                        </h3>

                                        <ul class="mc-c08__experiencias-lista">

                                            @foreach ($s->experiencias as $exp)

                                                <li>
                                            <span>{{ $exp->nombre }}</span>
                                            @php
                                                $fechaExperiencia = $exp->pivot->fecha_deseada
                                                    ?? $s->fecha_deseada;
                                            @endphp
                                            <small>
                                                @if ($fechaExperiencia)
                                                    {{ \Carbon\Carbon::parse($fechaExperiencia)->translatedFormat('j M Y') }}
                                                @else
                                                    Fecha por confirmar
                                                @endif
                                            </small>
                                        </li>

                                            @endforeach

                                        </ul>

                                    </div>

                                </div>

                                {{-- DATOS DE LA RESERVACIÓN --}}

                                <dl class="mc-c08__datos">

                                    <div>

                                        <dt>

                                            FECHAS SOLICITADAS

                                        </dt>

                                        <dd>

                                            Consulta las fechas de cada experiencia arriba.

                                        </dd>

                                    </div>

                                    <div>

                                        <dt>

                                            PARTICIPANTES

                                        </dt>

                                        <dd>

                                            {{ $s->cant_persona }}

                                            {{ $s->cant_persona == 1

                                                ? 'persona'

                                                : 'personas' }}

                                        </dd>

                                    </div>

                                    <div class="mc-c08__dato-total">

                                        <dt>

                                            TOTAL REGISTRADO

                                        </dt>

                                        <dd>

                                            ${{ number_format($s->total, 2) }}

                                            <small>MXN</small>

                                        </dd>

                                    </div>

                                </dl>

                                {{-- CALIFICAR LAS EXPERIENCIAS VIVIDAS (C16) --}}

                                @include('Calificaciones._invitacion', ['solicitud' => $s])

                                {{-- ACCIONES DE LA SOLICITUD --}}

                                <div class="mc-c08__reserva-acciones">

                                    <div class="mc-c08__reserva-mensaje">

                                        <span aria-hidden="true">ⓘ</span>

                                        <span>

                                            Consulta el estado de tu solicitud

                                            antes de realizar un pago.

                                        </span>

                                    </div>

                                    <div class="mc-c08__acciones">

                                        @if ($s->puedePagar())

                                            <a

                                                href="{{ route('pagos.create', $s) }}"

                                                class="mc-c08__boton mc-c08__boton--verde"

                                            >

                                                {{ $s->ultimoPago?->estado === 'rechazado'

                                                    ? 'Reenviar comprobante'

                                                    : 'Registrar pago' }}

                                                <span aria-hidden="true">↗</span>

                                            </a>

                                        @endif

                                        @if ($s->estado === 'pendiente')

                                            <form

                                                method="POST"

                                                action="{{ route('solicitudes.cancelar', $s) }}"

                                                onsubmit="return confirm('¿Seguro que deseas cancelar esta solicitud?')"

                                            >

                                                @csrf

                                                @method('PATCH')

                                                <button

                                                    type="submit"

                                                    class="mc-c08__cancelar"

                                                >

                                                    Cancelar solicitud

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>

                    {{-- PAGINACIÓN ORIGINAL --}}

                    <div class="mc-c08__paginacion">

                        {{ $solicitudes->links() }}

                    </div>

                @endif

            </section>

            @endif


            {{-- ==================================== --}}
            {{-- 04 · PAGOS --}}
            {{-- ==================================== --}}

            @if (request('seccion') !== 'consultorias')

            <section
                class="mc-c08__apartado"
                id="mis-pagos"
            >

                <div class="mc-c08__encabezado">

                    <div>

                        <span class="mc-c08__etiqueta">

                            <span class="mc-c08__linea"></span>

                            PAGOS DE MIS EXPERIENCIAS

                        </span>

                        <h2>

                            Mis

                            <em>pagos.</em>

                        </h2>

                        <p>

                            Revisa los pagos registrados para tus
                            reservaciones y su estado de aprobación.

                        </p>

                    </div>

                </div>

                @if ($pagos->isEmpty())

                    <div class="mc-c08__vacio">

                        <h3>
                            Aún no tienes pagos registrados.
                        </h3>

                        <p>
                            Cuando una reservación sea confirmada
                            podrás registrar tu pago desde su tarjeta.
                        </p>

                    </div>

                @else

                    <div class="mc-c08__pagos">

                        @foreach ($pagos as $pago)

                            <div class="mc-c08__pago">

                                <div>
                                    <span class="mc-c08__micro">FOLIO</span>
                                    <strong>{{ $pago->solicitud->folio }}</strong>
                                </div>

                                <div>
                                    <span class="mc-c08__micro">FECHA</span>
                                    <strong>{{ $pago->fecha?->format('d/m/Y') ?? 'Sin fecha' }}</strong>
                                </div>

                                <div>
                                    <span class="mc-c08__micro">REFERENCIA</span>
                                    <strong>{{ $pago->referencia ?: 'Sin referencia' }}</strong>
                                </div>

                                <div>
                                    <span class="mc-c08__micro">TOTAL DE LA RESERVACIÓN</span>
                                    <strong>${{ number_format((float) $pago->solicitud->total, 2) }} MXN</strong>
                                </div>

                                <x-estado :valor="$pago->estado" />

                            </div>

                        @endforeach

                    </div>

                @endif

            </section>

            @endif


            {{-- ==================================== --}}

            {{-- 05 · CONSULTORÍAS --}}

            {{-- ==================================== --}}

            @if (request('seccion') !== 'reservaciones')

            <section

                class="mc-c08__apartado"

                id="mis-consultorias"

            >

                <div class="mc-c08__encabezado">

                    <div>

                        <span class="mc-c08__etiqueta">

                            <span class="mc-c08__linea"></span>

                            ACOMPAÑAMIENTO Y CONSULTORÍA

                        </span>

                        <h2>

                            Mis

                            <em>consultorías.</em>

                        </h2>

                        <p>

                            Consulta las necesidades

                            registradas, el estado de atención

                            y los comentarios de seguimiento

                            de tus proyectos.

                        </p>

                    </div>

                    <span class="mc-c08__contador">

                        {{ $totalConsultorias }}

                        {{ $totalConsultorias === 1

                            ? 'consultoría'

                            : 'consultorías' }}

                    </span>

                </div>

                {{-- SIN CONSULTORÍAS --}}

                @if ($consultorias->isEmpty())

                    <div class="mc-c08__vacio">

                        <h3>

                            Aún no tienes

                            consultorías registradas.

                        </h3>

                        <p>

                            Cuando registres una solicitud

                            de consultoría podrás consultar

                            aquí su información

                            y seguimiento.

                        </p>

                        <a

                            href="{{ route('consultoria.index') }}"

                            class="mc-c08__boton mc-c08__boton--verde"

                        >

                            Conocer las consultorías

                            <span aria-hidden="true">↗</span>

                        </a>

                    </div>

                @else

                    <div class="mc-c08__consultorias">

                        @foreach ($consultorias as $c)

                            @php

                                $estadoConsultoria = match ($c->estado) {

                                    'recibida' => 'Recibida',

                                    'en_proceso' => 'En proceso',

                                    'atendida' => 'Atendida',

                                    'cerrada' => 'Cerrada',

                                    default => ucfirst(

                                        str_replace('_', ' ', $c->estado)

                                    ),

                                };

                                $comentarios = $seguimientos->get(

                                    $c->id_solicitud_consultoria,

                                    collect()

                                );

                            @endphp

                            <article class="mc-c08__consultoria">

                                {{-- CABECERA --}}

                                <div class="mc-c08__consultoria-top">

                                    <div>

                                        <span class="mc-c08__micro">

                                            EXPEDIENTE DE CONSULTORÍA

                                        </span>

                                        <strong class="mc-c08__consultoria-folio">

                                            CON-{{ str_pad(

                                                $c->id_solicitud_consultoria,

                                                5,

                                                '0',

                                                STR_PAD_LEFT

                                            ) }}

                                        </strong>

                                    </div>

                                    <span

                                        @class([

                                            'mc-c08__estado-consultoria',

                                            'mc-c08__estado-consultoria--recibida'

                                                => $c->estado === 'recibida',

                                            'mc-c08__estado-consultoria--proceso'

                                                => $c->estado === 'en_proceso',

                                            'mc-c08__estado-consultoria--atendida'

                                                => $c->estado === 'atendida',

                                            'mc-c08__estado-consultoria--cerrada'

                                                => $c->estado === 'cerrada',

                                        ])

                                    >

                                        {{ $estadoConsultoria }}

                                    </span>

                                </div>

                                {{-- ORGANIZACIÓN Y NECESIDAD --}}

                                <div class="mc-c08__consultoria-cuerpo">

                                    <span class="mc-c08__etiqueta">

                                        ORGANIZACIÓN SOLICITANTE

                                    </span>

                                    <h3>

                                        {{ $c->nombre_organizacion

                                           ?? 'Organización no disponible' }}

                                    </h3>

                                    <div class="mc-c08__consultoria-meta">

                                        <span>

                                            ⌖

                                            {{ $c->ubicacion }}

                                        </span>

                                        <span>

                                            ◷

                                            {{ \Carbon\Carbon::parse(

                                                $c->fecha_solicitud

                                            )->translatedFormat('d M Y') }}

                                        </span>

                                    </div>

                                    <div class="mc-c08__necesidad">

                                        <span class="mc-c08__micro">

                                            NECESIDAD REGISTRADA

                                        </span>

                                        <p>

                                            {{ $c->necesidad }}

                                        </p>

                                    </div>

                                </div>

                                {{-- HISTORIAL DESPLEGABLE --}}

                                <details

                                    class="mc-c08__seguimiento"

                                    @if ($comentarios->isNotEmpty()) open @endif

                                >

                                    <summary>

                                        <span class="mc-c08__seguimiento-texto">

                                            <strong>

                                                Seguimiento de la consultoría

                                            </strong>

                                            <small>

                                                {{ $comentarios->count() }}

                                                {{ $comentarios->count() === 1

                                                    ? 'comentario registrado'

                                                    : 'comentarios registrados' }}

                                            </small>

                                        </span>

                                        <span class="mc-c08__chevron"

                                              aria-hidden="true">

                                            ⌄

                                        </span>

                                    </summary>

                                    <div class="mc-c08__seguimiento-contenido">

                                        @if ($comentarios->isEmpty())

                                            <div class="mc-c08__sin-seguimiento">

                                                <span aria-hidden="true">◷</span>

                                                <p>

                                                    Aún no hay comentarios

                                                    de seguimiento

                                                    para esta consultoría.

                                                </p>

                                            </div>

                                        @else

                                            <ol class="mc-c08__timeline">

                                                @foreach (

                                                    $comentarios->sortBy('fecha')

                                                    as $seguimiento

                                                )

                                                    <li>

                                                        <div class="mc-c08__timeline-card">

                                                            <span class="mc-c08__micro">

                                                                ACTUALIZACIÓN DEL PROYECTO

                                                            </span>

                                                            <p>

                                                                {{ $seguimiento->comentario }}

                                                            </p>

                                                            <time

                                                                datetime="{{ \Carbon\Carbon::parse($seguimiento->fecha)->toIso8601String() }}"

                                                            >

                                                                {{ \Carbon\Carbon::parse(

                                                                    $seguimiento->fecha

                                                                )->translatedFormat('d M Y, H:i') }}

                                                            </time>

                                                        </div>

                                                    </li>

                                                @endforeach

                                            </ol>

                                        @endif

                                    </div>

                                </details>

                            </article>

                        @endforeach

                    </div>

                @endif

            </section>

            @endif

        </div>

    </section>

    {{-- ======================================== --}}

    {{-- 06 · INVITACIÓN FINAL --}}

    {{-- ======================================== --}}

    <section class="mc-c08__final">

        <div class="contenedor mc-c08__final-grid">

            <div>

                <span class="mc-c08__etiqueta

                             mc-c08__etiqueta--clara">

                    CONTINÚA DESCUBRIENDO

                </span>

                <h2>

                    Siempre hay algo

                    <em>nuevo por vivir.</em>

                </h2>

                <p>

                    Explora otras experiencias

                    y descubre nuevas formas

                    de conectar con Valle de Bravo.

                </p>

            </div>

            <a

                href="{{ route('experiencias.index') }}"

                class="mc-c08__boton mc-c08__boton--claro"

            >

                Explorar experiencias

                <span aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

</div>

@endsection
