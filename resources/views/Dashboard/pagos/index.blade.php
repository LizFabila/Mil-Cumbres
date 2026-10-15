
{{-- A03 · Gestión de pagos
     Tablas: pagos, solicitudes, personas
--}}

@extends('layouts.admin')

@section('titulo', 'Pagos')

@push('estilos')
    <link rel="stylesheet"
          href="{{ asset('css/admin-pagos-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    $estadosPago = \App\Models\Pago::ESTADOS;

    $totalPagos = collect($estadosPago)->sum(
        fn ($e) => (int) ($conteos[$e] ?? 0)
    );
@endphp

<div class="mc-a03">

    {{-- ====================================== --}}
    {{-- 01 · ENCABEZADO --}}
    {{-- ====================================== --}}

    <header class="mc-a03__cabecera">

        <div class="mc-a03__cabecera-principal">

            <div>
                <span class="mc-a03__eyebrow">
                    <span aria-hidden="true">✳</span>
                    ADMINISTRACIÓN / CONTROL FINANCIERO
                </span>

                <h1>
                    Gestión de
                    <em>pagos.</em>
                </h1>

                <p>
                    Consulta los comprobantes registrados
                    y verifica cada transferencia
                    contra el estado de cuenta
                    antes de aprobarla.
                </p>
            </div>

            <div class="mc-a03__cabecera-marca"
                 aria-hidden="true">
                <span>03</span>
                <small>PAGOS · MIL CUMBRES</small>
            </div>

        </div>

        <div class="mc-a03__cabecera-pie">

            <span>
                REVISIÓN Y VALIDACIÓN DE COMPROBANTES
            </span>

            <a href="{{ route('admin.resumen') }}">
                <span aria-hidden="true">←</span>
                Volver al resumen
            </a>

        </div>

    </header>


    {{-- ====================================== --}}
    {{-- 02 · INDICADORES --}}
    {{-- ====================================== --}}

    <section class="mc-a03__indicadores"
             aria-label="Indicadores de pagos">

        <div class="mc-a03__indicador
                    mc-a03__indicador--total">

            <span class="mc-a03__indicador-etiqueta">
                PAGOS REGISTRADOS
            </span>

            <strong>
                {{ $totalPagos }}
            </strong>

            <span class="mc-a03__indicador-descripcion">
                Total de registros
            </span>

            <span class="mc-a03__indicador-icono"
                  aria-hidden="true">
                ▤
            </span>

        </div>


        <div class="mc-a03__indicador
                    mc-a03__indicador--pendientes">

            <span class="mc-a03__indicador-etiqueta">
                POR REVISAR
            </span>

            <strong>
                {{ $conteos['pendiente'] ?? 0 }}
            </strong>

            <span class="mc-a03__indicador-descripcion">
                Pendientes de validación
            </span>

            <span class="mc-a03__indicador-icono"
                  aria-hidden="true">
                ◷
            </span>

        </div>


        <div class="mc-a03__indicador
                    mc-a03__indicador--aprobados">

            <span class="mc-a03__indicador-etiqueta">
                APROBADOS
            </span>

            <strong>
                {{ $conteos['aprobado'] ?? 0 }}
            </strong>

            <span class="mc-a03__indicador-descripcion">
                Comprobantes validados
            </span>

            <span class="mc-a03__indicador-icono"
                  aria-hidden="true">
                ✓
            </span>

        </div>


        <div class="mc-a03__indicador
                    mc-a03__indicador--rechazados">

            <span class="mc-a03__indicador-etiqueta">
                RECHAZADOS
            </span>

            <strong>
                {{ $conteos['rechazado'] ?? 0 }}
            </strong>

            <span class="mc-a03__indicador-descripcion">
                Comprobantes rechazados
            </span>

            <span class="mc-a03__indicador-icono"
                  aria-hidden="true">
                ×
            </span>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 03 · INFORMACIÓN Y FILTROS --}}
    {{-- ====================================== --}}

    <section class="mc-a03__contenido">

        <div class="mc-a03__seccion-titulo">

            <div>

                <span class="mc-a03__seccion-eyebrow">
                    <span class="mc-a03__raya"></span>
                    01 / REGISTROS DE PAGO
                </span>

                <h2>
                    Revisión de
                    <em>comprobantes.</em>
                </h2>

                <p>
                    Selecciona un estado para consultar
                    sus pagos y realizar las acciones
                    correspondientes.
                </p>

            </div>

            <span class="mc-a03__cantidad">
                {{ $pagos->total() }}
                {{ $pagos->total() == 1 ? 'resultado' : 'resultados' }}
            </span>

        </div>


        {{-- ================================== --}}
        {{-- PESTAÑAS DE ESTADO --}}
        {{-- ================================== --}}

        <nav class="mc-a03__filtros"
             aria-label="Filtrar pagos por estado">

            @foreach ($estadosPago as $e)

                <a
                    href="{{ route('admin.pagos.index', [
                        'estado' => $e
                    ]) }}"
                    @class([
                        'mc-a03__filtro',
                        'mc-a03__filtro--activo' => $estado === $e
                    ])
                    @if ($estado === $e)
                        aria-current="page"
                    @endif
                >

                    <span class="mc-a03__filtro-punto
                                 mc-a03__filtro-punto--{{ $e }}"
                          aria-hidden="true">
                    </span>

                    <span>
                        {{ ucfirst(str_replace('_', ' ', $e)) }}
                    </span>

                    <strong>
                        {{ $conteos[$e] ?? 0 }}
                    </strong>

                </a>

            @endforeach

        </nav>


        {{-- ================================== --}}
        {{-- RECORDATORIO DE VALIDACIÓN --}}
        {{-- ================================== --}}

        <div class="mc-a03__recomendacion">

            <span class="mc-a03__recomendacion-icono"
                  aria-hidden="true">
                <svg width="22"
                     height="22"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </span>

            <div>

                <strong>
                    Verifica antes de aprobar
                </strong>

                <p>
                    Comprueba en el estado de cuenta
                    que el depósito se haya recibido.
                    Confirma la referencia bancaria,
                    el importe y el folio de la solicitud.
                    Un comprobante adjunto no demuestra,
                    por sí solo, que el pago fue recibido.
                </p>

            </div>

        </div>


        {{-- ================================== --}}
        {{-- TABLA DE PAGOS --}}
        {{-- ================================== --}}

        <div class="mc-a03__tabla-contenedor">

            <table class="mc-a03__tabla">

                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Solicitud</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Referencia</th>
                        <th scope="col" class="mc-a03__alinear-derecha">
                            Monto esperado
                        </th>
                        <th scope="col">Comprobante</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pagos as $pago)

                        <tr>

                            {{-- FECHA --}}

                            <td data-label="Fecha">

                                <time
                                    class="mc-a03__fecha"
                                    datetime="{{ $pago->fecha->toIso8601String() }}"
                                >
                                    <strong>
                                        {{ $pago->fecha->format('d/m/Y') }}
                                    </strong>

                                    <small>
                                        {{ $pago->fecha->format('H:i') }}
                                        hrs.
                                    </small>

                                </time>

                            </td>


                            {{-- FOLIO --}}

                            <td data-label="Solicitud">

                                @if ($pago->solicitud)

                                    <a
                                        class="mc-a03__folio"
                                        href="{{ route(
                                            'admin.solicitudes.show',
                                            $pago->solicitud
                                        ) }}"
                                    >
                                        {{ $pago->solicitud->folio }}

                                        <span aria-hidden="true">
                                            ↗
                                        </span>
                                    </a>

                                @else

                                    <span class="mc-a03__sin-dato">
                                        Sin solicitud
                                    </span>

                                @endif

                            </td>


                            {{-- CLIENTE --}}

                            <td data-label="Cliente">

                                <span class="mc-a03__cliente">

                                    <span class="mc-a03__cliente-icono"
                                          aria-hidden="true">
                                        <svg width="18"
                                             height="18"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <circle cx="12" cy="8" r="4"/>
                                            <path d="M4 21a8 8 0 0 1 16 0"/>
                                        </svg>
                                    </span>

                                    <span>
                                        {{ $pago->solicitud?->persona?->nombre_completo
                                            ?? 'No disponible' }}
                                    </span>

                                </span>

                            </td>


                            {{-- REFERENCIA --}}

                            <td data-label="Referencia">

                                <span class="mc-a03__referencia">
                                    {{ $pago->referencia }}
                                </span>

                            </td>


                            {{-- MONTO ESPERADO --}}

                            <td data-label="Monto esperado"
                                class="mc-a03__alinear-derecha">

                                @if ($pago->solicitud)

                                    <strong class="mc-a03__monto">
                                        ${{ number_format(
                                            $pago->solicitud->total,
                                            2
                                        ) }}
                                        <small>MXN</small>
                                    </strong>

                                @else

                                    <span class="mc-a03__sin-dato">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- COMPROBANTE --}}

                            <td data-label="Comprobante">

                                @if ($pago->comprobante_url)

                                    <a
                                        href="{{ $pago->comprobante_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mc-a03__comprobante"
                                        aria-label="Abrir comprobante de la referencia {{ $pago->referencia }} en nueva pestaña"
                                    >

                                        <svg width="17"
                                             height="17"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M14 3h7v7"/>
                                            <path d="M10 14 21 3"/>
                                            <path d="M21 13v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h7"/>
                                        </svg>

                                        Abrir archivo

                                    </a>

                                @else

                                    <span class="mc-a03__sin-dato">
                                        No adjunto
                                    </span>

                                @endif

                            </td>


                            {{-- ESTADO --}}

                            <td data-label="Estado">

                                <x-estado :valor="$pago->estado" />

                            </td>


                            {{-- ACCIONES --}}

                            <td data-label="Acciones">

                                @if ($pago->estado === 'pendiente')

                                    <div class="mc-a03__acciones">

                                        {{-- APROBAR --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.pagos.update',
                                                $pago
                                            ) }}"
                                            onsubmit="return confirm('¿Confirmas que verificaste la recepción del pago en el estado de cuenta y deseas aprobarlo?')"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                name="estado"
                                                value="aprobado"
                                                class="mc-a03__accion
                                                       mc-a03__accion--aprobar"
                                            >
                                                <span aria-hidden="true">
                                                    ✓
                                                </span>

                                                Aprobar

                                            </button>

                                        </form>


                                        {{-- RECHAZAR --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.pagos.update',
                                                $pago
                                            ) }}"
                                            onsubmit="return confirm('¿Rechazar el pago {{ $pago->referencia }}?')"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                name="estado"
                                                value="rechazado"
                                                class="mc-a03__accion
                                                       mc-a03__accion--rechazar"
                                            >
                                                <span aria-hidden="true">
                                                    ×
                                                </span>

                                                Rechazar

                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <span class="mc-a03__resuelto">
                                        <span aria-hidden="true">✓</span>
                                        Revisado
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="mc-a03__vacio-celda">

                                <div class="mc-a03__vacio">

                                    <span aria-hidden="true">
                                        ✳
                                    </span>

                                    <h3>
                                        No hay pagos en este estado.
                                    </h3>

                                    <p>
                                        Actualmente no existen
                                        registros para el filtro
                                        «{{ ucfirst(str_replace('_', ' ', $estado)) }}».
                                    </p>

                                    <a href="{{ route('admin.resumen') }}">
                                        Volver al resumen
                                        <span aria-hidden="true">↗</span>
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================================== --}}
        {{-- PIE Y PAGINACIÓN --}}
        {{-- ================================== --}}

        <div class="mc-a03__tabla-pie">

            <div class="mc-a03__tabla-nota">

                <span aria-hidden="true">ⓘ</span>

                <p>
                    La columna «Monto esperado» corresponde
                    al total de la solicitud asociada.
                    No confirma que el dinero
                    haya sido recibido.
                </p>

            </div>

            <span class="mc-a03__tabla-resultados">
                {{ $pagos->total() }}
                {{ $pagos->total() == 1
                    ? 'pago encontrado'
                    : 'pagos encontrados' }}
            </span>

        </div>


        <div class="mc-a03__paginacion">
            {{ $pagos->links() }}
        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 04 · PIE INFERIOR --}}
    {{-- ====================================== --}}

    <footer class="mc-a03__footer">

        <span>
            ✳ MIL CUMBRES
        </span>

        <span>
            ADMINISTRACIÓN DE PAGOS
        </span>

    </footer>

</div>

@endsection
