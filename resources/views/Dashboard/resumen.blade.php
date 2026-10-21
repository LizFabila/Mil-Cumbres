{{-- A08 · Resumen general
     Tablas: solicitudes, pagos, solicitud_consultorias,
     asigna_experiencia, experiencias
--}}

@extends('layouts.admin')

@section('titulo', 'Resumen')

@section('sin-contexto', '1')

@push('estilos')
    <link rel="stylesheet"
          href="{{ asset('css/admin-resumen-milcumbres.css') }}">
    <link rel="stylesheet"
          href="{{ asset('css/admin-resumen-graficas.css') }}">
@endpush

@section('contenido')

@php
    $maxTop = max(1, (int) $top->max('solicitudes'));
@endphp

<div class="mc-a08 mc-res">

    {{-- ========================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================= --}}

    <header class="mc-res__encabezado">

        <div>

            <span class="mc-a08__eyebrow">
                <span class="mc-a08__linea"></span>
                PANEL ADMINISTRATIVO / MIL CUMBRES
            </span>

            <h1>
                Resumen
                <em>general.</em>
            </h1>

        </div>

        <div class="mc-res__fecha">
            {{ ucfirst(now()->translatedFormat('l j \d\e F \d\e Y')) }}
        </div>

    </header>


    {{-- ========================================= --}}
    {{-- 01 · SEMÁFOROS --}}
    {{-- ========================================= --}}

    <section class="mc-res__bloque"
             aria-labelledby="mc-res-atencion">

        <div class="mc-res__bloque-encabezado">

            <h2 id="mc-res-atencion">
                Lo que requiere atención
            </h2>

        </div>

        <div class="mc-res__semaforos">

            @foreach ($semaforos as $tarjeta)

                <article class="mc-res__semaforo mc-res__semaforo--{{ $tarjeta['tono'] }}">

                    <div class="mc-res__semaforo-superior">

                        <span>{{ $tarjeta['titulo'] }}</span>

                        <span class="mc-res__etiqueta">
                            {{ $tarjeta['etiqueta'] }}
                        </span>

                    </div>

                    <strong class="mc-res__semaforo-valor">
                        {{ $tarjeta['valor'] }}
                    </strong>

                    <p>{{ $tarjeta['ayuda'] }}</p>

                    @isset($tarjeta['escala'])

                        @php $escala = $tarjeta['escala']; @endphp

                        {{-- Gráfica de bala: valor actual contra los cortes del semáforo --}}
                        <div class="mc-res__bala"
                             style="--corte-amarillo: {{ $escala['corte_amarillo'] }}%; --corte-verde: {{ $escala['corte_verde'] }}%"
                             role="img"
                             aria-label="Ritmo {{ $escala['valor'] !== null ? $escala['valor'].'%' : 'sin referencia' }}. Verde desde {{ $escala['verde'] }}%, amarillo desde {{ $escala['amarillo'] }}%.">

                            @if ($escala['marcador'] !== null)
                                <span class="mc-res__bala-marca"
                                      style="left: {{ $escala['marcador'] }}%"></span>
                            @endif

                        </div>

                    @endisset

                    <a href="{{ $tarjeta['ruta'] }}">
                        Revisar
                        <span aria-hidden="true">↗</span>
                    </a>

                </article>

            @endforeach

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 02 · TENDENCIA DE INGRESOS (LÍNEA) --}}
    {{-- ========================================= --}}

    <section class="mc-res__bloque"
             aria-labelledby="mc-res-ingresos">

        <div class="mc-res__bloque-encabezado">

            <h2 id="mc-res-ingresos">
                Ingresos aprobados
                <small>últimos 6 meses · MXN</small>
            </h2>

        </div>

        <div class="mc-res__linea-area">

            <div class="mc-res__eje-y" aria-hidden="true">
                @foreach ($ejeIngresos as $tick)
                    <span style="top: {{ $tick['posicion'] }}%">{{ $tick['etiqueta'] }}</span>
                @endforeach
            </div>

            <div class="mc-res__lienzo-linea">

                @foreach ($ejeIngresos as $tick)
                    <span class="mc-res__rejilla"
                          style="top: {{ $tick['posicion'] }}%"
                          aria-hidden="true"></span>
                @endforeach

                <svg class="mc-res__linea"
                     viewBox="{{ $lineaViewBox }}"
                     role="img"
                     aria-labelledby="mc-res-linea-desc">

                    <desc id="mc-res-linea-desc">
                        Ingresos aprobados por mes en los últimos seis meses.
                    </desc>

                    <polyline class="mc-res__linea-trazo"
                              points="{{ $trazoIngresos }}" />

                    @foreach ($ingresosPorMes as $mes)

                        <circle @class(['mc-res__linea-punto', 'is-actual' => $mes['en_curso']])
                                cx="{{ $mes['x'] }}"
                                cy="{{ $mes['y'] }}"
                                r="{{ $mes['en_curso'] ? 6 : 4 }}">
                            <title>{{ $mes['etiqueta'] }}: ${{ number_format($mes['valor'], 0) }}</title>
                        </circle>

                    @endforeach

                </svg>

            </div>

        </div>

        <ol class="mc-res__meses">

            @foreach ($ingresosPorMes as $mes)

                <li @class(['is-actual' => $mes['en_curso']])>

                    <span>
                        {{ $mes['etiqueta'] }}
                        @if ($mes['en_curso'])
                            <small>(en curso)</small>
                        @endif
                    </span>

                </li>

            @endforeach

        </ol>

    </section>


    {{-- ========================================= --}}
    {{-- 03 · ESTADO DEL TRABAJO (PASTEL 3D) --}}
    {{-- ========================================= --}}

    <section class="mc-res__bloque"
             aria-labelledby="mc-res-flujo">

        <div class="mc-res__bloque-encabezado">

            <h2 id="mc-res-flujo">
                Estado del trabajo
                <small title="Usa la fecha de última modificación del registro, no la de confirmación">
                    Confirmadas este mes: {{ $kpis['confirmadas_mes'] }}
                </small>
            </h2>

        </div>

        <div class="mc-res__grupos">

            @foreach ($flujo as $grupo => $datos)

                <div class="mc-res__grupo">

                    <h3>
                        {{ $grupo }}
                        <small>{{ $datos['total'] }} en total</small>
                    </h3>

                    @if ($datos['total'] === 0)

                        <p class="mc-res__vacio-grafica">Sin registros.</p>

                    @else

                        <div class="mc-res__pastel-escena" aria-hidden="true">

                            <svg class="mc-res__pastel" viewBox="0 0 200 212">

                                {{-- Espesor del pastel: el disco oscuro queda debajo --}}
                                <circle class="mc-res__pared" cx="100" cy="112" r="90" />

                                @foreach ($datos['porciones'] as $p)

                                    @if ($p['d'])

                                        <g class="mc-res__porcion"
                                           style="--dx: {{ $p['dx'] }}px; --dy: {{ $p['dy'] }}px">

                                            <path d="{{ $p['d'] }}"
                                                  fill="{{ $p['color'] }}">
                                                <title>{{ ucfirst(str_replace('_', ' ', $p['estado'])) }}: {{ $p['total'] }} ({{ $p['porcentaje'] }}%)</title>
                                            </path>

                                            @if ($p['etiqueta'])
                                                <text class="mc-res__porcion-etiqueta"
                                                      x="{{ $p['lx'] }}"
                                                      y="{{ $p['ly'] }}"
                                                      fill="{{ $p['texto'] }}">{{ $p['etiqueta'] }}</text>
                                            @endif

                                        </g>

                                    @endif

                                @endforeach

                            </svg>

                        </div>

                    @endif

                    <ul class="mc-res__leyenda">

                        @foreach ($datos['porciones'] as $item)

                            <li>

                                <span class="mc-res__swatch"
                                      style="background: {{ $item['color'] }}"
                                      aria-hidden="true"></span>

                                <span class="mc-res__fila-nombre">
                                    {{ ucfirst(str_replace('_', ' ', $item['estado'])) }}
                                </span>

                                <span class="mc-res__fila-cifra">
                                    <strong>{{ $item['total'] }}</strong>
                                    · {{ $item['porcentaje'] }}%
                                </span>

                            </li>

                        @endforeach

                    </ul>

                </div>

            @endforeach

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- 04 · VISITANTES PRÓXIMOS (COLUMNAS) --}}
    {{-- ========================================= --}}

    <section class="mc-res__bloque"
             aria-labelledby="mc-res-visitantes">

        <div class="mc-res__bloque-encabezado">

            <h2 id="mc-res-visitantes">
                Visitantes en los próximos 30 días
                <small>{{ $kpis['personas_proximas'] }} {{ $kpis['personas_proximas'] == 1 ? 'persona' : 'personas' }}</small>
            </h2>

        </div>

        <ol class="mc-res__columnas">

            @foreach ($visitantesPorPeriodo as $periodo)

                <li class="mc-res__columna">

                    <span class="mc-res__columna-valor">
                        {{ $periodo['valor'] }}
                    </span>

                    <div class="mc-res__columna-barra">
                        <span style="height: {{ $periodo['ancho'] }}%"></span>
                    </div>

                    <span class="mc-res__columna-etiqueta">
                        {{ $periodo['etiqueta'] }}
                    </span>

                </li>

            @endforeach

        </ol>

    </section>


    {{-- ========================================= --}}
    {{-- 05 · VISITAS Y EXPERIENCIAS --}}
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
                        01 / PRÓXIMAS ACTIVIDADES
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
        {{-- EXPERIENCIAS MÁS SOLICITADAS (COLUMNAS VERTICALES) --}}
        {{-- =============================== --}}

        <div class="mc-a08__panel"
             id="mc-a08-top">

            <div class="mc-a08__panel-header">

                <div>

                    <span class="mc-a08__eyebrow">
                        02 / EXPERIENCIAS
                    </span>

                    <h2>
                        Las más
                        <em>solicitadas.</em>
                    </h2>

                </div>

                <span class="mc-a08__panel-decoracion"
                      aria-hidden="true">▥</span>

            </div>


            <div class="mc-a08__ranking mc-res__ranking">

                @forelse ($top as $exp)

                    @php
                        $cantidad = (int) $exp->solicitudes;

                        $altura = max(
                            0,
                            min(
                                100,
                                round(
                                    $cantidad / $maxTop * 100
                                )
                            )
                        );
                    @endphp

                    <div @class([
                        'mc-res__columna',
                        'mc-res__columna-exp',
                        'is-primero' => $loop->first,
                    ])>

                        <div class="mc-res__columna-barra"
                             role="img"
                             aria-label="{{ $exp->nombre }}: {{ $cantidad }} solicitudes">

                            <span style="height: {{ $altura }}%"></span>

                        </div>

                        <span class="mc-res__columna-etiqueta">
                            {{ $exp->nombre }}
                        </span>

                    </div>

                @empty

                    <div class="mc-a08__vacio mc-res__ranking-vacio">


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
    {{-- 06 · ACCESOS RÁPIDOS --}}
    {{-- ========================================= --}}

    <section class="mc-a08__accesos">

        <div class="mc-a08__accesos-header">

            <span class="mc-a08__eyebrow">
                03 / NAVEGACIÓN
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
    {{-- 07 · PIE DEL RESUMEN --}}
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
