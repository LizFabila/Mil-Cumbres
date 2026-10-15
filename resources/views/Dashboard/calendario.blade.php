
{{-- A05 · Calendario administrativo
     Tablas: solicitudes, asigna_experiencia,
     experiencias, personas
--}}

@extends('layouts.admin')

@section('titulo', 'Calendario')

@push('estilos')
    <link rel="stylesheet"
          href="{{ asset('css/admin-calendario-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    // Convertimos los días recibidos en una colección.
    $diasCalendario = collect($dias);

    // Estadísticas exclusivamente del mes consultado.
    $solicitudesMes = $diasCalendario
        ->filter(fn ($dia) => $dia->isSameMonth($mes))
        ->flatMap(fn ($dia) =>
            $solicitudes->get(
                $dia->format('Y-m-d'),
                collect()
            )
        );

    $totalMes = $solicitudesMes->count();

    $pendientesMes = $solicitudesMes
        ->where('estado', 'pendiente')
        ->count();

    $confirmadasMes = $solicitudesMes
        ->where('estado', 'confirmada')
        ->count();

    $completadasMes = $solicitudesMes
        ->where('estado', 'completada')
        ->count();
@endphp

<div class="mc-a05">

    {{-- ====================================== --}}
    {{-- 01 · ENCABEZADO --}}
    {{-- ====================================== --}}

    <header class="mc-a05__hero">

        <div class="mc-a05__hero-contenido">

            <a href="{{ route('admin.resumen') }}"
               class="mc-a05__regresar">
                ← Volver al resumen
            </a>

            <div class="mc-a05__hero-principal">

                <div>

                    <span class="mc-a05__eyebrow">
                        <span class="mc-a05__raya"></span>
                        ADMINISTRACIÓN / OPERACIÓN
                    </span>

                    <h1>
                        Calendario
                        <em>de solicitudes.</em>
                    </h1>

                    <p>
                        Consulta las fechas solicitadas,
                        los participantes y las experiencias
                        registradas. Accede a cada expediente
                        para revisar su información.
                    </p>

                </div>

                <div class="mc-a05__hero-sello"
                     aria-hidden="true">
                    <span>✳</span>
                    <strong>MIL CUMBRES</strong>
                    <small>GESTIÓN DE EXPERIENCIAS</small>
                </div>

            </div>

        </div>

        <div class="mc-a05__hero-pie">

            <span>
                ORGANIZACIÓN DE EXPERIENCIAS
            </span>

            <span>
                {{ mb_strtoupper($mes->translatedFormat('F Y')) }}
            </span>

        </div>

    </header>


    {{-- ====================================== --}}
    {{-- 02 · INDICADORES MENSUALES --}}
    {{-- ====================================== --}}

    <section class="mc-a05__indicadores"
             aria-label="Resumen de solicitudes del mes">

        <div class="mc-a05__indicador
                    mc-a05__indicador--total">

            <span class="mc-a05__indicador-etiqueta">
                TOTAL DEL MES
            </span>

            <strong>{{ $totalMes }}</strong>

            <span class="mc-a05__indicador-descripcion">
                Solicitudes registradas
            </span>

            <span class="mc-a05__indicador-icono"
                  aria-hidden="true">▦</span>

        </div>


        <div class="mc-a05__indicador
                    mc-a05__indicador--pendiente">

            <span class="mc-a05__indicador-etiqueta">
                PENDIENTES
            </span>

            <strong>{{ $pendientesMes }}</strong>

            <span class="mc-a05__indicador-descripcion">
                Pendientes de atención
            </span>

            <span class="mc-a05__indicador-icono"
                  aria-hidden="true">◷</span>

        </div>


        <div class="mc-a05__indicador
                    mc-a05__indicador--confirmada">

            <span class="mc-a05__indicador-etiqueta">
                CONFIRMADAS
            </span>

            <strong>{{ $confirmadasMes }}</strong>

            <span class="mc-a05__indicador-descripcion">
                Solicitudes confirmadas
            </span>

            <span class="mc-a05__indicador-icono"
                  aria-hidden="true">✓</span>

        </div>


        <div class="mc-a05__indicador
                    mc-a05__indicador--completada">

            <span class="mc-a05__indicador-etiqueta">
                COMPLETADAS
            </span>

            <strong>{{ $completadasMes }}</strong>

            <span class="mc-a05__indicador-descripcion">
                Experiencias completadas
            </span>

            <span class="mc-a05__indicador-icono"
                  aria-hidden="true">✳</span>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 03 · CABECERA DEL CALENDARIO --}}
    {{-- ====================================== --}}

    <section class="mc-a05__calendario-seccion">

        <div class="mc-a05__calendario-header">

            <div class="mc-a05__calendario-titulo">

                <span class="mc-a05__eyebrow">
                    01 / AGENDA MENSUAL
                </span>

                <h2>
                    {{ ucfirst($mes->translatedFormat('F')) }}
                    <em>{{ $mes->format('Y') }}</em>
                </h2>

                <p>
                    Solicitudes organizadas por fecha deseada.
                </p>

            </div>


            {{-- NAVEGACIÓN ORIGINAL ENTRE MESES --}}

            <nav class="mc-a05__controles"
                 aria-label="Navegación del calendario">

                <a
                    href="{{ route('admin.calendario', [
                        'mes' => $mes->copy()
                            ->subMonth()
                            ->format('Y-m')
                    ]) }}"
                    class="mc-a05__control
                           mc-a05__control--flecha"
                    aria-label="Mes anterior"
                    title="Mes anterior"
                >
                    ←
                </a>

                <a
                    href="{{ route('admin.calendario') }}"
                    class="mc-a05__control
                           mc-a05__control--actual"
                >
                    Mes actual
                </a>

                <a
                    href="{{ route('admin.calendario', [
                        'mes' => $mes->copy()
                            ->addMonth()
                            ->format('Y-m')
                    ]) }}"
                    class="mc-a05__control
                           mc-a05__control--flecha"
                    aria-label="Mes siguiente"
                    title="Mes siguiente"
                >
                    →
                </a>

            </nav>

        </div>


        {{-- LEYENDA DE ESTADOS --}}

        <div class="mc-a05__leyenda">

            <div class="mc-a05__leyenda-titulo">
                <span aria-hidden="true">✳</span>
                ESTADOS DE SOLICITUD
            </div>

            <div class="mc-a05__leyenda-estados">
                <x-estado valor="pendiente" />
                <x-estado valor="confirmada" />
                <x-estado valor="completada" />
            </div>

        </div>


        {{-- ====================================== --}}
        {{-- 04 · CALENDARIO PARA COMPUTADORAS --}}
        {{-- ====================================== --}}

        <div class="mc-a05__calendario-escritorio">

            <table class="mc-a05__tabla">

                <caption class="mc-a05__sr-only">
                    Solicitudes correspondientes a
                    {{ $mes->translatedFormat('F Y') }}
                </caption>

                <thead>
                    <tr>
                        @foreach ([
                            'Lun', 'Mar', 'Mié',
                            'Jue', 'Vie', 'Sáb', 'Dom'
                        ] as $nombreDia)

                            <th scope="col">
                                {{ $nombreDia }}
                            </th>

                        @endforeach
                    </tr>
                </thead>

                <tbody>

                    @foreach ($diasCalendario->chunk(7) as $semana)

                        <tr>

                            @foreach ($semana as $dia)

                                @php
                                    $fechaClave = $dia->format('Y-m-d');

                                    $delDia = $solicitudes->get(
                                        $fechaClave,
                                        collect()
                                    );
                                @endphp

                                <td
                                    @class([
                                        'mc-a05__celda',
                                        'mc-a05__celda--fuera' =>
                                            ! $dia->isSameMonth($mes),
                                        'mc-a05__celda--hoy' =>
                                            $dia->isToday(),
                                        'mc-a05__celda--con-eventos' =>
                                            $delDia->isNotEmpty(),
                                    ])
                                >

                                    <div class="mc-a05__dia-header">

                                        <time
                                            datetime="{{ $fechaClave }}"
                                            title="{{ $dia->translatedFormat('l j \d\e F \d\e Y') }}"
                                            class="mc-a05__dia-numero"
                                        >
                                            {{ $dia->day }}
                                        </time>

                                        @if ($dia->isToday())

                                            <span class="mc-a05__hoy">
                                                HOY
                                            </span>

                                        @endif

                                    </div>


                                    <div class="mc-a05__eventos">

                                        @foreach ($delDia as $s)

                                            <a
                                                href="{{ route(
                                                    'admin.solicitudes.show',
                                                    $s
                                                ) }}"
                                                class="mc-a05__evento mc-a05__evento--{{ $s->estado }}"
                                                title="{{ $s->folio }} · {{ $s->persona?->nombre_completo }}"
                                            >

                                                <span class="mc-a05__evento-folio">
                                                    {{ $s->folio }}
                                                </span>

                                                <span class="mc-a05__evento-nombre">
                                                    {{ $s->experiencias
                                                        ->pluck('nombre')
                                                        ->implode(' + ') }}
                                                </span>

                                                <span class="mc-a05__evento-personas">
                                                    <span aria-hidden="true">♙</span>

                                                    {{ $s->cant_persona }}
                                                    {{ $s->cant_persona == 1
                                                        ? 'persona'
                                                        : 'personas' }}
                                                </span>

                                            </a>

                                        @endforeach

                                    </div>

                                </td>

                            @endforeach

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ====================================== --}}
        {{-- 05 · AGENDA PARA CELULARES --}}
        {{-- ====================================== --}}

        <div class="mc-a05__agenda-movil">

            <div class="mc-a05__agenda-intro">

                <span class="mc-a05__eyebrow">
                    VISTA DE AGENDA
                </span>

                <p>
                    Solicitudes del mes agrupadas
                    por día.
                </p>

            </div>


            @if ($totalMes === 0)

                <div class="mc-a05__vacio">

                    <span aria-hidden="true">✳</span>

                    <strong>
                        Sin solicitudes este mes.
                    </strong>

                    <p>
                        Cuando existan solicitudes
                        aparecerán aquí ordenadas
                        por fecha deseada.
                    </p>

                </div>

            @else

                @foreach ($diasCalendario as $dia)

                    @if ($dia->isSameMonth($mes))

                        @php
                            $delDia = $solicitudes->get(
                                $dia->format('Y-m-d'),
                                collect()
                            );
                        @endphp

                        @if ($delDia->isNotEmpty())

                            <section class="mc-a05__agenda-dia">

                                <div class="mc-a05__agenda-fecha">

                                    <div class="mc-a05__agenda-numero">
                                        {{ $dia->format('d') }}
                                    </div>

                                    <div>

                                        <strong>
                                            {{ ucfirst(
                                                $dia->translatedFormat('l')
                                            ) }}
                                        </strong>

                                        <span>
                                            {{ $dia->translatedFormat('F Y') }}
                                        </span>

                                    </div>

                                    <span class="mc-a05__agenda-cantidad">
                                        {{ $delDia->count() }}
                                    </span>

                                </div>


                                <div class="mc-a05__agenda-eventos">

                                    @foreach ($delDia as $s)

                                        <a
                                            href="{{ route(
                                                'admin.solicitudes.show',
                                                $s
                                            ) }}"
                                            class="mc-a05__agenda-evento mc-a05__evento--{{ $s->estado }}"
                                        >

                                            <div class="mc-a05__agenda-evento-top">

                                                <strong>
                                                    {{ $s->folio }}
                                                </strong>

                                                <x-estado
                                                    :valor="$s->estado"
                                                />

                                            </div>

                                            <span class="mc-a05__agenda-experiencia">
                                                {{ $s->experiencias
                                                    ->pluck('nombre')
                                                    ->implode(' + ') }}
                                            </span>

                                            <div class="mc-a05__agenda-meta">

                                                <span>
                                                    {{ $s->cant_persona }}
                                                    {{ $s->cant_persona == 1
                                                        ? 'persona'
                                                        : 'personas' }}
                                                </span>

                                                @if ($s->persona?->nombre_completo)

                                                    <span>
                                                        {{ $s->persona->nombre_completo }}
                                                    </span>

                                                @endif

                                            </div>

                                            <span class="mc-a05__agenda-abrir">
                                                Abrir solicitud
                                                <span aria-hidden="true">↗</span>
                                            </span>

                                        </a>

                                    @endforeach

                                </div>

                            </section>

                        @endif

                    @endif

                @endforeach

            @endif

        </div>


        {{-- ====================================== --}}
        {{-- 06 · PIE DEL CALENDARIO --}}
        {{-- ====================================== --}}

        <div class="mc-a05__calendario-footer">

            <div>

                <span class="mc-a05__footer-icono"
                      aria-hidden="true">
                    ⓘ
                </span>

                <p>
                    Las fechas mostradas corresponden
                    a las fechas deseadas de las solicitudes.
                    No representan por sí solas
                    disponibilidad ni cupos confirmados.
                </p>

            </div>

            <a href="{{ route('admin.solicitudes.index') }}"
               class="mc-a05__ver-todas">

                Gestionar solicitudes
                <span aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

    {{-- ====================================== --}}
    {{-- 07 · CIERRE --}}
    {{-- ====================================== --}}

    <footer class="mc-a05__cierre">

        <span>
            ✳ MIL CUMBRES
        </span>

        <span>
            CALENDARIO ADMINISTRATIVO
        </span>

    </footer>

</div>

@endsection
