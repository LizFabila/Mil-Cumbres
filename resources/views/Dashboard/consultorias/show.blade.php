
{{-- A04 · Expediente de consultoría
     Tablas: solicitud_consultorias, organizaciones,
     tipo_organizaciones, personas,
     seguimiento_consultorias
--}}

@extends('layouts.admin')

@section('titulo', 'Detalle de consultoría')

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/admin-consultoria-detalle.css') }}">
@endpush

@section('contenido')

@php
    $nombreOrganizacion =
        $consultoria->organizacion?->nombre
        ?? 'Organización no disponible';

    $tipoOrganizacion =
        $consultoria->organizacion?->tipo?->nombre
        ?? 'Sin tipo registrado';

    $nombreContacto =
        $consultoria->persona?->nombre_completo
        ?? 'Contacto no disponible';

    $folio = 'CON-' . str_pad(
        $consultoria->id_solicitud_consultoria,
        5,
        '0',
        STR_PAD_LEFT
    );

    $cantidadSeguimientos = $consultoria->seguimientos->count();
@endphp

<div class="mc-cd">

    {{-- ======================================= --}}
    {{-- 01 · NAVEGACIÓN --}}
    {{-- ======================================= --}}

    <nav class="mc-cd-breadcrumb"
         aria-label="Ruta de navegación">

        <a href="{{ route('admin.resumen') }}">
            Panel administrativo
        </a>

        <span>/</span>

        <a href="{{ route('admin.consultorias.index') }}">
            Consultorías
        </a>

        <span>/</span>

        <span>{{ $folio }}</span>

    </nav>


    {{-- ======================================= --}}
    {{-- 02 · CABECERA DEL EXPEDIENTE --}}
    {{-- ======================================= --}}

    <header class="mc-cd-hero">

        <span class="mc-cd-hero__ornamento"
              aria-hidden="true">✳</span>

        <div class="mc-cd-hero__contenido">

            <div class="mc-cd-hero__principal">

                <span class="mc-cd-eyebrow mc-cd-eyebrow--claro">
                    <span class="mc-cd-eyebrow__linea"></span>
                    MIL CUMBRES / CONSULTORÍAS
                </span>

                <div class="mc-cd-hero__identificador">

                    <span>EXPEDIENTE</span>

                    <strong>
                        {{ $folio }}
                    </strong>

                </div>

                <h1>
                    {{ $nombreOrganizacion }}
                </h1>

                <p>
                    Seguimiento de la solicitud
                    de consultoría en turismo regenerativo.
                </p>

                <div class="mc-cd-hero__etiquetas">

                    <span>
                        ✳ {{ $tipoOrganizacion }}
                    </span>

                    <span>
                        ⌖ {{ $consultoria->ubicacion ?: 'Ubicación no especificada' }}
                    </span>

                </div>

            </div>


            {{-- ESTADO ACTUAL --}}

            <div class="mc-cd-hero__estado">

                <span class="mc-cd-hero__estado-titulo">
                    ESTADO DE LA SOLICITUD
                </span>

                <div class="mc-cd-hero__estado-valor">
                    <x-estado :valor="$consultoria->estado" />
                </div>

                <span class="mc-cd-hero__estado-nota">
                    Consulta o modifica el estado
                    desde el panel de gestión.
                </span>

            </div>

        </div>

        <div class="mc-cd-hero__pie">

            <span>
                TURISMO REGENERATIVO
            </span>

            <span>
                GESTIÓN DE PROYECTOS
            </span>

        </div>

    </header>


    {{-- ======================================= --}}
    {{-- 03 · INDICADORES DEL EXPEDIENTE --}}
    {{-- ======================================= --}}

    <section class="mc-cd-resumen"
             aria-label="Información general del expediente">

        <div class="mc-cd-resumen__item">

            <span class="mc-cd-resumen__icono"
                  aria-hidden="true">
                ◷
            </span>

            <div>

                <small>
                    FECHA DE SOLICITUD
                </small>

                <strong>
                    {{ $consultoria->fecha_solicitud->format('d/m/Y') }}
                </strong>

                <span>
                    {{ $consultoria->fecha_solicitud->format('H:i') }} hrs.
                </span>

            </div>

        </div>


        <div class="mc-cd-resumen__item">

            <span class="mc-cd-resumen__icono"
                  aria-hidden="true">
                ✳
            </span>

            <div>

                <small>
                    SEGUIMIENTOS REGISTRADOS
                </small>

                <strong>
                    {{ $cantidadSeguimientos }}
                    {{ $cantidadSeguimientos === 1
                        ? 'comentario'
                        : 'comentarios' }}
                </strong>

                <span>
                    Historial del expediente
                </span>

            </div>

        </div>


        <div class="mc-cd-resumen__item">

            <span class="mc-cd-resumen__icono"
                  aria-hidden="true">
                ⌖
            </span>

            <div>

                <small>
                    TIPO DE ORGANIZACIÓN
                </small>

                <strong>
                    {{ $tipoOrganizacion }}
                </strong>

                <span>
                    {{ $nombreOrganizacion }}
                </span>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- MENSAJES DEL SISTEMA --}}
    {{-- ======================================= --}}

    @if (session('ok'))

        <div class="mc-cd-mensaje mc-cd-mensaje--exito"
             role="status">

            <span aria-hidden="true">✓</span>

            <p>{{ session('ok') }}</p>

        </div>

    @endif


    @if ($errors->any())

        <div class="mc-cd-mensaje mc-cd-mensaje--error"
             role="alert">

            <span aria-hidden="true">!</span>

            <div>
                <strong>
                    Revisa la información enviada.
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    @endif


    {{-- ======================================= --}}
    {{-- 04 · CUERPO DEL EXPEDIENTE --}}
    {{-- ======================================= --}}

    <div class="mc-cd-layout">

        {{-- COLUMNA PRINCIPAL --}}

        <div class="mc-cd-principal">

            {{-- ================================= --}}
            {{-- NECESIDAD DEL SOLICITANTE --}}
            {{-- ================================= --}}

            <section class="mc-cd-panel">

                <div class="mc-cd-panel__encabezado">

                    <div>

                        <span class="mc-cd-eyebrow">
                            <span class="mc-cd-eyebrow__linea"></span>
                            01 / INFORMACIÓN DEL PROYECTO
                        </span>

                        <h2>
                            Necesidad
                            <em>registrada.</em>
                        </h2>

                    </div>

                    <span class="mc-cd-panel__adorno"
                          aria-hidden="true">
                        ✳
                    </span>

                </div>

                <p class="mc-cd-panel__intro">
                    Descripción proporcionada
                    por el solicitante al registrar
                    su consultoría.
                </p>

                <div class="mc-cd-necesidad">

                    <span class="mc-cd-necesidad__etiqueta">
                        DESCRIPCIÓN ORIGINAL
                    </span>

                    <p>
                        {{ $consultoria->necesidad ?: 'No se registró una descripción de la necesidad.' }}
                    </p>

                </div>

            </section>


            {{-- ================================= --}}
            {{-- HISTORIAL DE SEGUIMIENTO --}}
            {{-- ================================= --}}

            <section class="mc-cd-panel">

                <div class="mc-cd-panel__encabezado">

                    <div>

                        <span class="mc-cd-eyebrow">
                            <span class="mc-cd-eyebrow__linea"></span>
                            02 / HISTORIAL DEL PROYECTO
                        </span>

                        <h2>
                            Línea de
                            <em>seguimiento.</em>
                        </h2>

                    </div>

                    <span class="mc-cd-contador">
                        {{ $cantidadSeguimientos }}
                    </span>

                </div>

                <p class="mc-cd-panel__intro">
                    Consulta los comentarios registrados
                    durante la atención de esta consultoría.
                </p>


                {{-- LÍNEA DE TIEMPO --}}

                @if ($consultoria->seguimientos->isNotEmpty())

                    <ol class="mc-cd-timeline">

                        @foreach ($consultoria->seguimientos as $seg)

                            <li class="mc-cd-timeline__item">

                                <span class="mc-cd-timeline__punto"
                                      aria-hidden="true">
                                    ✳
                                </span>

                                <article class="mc-cd-timeline__card">

                                    <div class="mc-cd-timeline__cabecera">

                                        <span class="mc-cd-timeline__numero">

                                            SEGUIMIENTO
                                            {{ sprintf('%02d', $loop->iteration) }}

                                        </span>

                                        <time datetime="{{ $seg->fecha->toIso8601String() }}">

                                            {{ $seg->fecha->format('d/m/Y') }}

                                            ·

                                            {{ $seg->fecha->format('H:i') }}

                                        </time>

                                    </div>

                                    <h3>
                                        Comentario de seguimiento
                                    </h3>

                                    <p>
                                        {{ $seg->comentario }}
                                    </p>

                                </article>

                            </li>

                        @endforeach

                    </ol>

                @else

                    <div class="mc-cd-timeline-vacia">

                        <span aria-hidden="true">✳</span>

                        <h3>
                            El seguimiento comienza aquí.
                        </h3>

                        <p>
                            Todavía no hay comentarios
                            registrados para esta consultoría.
                            Agrega el primero para comenzar
                            a documentar su atención.
                        </p>

                    </div>

                @endif

            </section>


            {{-- ================================= --}}
            {{-- AGREGAR SEGUIMIENTO --}}
            {{-- ================================= --}}

            <section class="mc-cd-panel mc-cd-panel--nuevo"
                     id="nuevo-seguimiento">

                <div class="mc-cd-panel__encabezado">

                    <div>

                        <span class="mc-cd-eyebrow">
                            <span class="mc-cd-eyebrow__linea"></span>
                            03 / NUEVA ACTUALIZACIÓN
                        </span>

                        <h2>
                            Registra un
                            <em>nuevo seguimiento.</em>
                        </h2>

                    </div>

                </div>

                <p class="mc-cd-panel__intro">
                    Documenta una llamada, visita,
                    propuesta enviada u otra actividad
                    relacionada con la atención
                    de la solicitud.
                </p>


                <form
                    method="POST"
                    action="{{ route('admin.consultorias.seguimiento', $consultoria) }}"
                    class="mc-cd-form"
                >

                    @csrf

                    <div class="mc-cd-campo">

                        <label for="mc-cd-comentario">
                            Comentario de seguimiento
                            <span>*</span>
                        </label>

                        <textarea
                            id="mc-cd-comentario"
                            name="comentario"
                            rows="6"
                            maxlength="2000"
                            placeholder="Describe la actividad realizada, el acuerdo alcanzado o el siguiente paso del proyecto..."
                            aria-describedby="mc-cd-comentario-ayuda"
                            required
                        >{{ old('comentario') }}</textarea>

                        <div class="mc-cd-campo__pie">

                            <span id="mc-cd-comentario-ayuda">
                                Máximo 2000 caracteres.
                            </span>

                            <span>
                                Campo obligatorio
                            </span>

                        </div>

                    </div>

                    <div class="mc-cd-form__pie">

                        <p>
                            El comentario quedará
                            registrado en el historial
                            de esta consultoría.
                        </p>

                        <button
                            type="submit"
                            class="mc-cd-boton mc-cd-boton--verde"
                        >

                            Guardar seguimiento

                            <span aria-hidden="true">↗</span>

                        </button>

                    </div>

                </form>

            </section>

        </div>


        {{-- ================================= --}}
        {{-- PANEL LATERAL --}}
        {{-- ================================= --}}

        <aside class="mc-cd-sidebar">

            {{-- INFORMACIÓN DEL CONTACTO --}}

            <section class="mc-cd-lateral">

                <span class="mc-cd-lateral__eyebrow">
                    ✳ INFORMACIÓN DEL SOLICITANTE
                </span>

                <h2>
                    Datos de
                    <em>contacto.</em>
                </h2>

                <div class="mc-cd-contacto">

                    <div class="mc-cd-contacto__avatar"
                         aria-hidden="true">

                        {{ \Illuminate\Support\Str::upper(
                            \Illuminate\Support\Str::substr(
                                $consultoria->persona?->nombre ?? 'C',
                                0,
                                1
                            )
                        ) }}

                    </div>

                    <div>

                        <strong>
                            {{ $nombreContacto }}
                        </strong>

                        <span>
                            Solicitante de consultoría
                        </span>

                    </div>

                </div>


                <dl class="mc-cd-lista-datos">

                    <div>

                        <dt>
                            CORREO ELECTRÓNICO
                        </dt>

                        <dd>

                            @if ($consultoria->persona?->email)

                                <a href="mailto:{{ $consultoria->persona->email }}">
                                    {{ $consultoria->persona->email }}
                                </a>

                            @else

                                No disponible

                            @endif

                        </dd>

                    </div>


                    <div>

                        <dt>
                            TELÉFONO
                        </dt>

                        <dd>

                            @if ($consultoria->persona?->telefono)

                                <a href="tel:{{ $consultoria->persona->telefono }}">
                                    {{ $consultoria->persona->telefono }}
                                </a>

                            @else

                                No registrado

                            @endif

                        </dd>

                    </div>


                    <div>

                        <dt>
                            ORGANIZACIÓN
                        </dt>

                        <dd>
                            {{ $nombreOrganizacion }}
                        </dd>

                    </div>


                    <div>

                        <dt>
                            UBICACIÓN
                        </dt>

                        <dd>
                            {{ $consultoria->ubicacion ?: 'No especificada' }}
                        </dd>

                    </div>

                </dl>

            </section>


            {{-- ================================= --}}
            {{-- GESTIONAR ESTADO --}}
            {{-- ================================= --}}

            <section class="mc-cd-estado">

                <div class="mc-cd-estado__encabezado">

                    <span>
                        ✳ GESTIÓN DEL EXPEDIENTE
                    </span>

                    <h2>
                        Actualizar
                        <em>estado.</em>
                    </h2>

                    <p>
                        Selecciona el estado
                        correspondiente a la atención
                        actual de esta consultoría.
                    </p>

                </div>


                <div class="mc-cd-estado__actual">

                    <span>
                        ESTADO ACTUAL
                    </span>

                    <x-estado :valor="$consultoria->estado" />

                </div>


                <form
                    method="POST"
                    action="{{ route('admin.consultorias.estado', $consultoria) }}"
                    class="mc-cd-estado__form"
                >

                    @csrf
                    @method('PATCH')

                    <label for="mc-cd-estado">
                        Nuevo estado
                    </label>

                    <select
                        id="mc-cd-estado"
                        name="estado"
                        required
                    >

                        @foreach (\App\Models\SolicitudConsultoria::ESTADOS as $e)

                            <option
                                value="{{ $e }}"
                                @selected(
                                    old('estado', $consultoria->estado) === $e
                                )
                            >
                                {{ ucfirst(str_replace('_', ' ', $e)) }}
                            </option>

                        @endforeach

                    </select>

                    <button
                        type="submit"
                        class="mc-cd-boton mc-cd-boton--claro"
                    >

                        Guardar nuevo estado

                        <span aria-hidden="true">↗</span>

                    </button>

                </form>

            </section>


            {{-- ================================= --}}
            {{-- ACCESO A LISTADO --}}
            {{-- ================================= --}}

            <a href="{{ route('admin.consultorias.index') }}"
               class="mc-cd-volver">

                <span class="mc-cd-volver__icono"
                      aria-hidden="true">
                    ←
                </span>

                <span>

                    <strong>
                        Volver a consultorías
                    </strong>

                    <small>
                        Consultar otros expedientes
                    </small>

                </span>

            </a>

        </aside>

    </div>


    {{-- ======================================= --}}
    {{-- 05 · CIERRE DEL EXPEDIENTE --}}
    {{-- ======================================= --}}

    <div class="mc-cd-footer">

        <span>
            ✳ MIL CUMBRES · TURISMO REGENERATIVO
        </span>

        <span>
            EXPEDIENTE {{ $folio }}
        </span>

    </div>

</div>

@endsection
