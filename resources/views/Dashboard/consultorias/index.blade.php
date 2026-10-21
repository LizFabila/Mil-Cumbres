
{{-- A04 · Administración de consultorías
     Tablas: solicitud_consultorias, organizaciones,
     tipo_organizaciones, personas,
     seguimiento_consultorias
--}}

@extends('layouts.admin')

@section('titulo', 'Consultorías')

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/admin-consultorias.css') }}">
@endpush

@section('contenido')

@php
    $estadoActivo = (string) ($filtros['estado'] ?? '');
    $tipoActivo = (string) ($filtros['tipo'] ?? '');

    $hayFiltros = $estadoActivo !== '' || $tipoActivo !== '';

    // Los siguientes indicadores, excepto total(),
    // corresponden solamente a la página actual.
    $conSeguimientoPagina = $consultorias->getCollection()
        ->filter(fn ($c) => (int) ($c->seguimientos_count ?? 0) > 0)
        ->count();
@endphp

<div class="mc-a04">

    {{-- ====================================== --}}
    {{-- 01 · ENCABEZADO --}}
    {{-- ====================================== --}}

    
<section class="mc-a04__hero">

    <div class="mc-a04__hero-inner">

        <div class="mc-a04__hero-principal">

            

            <h1>
                Gestión de <em>consultorías.</em>
            </h1>

            <p>
                Consulta y administra las solicitudes de consultoría
                de empresas y organizaciones agrarias. Revisa sus
                expedientes y da seguimiento a cada proyecto desde
                un solo lugar.
            </p>

        </div>

    </div>

    

