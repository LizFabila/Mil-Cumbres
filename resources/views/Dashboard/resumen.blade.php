
{{-- A08 · Resumen administrativo
     Tablas: solicitudes, pagos, solicitud_consultorias,
     asigna_experiencia, experiencias
--}}

@extends('layouts.admin')

@section('titulo', 'Resumen')

@push('estilos')
    <link rel="stylesheet"
          href="{{ asset('css/admin-resumen-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    // Se utilizan los datos que ya proporciona el controlador.
    $maxTop = max(1, (int) $top->max('solicitudes'));

    $estados = \App\Models\Solicitud::ESTADOS;

    $totalEstados = collect($estados)->sum(
        fn ($estado) => (int) ($porEstado[$estado] ?? 0)
    );
@endphp

<div class="mc-a08">

    {{-- ========================================= --}}
    {{-- 01 · ENCABEZADO DEL DASHBOARD --}}
    {{-- ========================================= --}}

    <header class="mc-a08__hero">

        <div class="mc-a08__hero-principal">

            <div class="mc-a08__hero-texto">

                <span class="mc-a08__eyebrow
                             mc-a08__eyebrow--claro">
                    <span class="mc-a08__linea"></span>
                    PANEL ADMINISTRATIVO / MIL CUMBRES
                </span>

                <h1>
                    Centro de
                    <em>operaciones.</em>
                </h1>

                <p>
                    Consulta las solicitudes, pagos
                    y consultorías que requieren atención,
                    además de las próximas experiencias.
                </p>

                <div class="mc-a08__fecha">

                    <span aria-hidden="true">◷</span>

                    {{ ucfirst(
                        now()->translatedFormat(
                            'l j \d\e F \d\e Y'
                        )
                    ) }}

                </div>

            </div>

            <div class="mc-a08__hero-ornamento"
                 aria-hidden="true">


                <strong>
                    MIL CUMBRES
                </strong>

                <small>
                    TURISMO REGENERATIVO
                </small>

            </div>

        </div>

        <div class="mc-a08__hero-footer">

            <span>
                GESTIÓN Y SEGUIMIENTO
            </span>

            <span>
                RESUMEN GENERAL
            </span>

        </div>

    </header>


    {{-- ========================================= --}}
    {{-- 02 · INDICADORES PRINCIPALES --}}
    {{-- ========================================= --}}

    <section class="mc-a08__seccion"
             aria-labelledby="mc-a08-kpis-titulo">

        <div class="mc-a08__seccion-header">

            <div>

                <span class="mc-a08__eyebrow">
                    <span class="mc-a08__linea"></span>
                    01 / INDICADORES
                </span>

                <h2 id="mc-a08-kpis-titulo">
                    Estado de la
                    <em>operación.</em>
                </h2>

                <p>
                    Información de solicitudes,
                    pagos y consultorías registrada
                    en el sistema.
                </p>

            </div>

            <span class="mc-a08__seccion-referencia">
                PANEL DE CONTROL
            </span>

        </div>


        <div class="mc-a08__kpis">

            {{-- SOLICITUDES PENDIENTES --}}

            <article class="mc-a08__kpi mc-a08__kpi--pendientes">

                <div class="mc-a08__kpi-superior">

                    <span>
                        SOLICITUDES PENDIENTES
                    </span>

                    <span class="mc-a08__kpi-icono"
                          aria-hidden="true">◷</span>

                </div>

                <strong class="mc-a08__kpi-valor">
                    {{ $kpis['pendientes'] }}
                </strong>

                <p>
                    Solicitudes que requieren revisión.
                </p>

                <a href="{{ route('admin.solicitudes.index', [
                    'estado' => 'pendiente'
                ]) }}">
                    Revisar solicitudes
                    <span aria-hidden="true">↗</span>
                </a>

            </article>


            {{-- PAGOS POR REVISAR --}}

            <article class="mc-a08__kpi mc-a08__kpi--pagos">

                <div class="mc-a08__kpi-superior">

                    <span>
                        PAGOS POR REVISAR
                    </span>

                    <span class="mc-a08__kpi-icono"
                          aria-hidden="true">▤</span>

                </div>

                <strong class="mc-a08__kpi-valor">
                    {{ $kpis['pagos_por_revisar'] }}
                </strong>

                <p>
                    Comprobantes que necesitan validación.
                </p>

                <a href="{{ route('admin.pagos.index') }}">
                    Revisar pagos
                    <span aria-hidden="true">↗</span>
                </a>

            </article>


            {{-- CONSULTORÍAS ABIERTAS --}}

            <article class="mc-a08__kpi mc-a08__kpi--consultorias">

                <div class="mc-a08__kpi-superior">

                    <span>
                        CONSULTORÍAS ABIERTAS
                    </span>


                </div>

                <strong class="mc-a08__kpi-valor">
                    {{ $kpis['consultorias_abiertas'] }}
                </strong>

                <p>
                    Solicitudes de acompañamiento abiertas.
                </p>

                <a href="{{ route('admin.consultorias.index') }}">
                    Ver consultorías
                    <span aria-hidden="true">↗</span>
                </a>

            </article>


            {{-- CONFIRMADAS DEL MES --}}

            <article class="mc-a08__kpi mc-a08__kpi--confirmadas">

                <div class="mc-a08__kpi-superior">

                    <span>
                        CONFIRMADAS ESTE MES
                    </span>

                    <span class="mc-a08__kpi-icono"
                          aria-hidden="true">✓</span>

                </div>

                <strong class="mc-a08__kpi-valor">
                    {{ $kpis['confirmadas_mes'] }}
                </strong>

                <p>
                    Solicitudes confirmadas durante el mes.
                </p>

                <span class="mc-a08__kpi-informativo">
                    REGISTRO MENSUAL
                </span>

            </article>


            {{-- INGRESOS APROBADOS --}}

            <article class="mc-a08__kpi mc-a08__kpi--ingresos">

                <div class="mc-a08__kpi-superior">

                    <span>
                        INGRESOS APROBADOS DEL MES
                    </span>

                    <span class="mc-a08__kpi-icono"
                          aria-hidden="true">$</span>

                </div>

                <strong class="mc-a08__kpi-valor
                               mc-a08__kpi-valor--moneda">

                    ${{ number_format($kpis['ingresos_mes'], 0) }}

                    <small>MXN</small>

                </strong>

                <p>
                    Importe de pagos aprobados
                    durante el mes.
                </p>

                <span class="mc-a08__kpi-informativo">
                    TOTAL MENSUAL
                </span>

            </article>


            {{-- VISITANTES PRÓXIMOS --}}

            <article class="mc-a08__kpi mc-a08__kpi--visitantes">

                <div class="mc-a08__kpi-superior">

                    <span>
                        VISITANTES PRÓXIMOS 30 DÍAS
                    </span>

                    <span class="mc-a08__kpi-icono"
                          aria-hidden="true">♧</span>

                </div>

                <strong class="mc-a08__kpi-valor">
                    {{ $kpis['personas_proximas'] }}
                </strong>

                <p>
                    Participantes contemplados
                    para los próximos 30 días.
                </p>

                <a href="{{ route('admin.calendario') }}">
                    Abrir calendario
                    <span aria-hidden="true">↗</span>
                </a>

            </article>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 03 · VISITAS Y EXPERIENCIAS --}}
    {{-- ========================================= --}}

    <section class="mc-a08__analisis"
             aria-label="Visitas y experiencias">

        {{-- =============================== --}}
        {{-- PRÓXIMAS VISITAS --}}
        {{-- =============================== --}}

        <div class="mc-a08__panel"
             id="mc-a08-proximas">

            <div class="mc-a08__panel-header">

                <div>

                    <span class="mc-a08__eyebrow">
                        02 / PRÓXIMAS ACTIVIDADES
                    </span>

                    <h2>
                        Visitas
                        <em>confirmadas.</em>
                    </h2>

                    <p>
                        Experiencias próximas que ya
                        cuentan con confirmación.
                    </p>

                </div>

                <a href="{{ route('admin.calendario') }}"
                   class="mc-a08__panel-enlace"
                   title="Abrir calendario">

                    <span aria-hidden="true">↗</span>

                </a>

            </div>


            <div class="mc-a08__visitas">

                @forelse ($proximas as $s)

                    <article class="mc-a08__visita">

                        {{-- FECHA --}}

                        <div class="mc-a08__visita-fecha">

                            <strong>
                                {{ $s->fecha_deseada->format('d') }}
                            </strong>

                            <span>
                                {{ mb_strtoupper(
                                    $s->fecha_deseada
                                        ->translatedFormat('M')
                                ) }}
                            </span>

                        </div>


                        {{-- INFORMACIÓN --}}

                        <div class="mc-a08__visita-info">

                            <span class="mc-a08__visita-etiqueta">
                                EXPERIENCIA CONFIRMADA
                            </span>

                            <h3>
                                {{ $s->experiencias
                                    ->pluck('nombre')
                                    ->implode(' + ') }}
                            </h3>

                            <div class="mc-a08__visita-meta">

                                <span>
                                    {{ $s->persona?->nombre_completo
                                        ?? 'Sin nombre disponible' }}
                                </span>

                                <span aria-hidden="true">·</span>

                                <span>
                                    {{ $s->cant_persona }}

                                    {{ $s->cant_persona == 1
                                        ? 'persona'
                                        : 'personas' }}
                                </span>

                            </div>

                        </div>


                        {{-- VER SOLICITUD --}}

                        <a
                            href="{{ route('admin.solicitudes.show', $s) }}"
                            class="mc-a08__visita-accion"
                        >
                            Ver
                            <span aria-hidden="true">↗</span>
                        </a>

                    </article>

                @empty

                    <div class="mc-a08__vacio">


                        <strong>
                            Sin visitas confirmadas por ahora.
                        </strong>

                        <p>
                            Cuando existan visitas próximas
                            aparecerán aquí.
                        </p>

                    </div>

                @endforelse

            </div>


            <div class="mc-a08__panel-footer">

                <span>
                    AGENDA DE EXPERIENCIAS
                </span>

                <a href="{{ route('admin.calendario') }}">
                    Ver calendario completo
                    <span aria-hidden="true">→</span>
                </a>

            </div>

        </div>


        {{-- =============================== --}}
        {{-- EXPERIENCIAS MÁS SOLICITADAS --}}
        {{-- =============================== --}}

        <div class="mc-a08__panel"
             id="mc-a08-top">

            <div class="mc-a08__panel-header">

                <div>

                    <span class="mc-a08__eyebrow">
                        03 / EXPERIENCIAS
                    </span>

                    <h2>
                        Las más
                        <em>solicitadas.</em>
                    </h2>

                    <p>
                        Comparación de experiencias
                        según sus registros de solicitudes.
                    </p>

                </div>

                <span class="mc-a08__panel-decoracion"
                      aria-hidden="true">▥</span>

            </div>


            <div class="mc-a08__ranking">

                @forelse ($top as $exp)

                    @php
                        $cantidad = (int) $exp->solicitudes;

                        $porcentaje = max(
                            0,
                            min(
                                100,
                                round(
                                    $cantidad / $maxTop * 100
                                )
                            )
                        );
                    @endphp

                    <div class="mc-a08__ranking-item">

                        <div class="mc-a08__ranking-superior">

                            <span class="mc-a08__ranking-numero">
                                {{ sprintf('%02d', $loop->iteration) }}
                            </span>

                            <span class="mc-a08__ranking-nombre">
                                {{ $exp->nombre }}
                            </span>

                            <strong>
                                {{ $cantidad }}
                            </strong>

                        </div>

                        <div
                            class="mc-a08__barra"
                            role="progressbar"
                            aria-label="Solicitudes de {{ $exp->nombre }} respecto a la experiencia más solicitada"
                            aria-valuenow="{{ $porcentaje }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >

                            <span style="width: {{ $porcentaje }}%"></span>

                        </div>

                    </div>

                @empty

                    <div class="mc-a08__vacio">


                        <strong>
                            Todavía no hay solicitudes.
                        </strong>

                        <p>
                            El gráfico se mostrará
                            cuando haya experiencias
                            con solicitudes registradas.
                        </p>

                    </div>

                @endforelse

            </div>


            <div class="mc-a08__panel-footer">

                <span>
                    SOLICITUDES POR EXPERIENCIA
                </span>

                <a href="{{ route('admin.experiencias.index') }}">
                    Administrar catálogo
                    <span aria-hidden="true">→</span>
                </a>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 04 · SOLICITUDES POR ESTADO --}}
    {{-- ========================================= --}}

    <section class="mc-a08__estados">

        <div class="mc-a08__estados-intro">

            <div>

                <span class="mc-a08__eyebrow">
                    <span class="mc-a08__linea"></span>
                    04 / SEGUIMIENTO
                </span>

                <h2>
                    Distribución de
                    <em>solicitudes.</em>
                </h2>

                <p>
                    Consulta cuántas solicitudes
                    se encuentran en cada estado.
                </p>

            </div>

            <div class="mc-a08__estados-total">

                <strong>
                    {{ $totalEstados }}
                </strong>

                <span>
                    TOTAL EN LA DISTRIBUCIÓN
                </span>

            </div>

        </div>


        <div class="mc-a08__estados-grid">

            @foreach ($estados as $estado)

                @php
                    $cantidadEstado = (int) (
                        $porEstado[$estado] ?? 0
                    );

                    $porcentajeEstado = $totalEstados > 0
                        ? round(
                            $cantidadEstado / $totalEstados * 100
                        )
                        : 0;
                @endphp

                <div class="mc-a08__estado">

                    <div class="mc-a08__estado-superior">

                        <x-estado :valor="$estado" />

                        <strong>
                            {{ $cantidadEstado }}
                        </strong>

                    </div>

                    <div
                        class="mc-a08__estado-barra"
                        role="progressbar"
                        aria-label="Porcentaje de solicitudes en estado {{ str_replace('_', ' ', $estado) }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        aria-valuenow="{{ $porcentajeEstado }}"
                    >

                        <span
                            style="width: {{ $porcentajeEstado }}%"
                        ></span>

                    </div>

                    <small>
                        {{ $porcentajeEstado }}% del total
                    </small>

                </div>

            @endforeach

        </div>

        <div class="mc-a08__estados-footer">

            <span>
                Los valores corresponden a los datos
                calculados por el sistema.
            </span>

            <a href="{{ route('admin.solicitudes.index') }}">
                Gestionar solicitudes
                <span aria-hidden="true">↗</span>
            </a>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 05 · ACCESOS RÁPIDOS --}}
    {{-- ========================================= --}}

    <section class="mc-a08__accesos">

        <div class="mc-a08__accesos-header">

            <span class="mc-a08__eyebrow">
                05 / NAVEGACIÓN
            </span>

            <h2>
                Accesos
                <em>rápidos.</em>
            </h2>

        </div>

        <div class="mc-a08__accesos-grid">

            <a href="{{ route('admin.solicitudes.index') }}">

                <span class="mc-a08__acceso-icono"
                      aria-hidden="true">▤</span>

                <span>
                    <strong>
                        Administrar solicitudes
                    </strong>

                    <small>
                        Reservaciones y seguimiento
                    </small>
                </span>

                <span aria-hidden="true">↗</span>

            </a>


            <a href="{{ route('admin.pagos.index') }}">

                <span class="mc-a08__acceso-icono"
                      aria-hidden="true">$</span>

                <span>
                    <strong>
                        Revisar pagos
                    </strong>

                    <small>
                        Comprobantes registrados
                    </small>
                </span>

                <span aria-hidden="true">↗</span>

            </a>


            <a href="{{ route('admin.consultorias.index') }}">


                <span>
                    <strong>
                        Gestionar consultorías
                    </strong>

                    <small>
                        Solicitudes de acompañamiento
                    </small>
                </span>

                <span aria-hidden="true">↗</span>

            </a>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 06 · PIE DEL RESUMEN --}}
    {{-- ========================================= --}}

    <footer class="mc-a08__footer">

        <span>
            MIL CUMBRES
        </span>

        <span>
            PANEL DE ADMINISTRACIÓN
        </span>

    </footer>

</div>

@endsection
