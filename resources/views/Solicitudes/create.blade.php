
{{-- C06 · Solicitar experiencia
     Tablas: solicitudes, asigna_experiencia,
     experiencias, asigna_disponibilidad, disponibilidad
--}}

@extends('layouts.app')

@section('titulo', 'Solicitar experiencia')

@section('descripcion',
    'Planea tu visita a Valle de Bravo y solicita experiencias de naturaleza, bienestar y cultura con Mil Cumbres.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/solicitar-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    $marcadas = old('experiencias', $seleccionadas);
    $marcadasIds = array_map('intval', (array) $marcadas);
@endphp

<div class="mc-c06">

    {{-- ======================================= --}}
    {{-- 01 · PORTADA FOTOGRÁFICA --}}
    {{-- ======================================= --}}

    <section class="mc-c06__hero">

        <img
            class="mc-c06__hero-imagen"
            src="{{ config('milcumbres.imagenes.hero_catalogo') }}"
            alt="Paisaje natural de Valle de Bravo"
            fetchpriority="high"
        >

        <div class="mc-c06__hero-sombra"></div>

        <div class="contenedor mc-c06__hero-contenido">

            <nav class="mc-c06__migas"
                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">
                    Inicio
                </a>

                <span>/</span>

                <a href="{{ route('experiencias.index') }}">
                    Experiencias
                </a>

                <span>/</span>

                <span>Solicitar</span>

            </nav>

            <span class="mc-c06__etiqueta
                         mc-c06__etiqueta--clara">
                ✳ MIL CUMBRES · PLANEA TU EXPERIENCIA
            </span>

            <h1>
                Tu próxima aventura
                <em>comienza aquí.</em>
            </h1>

            <p>
                Elige tus experiencias favoritas,
                selecciona la fecha en que deseas
                visitarnos y prepara un día especial
                en Valle de Bravo.
            </p>

            <div class="mc-c06__hero-pie">

                <span>DESCUBRE</span>
                <span aria-hidden="true">→</span>
                <span>PLANEA</span>
                <span aria-hidden="true">→</span>
                <span>DISFRUTA</span>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- 02 · INTRODUCCIÓN --}}
    {{-- ======================================= --}}

    <section class="mc-c06__introduccion">

        <div class="contenedor mc-c06__intro-grid">

            <div>

                <span class="mc-c06__etiqueta">
                    <span class="mc-c06__linea"></span>
                    ORGANIZA TU PRÓXIMA VISITA
                </span>

                <h2>
                    Diseña un día
                    <em>a tu manera.</em>
                </h2>

            </div>

            <div class="mc-c06__intro-texto">

                <p>
                    Puedes combinar una o varias
                    experiencias para el mismo día.
                    Te confirmaremos la disponibilidad
                    antes de solicitar cualquier pago.
                </p>

                <span>
                    ✳ UNA SOLICITUD · MÚLTIPLES EXPERIENCIAS
                </span>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- 03 · FORMULARIO DE SOLICITUD --}}
    {{-- ======================================= --}}

    <section class="mc-c06__seccion">

        <div class="contenedor">

            <x-avisos />

            <form
                method="POST"
                action="{{ route('solicitudes.store') }}"
                class="mc-c06__form"
                id="form-solicitud"
                novalidate
            >

                @csrf

                <div class="mc-c06__layout">

                    {{-- ================================= --}}
                    {{-- COLUMNA PRINCIPAL --}}
                    {{-- ================================= --}}

                    <div class="mc-c06__principal">

                        {{-- PASOS DEL FORMULARIO --}}

                        <nav class="mc-c06__pasos-nav"
                             aria-label="Secciones de la solicitud">

                            <a href="#mc-c06-experiencias">
                                <span>01</span>
                                Experiencias
                            </a>

                            <a href="#mc-c06-visita">
                                <span>02</span>
                                Fecha y personas
                            </a>

                            <a href="#mc-c06-resumen">
                                <span>03</span>
                                Resumen
                            </a>

                        </nav>


                        {{-- ================================= --}}
                        {{-- PASO 01 · EXPERIENCIAS --}}
                        {{-- ================================= --}}

                        <fieldset
                            class="pila mc-c06__bloque"
                            id="mc-c06-experiencias"
                            aria-describedby="ayuda-exp"
                        >

                            <legend class="mc-c06__legend">

                                <span class="mc-c06__paso-numero">
                                    01
                                </span>

                                <span>
                                    Selecciona tus
                                    <em>experiencias.</em>
                                </span>

                            </legend>

                            <p class="mc-c06__descripcion"
                               id="ayuda-exp">
                                Elige una o varias actividades.
                                El total se calcula multiplicando
                                el precio por persona de cada
                                experiencia por el número
                                de participantes.
                            </p>


                            {{-- LISTADO DE EXPERIENCIAS --}}

                            <div class="mc-c06__opciones">

                                @forelse ($experiencias as $exp)

                                    <label
                                        class="opcion-exp mc-c06__opcion"
                                    >

                                        {{-- CHECKBOX ORIGINAL --}}

                                        <input
                                            type="checkbox"
                                            name="experiencias[]"
                                            value="{{ $exp->id_experiencia }}"
                                            data-precio="{{ $exp->precio }}"
                                            data-dias="{{ $exp->disponibilidades->pluck('dias_semana')->unique()->implode(',') }}"
                                            @checked(
                                                in_array(
                                                    $exp->id_experiencia,
                                                    $marcadasIds
                                                )
                                            )
                                        >


                                        {{-- FOTOGRAFÍA --}}

                                        <img
                                            src="{{ $exp->imagen_url }}"
                                            alt=""
                                            loading="lazy"
                                        >


                                        {{-- DATOS DE LA ACTIVIDAD --}}

                                        <span class="mc-c06__opcion-info">

                                            <span class="mc-c06__opcion-etiqueta">
                                                EXPERIENCIA MIL CUMBRES
                                            </span>

                                            <strong>
                                                {{ $exp->nombre }}
                                            </strong>

                                            <small>

                                                {{ $exp->zona?->nombre ?? 'Zona por confirmar' }}

                                                ·

                                                {{ $exp->duracion_texto }}

                                            </small>

                                            @if ($exp->disponibilidades->isNotEmpty())

                                                <span class="mc-c06__opcion-dias">
                                                    <span aria-hidden="true">◷</span>

                                                    {{ $exp->disponibilidades
                                                        ->map->dia_texto
                                                        ->unique()
                                                        ->implode(', ') }}
                                                </span>

                                            @else

                                                <span class="mc-c06__opcion-dias">
                                                    Horarios por confirmar
                                                </span>

                                            @endif

                                        </span>


                                        {{-- PRECIO ORIGINAL --}}

                                        <strong class="mc-c06__opcion-precio">

                                            <small>
                                                POR PERSONA
                                            </small>

                                            {{ $exp->precio_texto }}

                                        </strong>

                                    </label>

                                @empty

                                    <div class="mc-c06__vacio">

                                        <span aria-hidden="true">✳</span>

                                        <h3>
                                            No hay experiencias
                                            para seleccionar.
                                        </h3>

                                        <p>
                                            Consulta el catálogo
                                            para conocer las actividades
                                            disponibles.
                                        </p>

                                        <a href="{{ route('experiencias.index') }}">
                                            Volver al catálogo →
                                        </a>

                                    </div>

                                @endforelse

                            </div>


                            @error('experiencias')

                                <span class="error mc-c06__error"
                                      role="alert">
                                    {{ $message }}
                                </span>

                            @enderror

                            @error('experiencias.*')

                                <span class="error mc-c06__error"
                                      role="alert">
                                    {{ $message }}
                                </span>

                            @enderror

                        </fieldset>


                        {{-- ================================= --}}
                        {{-- PASO 02 · FECHA Y PERSONAS --}}
                        {{-- ================================= --}}

                        <fieldset
                            class="form-grid mc-c06__bloque
                                   mc-c06__bloque--visita"
                            id="mc-c06-visita"
                        >

                            <legend class="mc-c06__legend completo">

                                <span class="mc-c06__paso-numero">
                                    02
                                </span>

                                <span>
                                    Planea
                                    <em>tu visita.</em>
                                </span>

                            </legend>

                            <p class="mc-c06__descripcion completo">
                                Indícanos cuándo te gustaría
                                realizar las actividades
                                y cuántas personas asistirán.
                            </p>


                            {{-- FECHA --}}

                            <div class="campo mc-c06__campo">

                                <label for="fecha_deseada">
                                    Fecha deseada
                                    <span class="mc-c06__obligatorio">*</span>
                                </label>

                                <input
                                    @class([
                                        'control',
                                        'mc-c06__control',
                                        'es-invalido' =>
                                            $errors->has('fecha_deseada')
                                    ])
                                    type="date"
                                    id="fecha_deseada"
                                    name="fecha_deseada"
                                    value="{{ old('fecha_deseada', $fecha) }}"
                                    min="{{ now()->addDay()->toDateString() }}"
                                    required
                                >

                                {{-- Identificador original de solicitud.js --}}

                                <span
                                    class="ayuda mc-c06__aviso-dia"
                                    id="aviso-dia"
                                    aria-live="polite"
                                ></span>

                                @error('fecha_deseada')

                                    <span class="error mc-c06__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            {{-- PARTICIPANTES --}}

                            <div class="campo mc-c06__campo">

                                <label for="cant_persona">
                                    Número de personas
                                    <span class="mc-c06__obligatorio">*</span>
                                </label>

                                <input
                                    @class([
                                        'control',
                                        'mc-c06__control',
                                        'es-invalido' =>
                                            $errors->has('cant_persona')
                                    ])
                                    type="number"
                                    id="cant_persona"
                                    name="cant_persona"
                                    value="{{ old('cant_persona', $personas) }}"
                                    min="1"
                                    required
                                >

                                <span class="ayuda">
                                    Incluye a todas las personas
                                    que participarán.
                                </span>

                                @error('cant_persona')

                                    <span class="error mc-c06__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            {{-- NOTA OPCIONAL --}}

                            <div class="campo completo mc-c06__campo">

                                <label for="nota">
                                    Información adicional
                                    <span class="mc-c06__opcional">
                                        (opcional)
                                    </span>
                                </label>

                                <textarea
                                    class="control mc-c06__control
                                           mc-c06__textarea"
                                    id="nota"
                                    name="nota"
                                    maxlength="1000"
                                    rows="5"
                                    placeholder="Cuéntanos si existen alergias, necesidades de movilidad, si viajan con niños o alguna información relevante para tu visita..."
                                >{{ old('nota') }}</textarea>

                                <span class="ayuda">
                                    Puedes escribir cualquier
                                    detalle que nos ayude
                                    a preparar tu experiencia.
                                </span>

                                @error('nota')

                                    <span class="error mc-c06__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>

                        </fieldset>


                        {{-- RECORDATORIO --}}

                        <div class="mc-c06__recordatorio">

                            <span class="mc-c06__recordatorio-icono"
                                  aria-hidden="true">
                                ✳
                            </span>

                            <div>

                                <strong>
                                    Tu fecha es una preferencia.
                                </strong>

                                <p>
                                    Revisaremos los horarios y
                                    la disponibilidad de todas
                                    las experiencias seleccionadas.
                                    La reservación solo quedará
                                    confirmada después de
                                    nuestra revisión.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ================================= --}}
                    {{-- PASO 03 · RESUMEN LATERAL --}}
                    {{-- ================================= --}}

                    <aside
                        class="mc-c06__lateral"
                        id="mc-c06-resumen"
                    >

                        <div class="mc-c06__resumen-card">

                            <div class="mc-c06__resumen-header">

                                <span>
                                    ✳ TU SOLICITUD
                                </span>

                                <h2>
                                    Todo listo
                                    <em>para planear.</em>
                                </h2>

                                <p>
                                    Revisa el total estimado
                                    y envía tu solicitud
                                    para confirmar disponibilidad.
                                </p>

                            </div>


                            <div class="mc-c06__resumen-contenido">

                                <span class="mc-c06__resumen-etiqueta">
                                    RESUMEN DE TU VISITA
                                </span>

                                <div class="mc-c06__resumen-fila">

                                    <span>
                                        <span aria-hidden="true">✳</span>
                                        Actividades
                                    </span>

                                    <strong>
                                        Según tu selección
                                    </strong>

                                </div>

                                <div class="mc-c06__resumen-fila">

                                    <span>
                                        <span aria-hidden="true">◷</span>
                                        Fecha
                                    </span>

                                    <strong>
                                        Por confirmar
                                    </strong>

                                </div>


                                {{-- TOTAL ORIGINAL, CONTROLADO POR JS --}}

                                <div class="resumen-total mc-c06__total">

                                    <span class="texto-suave">
                                        TOTAL ESTIMADO
                                    </span>

                                    <strong id="total">
                                        $0.00 MXN
                                    </strong>

                                    <small>
                                        El importe es estimado
                                        y no constituye un cobro.
                                    </small>

                                </div>


                                {{-- ACCIÓN ORIGINAL --}}

                                <button
                                    class="btn btn--primario btn--bloque
                                           mc-c06__enviar"
                                    type="submit"
                                >

                                    Enviar solicitud

                                    <span aria-hidden="true">↗</span>

                                </button>

                                <p class="ayuda centrado mb-0
                                          mc-c06__sin-cobro">

                                    Aún no se cobra nada.
                                    Recibirás un folio para
                                    consultar el seguimiento.

                                </p>

                            </div>


                            <div class="mc-c06__resumen-pie">

                                <span aria-hidden="true">ⓘ</span>

                                <p>
                                    La solicitud no garantiza
                                    automáticamente la fecha
                                    ni los cupos seleccionados.
                                </p>

                            </div>

                        </div>


                        <div class="mc-c06__lateral-extra">

                            <span aria-hidden="true">✳</span>

                            <strong>
                                Viaja con propósito.
                            </strong>

                            <p>
                                Conoce la propuesta de
                                turismo regenerativo
                                de Mil Cumbres.
                            </p>

                            <a href="{{ route('modelo') }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                Nuestro modelo ↗
                            </a>

                        </div>

                    </aside>

                </div>

            </form>

        </div>

    </section>

</div>

@endsection


{{-- ======================================= --}}
{{-- JAVASCRIPT EXISTENTE --}}
{{-- ======================================= --}}

@push('scripts')

<script
    src="{{ asset('js/solicitud.js') }}"
    defer
></script>

@endpush