</section>



    {{-- ====================================== --}}
    {{-- 02 · INDICADORES --}}
    {{-- ====================================== --}}

    <section class="mc-a04__indicadores"
             aria-label="Resumen de solicitudes">

        {{-- TOTAL FILTRADO --}}

        <div class="mc-a04__indicador">

            <div class="mc-a04__indicador-top">

                <span>
                    SOLICITUDES
                </span>


            </div>

            <strong>
                {{ $consultorias->total() }}
            </strong>

            <p>
                {{ $hayFiltros
                    ? 'Solicitudes que coinciden con los filtros'
                    : 'Solicitudes registradas' }}
            </p>

        </div>


        {{-- RESULTADOS EN LA PÁGINA --}}

        <div class="mc-a04__indicador">

            <div class="mc-a04__indicador-top">

                <span>
                    VISTA ACTUAL
                </span>


            </div>

            <strong>
                {{ $consultorias->count() }}
            </strong>

            <p>
                Expedientes en esta página
            </p>

        </div>


        {{-- CON SEGUIMIENTO EN LA PÁGINA --}}

        <div class="mc-a04__indicador
                    mc-a04__indicador--destacado">

            <div class="mc-a04__indicador-top">

                <span>
                    SEGUIMIENTO
                </span>


            </div>

            <strong>
                {{ $conSeguimientoPagina }}
            </strong>

            <p>
                Expedientes con comentarios
                en esta página
            </p>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 03 · FILTROS --}}
    {{-- ====================================== --}}

    <section class="mc-a04__filtros-section">

        <div class="mc-a04__section-heading">

            <div>

                <span class="mc-a04__eyebrow">
                    <span class="mc-a04__linea"></span>
                    ORGANIZA TU TRABAJO
                </span>

                <h2>
                    Centro de
                    <em>consultorías.</em>
                </h2>

                <p>
                    Encuentra solicitudes según su estado
                    o el tipo de organización.
                </p>

            </div>

            <a href="{{ route('admin.resumen') }}"
               class="mc-a04__volver">

                Ir al panel general

                <span aria-hidden="true">↗</span>

            </a>

        </div>


        {{-- FILTROS RÁPIDOS POR ESTADO --}}

        <div class="mc-a04__estados">

            <span class="mc-a04__estados-label">
                FILTRAR POR ESTADO
            </span>

            <div class="mc-a04__estados-opciones">

                <a
                    href="{{ route(
                        'admin.consultorias.index',
                        request()->except('estado', 'page')
                    ) }}"

                    @class([
                        'mc-a04__estado-link',
                        'mc-a04__estado-link--activo' =>
                            $estadoActivo === ''
                    ])
                >
                    Todos
                </a>

                @foreach (\App\Models\SolicitudConsultoria::ESTADOS as $e)

                    <a
                        href="{{ route(
                            'admin.consultorias.index',
                            array_merge(
                                request()->except('estado', 'page'),
                                ['estado' => $e]
                            )
                        ) }}"

                        @class([
                            'mc-a04__estado-link',
                            'mc-a04__estado-link--activo' =>
                                $estadoActivo === (string) $e
                        ])
                    >

                        <span
                            @class([
                                'mc-a04__estado-punto',
                                'mc-a04__estado-punto--recibida' =>
                                    $e === 'recibida',
                                'mc-a04__estado-punto--proceso' =>
                                    $e === 'en_proceso',
                                'mc-a04__estado-punto--atendida' =>
                                    $e === 'atendida',
                                'mc-a04__estado-punto--cerrada' =>
                                    $e === 'cerrada',
                            ])
                        ></span>

                        {{ ucfirst(str_replace('_', ' ', $e)) }}

                    </a>

                @endforeach

            </div>

        </div>


        {{-- FORMULARIO DE FILTROS --}}

        <form
            method="GET"
            action="{{ route('admin.consultorias.index') }}"
            class="mc-a04__filtro-form"
        >

            <div class="mc-a04__campo">

                <label for="mc-a04-estado">
                    Estado de la solicitud
                </label>

                <select
                    id="mc-a04-estado"
                    name="estado"
                >

                    <option value="">
                        Todos los estados
                    </option>

                    @foreach (\App\Models\SolicitudConsultoria::ESTADOS as $e)

                        <option
                            value="{{ $e }}"
                            @selected($estadoActivo === (string) $e)
                        >
                            {{ ucfirst(str_replace('_', ' ', $e)) }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mc-a04__campo">

                <label for="mc-a04-tipo">
                    Tipo de organización
                </label>

                <select
                    id="mc-a04-tipo"
                    name="tipo"
                >

                    <option value="">
                        Todas las organizaciones
                    </option>

                    @foreach ($tipos as $t)

                        <option
                            value="{{ $t->id_tipo_organizacion }}"
                            @selected(
                                $tipoActivo ===
                                (string) $t->id_tipo_organizacion
                            )
                        >
                            {{ $t->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>


            <button
                type="submit"
                class="mc-a04__filtrar"
            >
                Aplicar filtros
                <span aria-hidden="true">↗</span>
            </button>


            @if ($hayFiltros)

                <a
                    href="{{ route('admin.consultorias.index') }}"
                    class="mc-a04__limpiar"
                >
                    Limpiar
                </a>

            @endif

        </form>

    </section>


    {{-- ====================================== --}}
    {{-- 04 · BANDEJA DE CONSULTORÍAS --}}
    {{-- ====================================== --}}

    <section class="mc-a04__bandeja">

        <div class="mc-a04__bandeja-heading">

            <div>

                <span class="mc-a04__eyebrow">
                    <span class="mc-a04__linea"></span>
                    EXPEDIENTES
                </span>

                <h2>
                    Solicitudes registradas
                </h2>

                <p>
                    Revisa la información de las organizaciones
                    y accede a los detalles de cada solicitud.
                </p>

            </div>

            <div class="mc-a04__conteo">

                <strong>
                    {{ $consultorias->total() }}
                </strong>

                <span>
                    {{ $consultorias->total() === 1
                        ? 'resultado'
                        : 'resultados' }}
                </span>

            </div>

        </div>


        {{-- FILTRO ACTIVO --}}

        @if ($hayFiltros)

            <div class="mc-a04__filtros-activos">


                <p>
                    Estás consultando solicitudes
                    con los filtros seleccionados.
                </p>

                <a href="{{ route('admin.consultorias.index') }}">
                    Mostrar todas
                </a>

            </div>

        @endif


        {{-- ================================= --}}
        {{-- TABLA DE EXPEDIENTES --}}
        {{-- ================================= --}}

        <div class="mc-a04__tabla-wrap">

            <table class="mc-a04__tabla">

                <thead>

                    <tr>

                        <th scope="col">
                            SOLICITUD
                        </th>

                        <th scope="col">
                            ORGANIZACIÓN
                        </th>

                        <th scope="col">
                            CONTACTO
                        </th>

                        <th scope="col">
                            UBICACIÓN
                        </th>

                        <th scope="col">
                            SEGUIMIENTO
                        </th>

                        <th scope="col">
                            ESTADO
                        </th>

                        <th scope="col">
                            ACCIÓN
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($consultorias as $c)

                        <tr>

                            {{-- SOLICITUD Y FECHA --}}

                            <td data-label="Solicitud">

                                <div class="mc-a04__solicitud">

                                    <span class="mc-a04__folio">
                                        CON-{{ str_pad(
                                            $c->id_solicitud_consultoria,
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}
                                    </span>

                                    <span class="mc-a04__fecha">
                                        {{ $c->fecha_solicitud->translatedFormat('d M Y') }}
                                    </span>

                                </div>

                            </td>


                            {{-- ORGANIZACIÓN --}}

                            <td data-label="Organización">

                                <div class="mc-a04__organizacion">

                                    <div class="mc-a04__avatar"
                                         aria-hidden="true">
                                        {{ \Illuminate\Support\Str::upper(
                                            \Illuminate\Support\Str::substr(
                                                $c->organizacion?->nombre ?? 'O',
                                                0,
                                                1
                                            )
                                        ) }}
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $c->organizacion?->nombre
                                               ?? 'Organización no disponible' }}
                                        </strong>

                                        <span>
                                            {{ $c->organizacion?->tipo?->nombre
                                               ?? 'Tipo no disponible' }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- CONTACTO --}}

                            <td data-label="Contacto">

                                <div class="mc-a04__contacto">

                                    <strong>
                                        {{ $c->persona?->nombre_completo
                                           ?? 'Usuario no disponible' }}
                                    </strong>

                                    @if ($c->persona?->email)

                                        <span>
                                            {{ $c->persona->email }}
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- UBICACIÓN --}}

                            <td data-label="Ubicación">

                                <span class="mc-a04__ubicacion">
                                    <span aria-hidden="true">⌖</span>
                                    {{ $c->ubicacion }}
                                </span>

                            </td>


                            {{-- SEGUIMIENTO --}}

                            <td data-label="Seguimiento">

                                @if ($c->seguimientos_count > 0)

                                    <span class="mc-a04__seguimiento
                                                 mc-a04__seguimiento--activo">


                                        {{ $c->seguimientos_count }}

                                        {{ $c->seguimientos_count == 1
                                            ? 'comentario'
                                            : 'comentarios' }}

                                    </span>

                                @else

                                    <span class="mc-a04__seguimiento
                                                 mc-a04__seguimiento--vacio">

                                        Sin seguimiento

                                    </span>

                                @endif

                            </td>


                            {{-- ESTADO ORIGINAL --}}

                            <td data-label="Estado">

                                <x-estado :valor="$c->estado" />

                            </td>


                            {{-- ACCIÓN --}}

                            <td data-label="Acción">

                                <a
                                    href="{{ route('admin.consultorias.show', $c) }}"
                                    class="mc-a04__abrir"
                                >

                                    Ver expediente

                                    <span aria-hidden="true">↗</span>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr class="mc-a04__fila-vacia">

                            <td colspan="7">

                                <div class="mc-a04__vacio">


                                    <h3>
                                        No hay consultorías
                                        para mostrar.
                                    </h3>

                                    <p>
                                        @if ($hayFiltros)

                                            No encontramos solicitudes
                                            que coincidan con los filtros
                                            seleccionados.

                                        @else

                                            Todavía no hay solicitudes
                                            de consultoría registradas.

                                        @endif
                                    </p>

                                    @if ($hayFiltros)

                                        <a href="{{ route('admin.consultorias.index') }}">
                                            Ver todas las consultorías
                                            <span aria-hidden="true">↗</span>
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================================= --}}
        {{-- PAGINACIÓN --}}
        {{-- ================================= --}}

        @if ($consultorias->hasPages())

            <div class="mc-a04__paginacion">

                <span class="mc-a04__paginacion-info">

                    Mostrando
                    {{ $consultorias->firstItem() }}
                    –
                    {{ $consultorias->lastItem() }}
                    de
                    {{ $consultorias->total() }}

                </span>

                <div>
                    {{ $consultorias->withQueryString()->links() }}
                </div>

            </div>

        @endif

    </section>


    {{-- ====================================== --}}
    {{-- 05 · NOTA FINAL --}}
    {{-- ====================================== --}}

    <div class="mc-a04__ayuda">


        <div>

            <strong>
                Un seguimiento claro para cada proyecto.
            </strong>

            <p>
                Selecciona «Ver expediente» para consultar
                la necesidad registrada, revisar el estado
                de la consultoría y acceder a sus comentarios
                de seguimiento.
            </p>

        </div>

        <a href="{{ route('admin.resumen') }}">
            Panel general
            <span aria-hidden="true">↗</span>
        </a>

    </div>

</div>

@endsection
