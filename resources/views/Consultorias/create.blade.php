
{{-- C11 · Solicitar consultoría
     Tablas: solicitud_consultorias, organizaciones,
     tipo_organizaciones, personas
--}}

@extends('layouts.app')

@section('titulo', 'Solicitar consultoría')

@section('descripcion',
    'Solicita una consultoría de turismo regenerativo para tu organización con Mil Cumbres.'
)

@push('estilos')
    <link rel="stylesheet"
          href="{{ asset('css/solicitar-consultoria-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-c11">

    {{-- ====================================== --}}
    {{-- 01 · PORTADA FOTOGRÁFICA --}}
    {{-- ====================================== --}}

    <section class="mc-c11__hero">

        <img
            src="{{ config('milcumbres.imagenes.consultoria') }}"
            class="mc-c11__hero-img"
            alt="Naturaleza y territorio"
            fetchpriority="high"
        >

        <div class="mc-c11__hero-overlay"></div>

        <div class="contenedor mc-c11__hero-contenido">

            <nav class="mc-c11__migas"
                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">
                    Inicio
                </a>

                <span aria-hidden="true">/</span>

                <a href="{{ route('consultoria.index') }}">
                    Consultoría
                </a>

                <span aria-hidden="true">/</span>

                <span>Solicitar</span>

            </nav>

            <div class="mc-c11__hero-principal">

                <span class="mc-c11__eyebrow mc-c11__eyebrow--claro">
                    <span class="mc-c11__raya"></span>
                    MIL CUMBRES / CONSULTORÍA
                </span>


                <h1>
                    Cuéntanos sobre
                    <em>tu proyecto.</em>
                </h1>

                <p>
                    Cada territorio tiene una historia,
                    necesidades y oportunidades diferentes.
                    Queremos conocer tu organización
                    y entender lo que deseas desarrollar.
                </p>

            </div>

            <div class="mc-c11__hero-pie">

                <span>
                    NATURALEZA · COMUNIDAD · TERRITORIO
                </span>

                <span>
                    SOLICITUD DE ACOMPAÑAMIENTO
                </span>

            </div>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 02 · INTRODUCCIÓN --}}
    {{-- ====================================== --}}

    <section class="mc-c11__intro">

        <div class="contenedor mc-c11__intro-grid">

            <div>

                <span class="mc-c11__eyebrow">
                    <span class="mc-c11__raya"></span>
                    COMENCEMOS A CONVERSAR
                </span>

                <h2>
                    El primer paso
                    es <em>escucharte.</em>
                </h2>

            </div>

            <div>

                <p>
                    Completa la información de tu
                    organización y describe la necesidad
                    que deseas atender. Con estos datos
                    podremos conocer mejor tu propuesta.
                </p>

                <div class="mc-c11__intro-nota">


                    <span>
                        Tus datos de contacto se tomarán
                        de tu cuenta de Mil Cumbres.
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 03 · SOLICITUD --}}
    {{-- ====================================== --}}

    <section class="mc-c11__contenido">

        <div class="contenedor">

            <x-avisos />

            <div class="mc-c11__encabezado-form">

                <div>

                    <span class="mc-c11__eyebrow">
                        <span class="mc-c11__raya"></span>
                        FORMULARIO DE CONSULTORÍA
                    </span>

                    <h2>
                        Solicita nuestro
                        <em>acompañamiento.</em>
                    </h2>

                    <p>
                        Proporciona la información de
                        tu organización y cuéntanos
                        qué te gustaría lograr.
                    </p>

                </div>

                <span class="mc-c11__form-identificador">
                    C11 / MIL CUMBRES
                </span>

            </div>


            {{-- ================================== --}}
            {{-- FORMULARIO PRINCIPAL --}}
            {{-- ================================== --}}

            <form
                method="POST"
                action="{{ route('consultoria.store') }}"
                class="mc-c11__form"
                id="mc-c11-form"
                novalidate
            >

                @csrf

                <div class="mc-c11__layout">

                    {{-- ============================= --}}
                    {{-- COLUMNA PRINCIPAL --}}
                    {{-- ============================= --}}

                    <div class="mc-c11__principal">

                        {{-- NAVEGACIÓN VISUAL --}}

                        <nav class="mc-c11__indice"
                             aria-label="Apartados del formulario">

                            <a href="#mc-c11-tipo">
                                Organización
                            </a>

                            <a href="#mc-c11-datos">
                                Datos generales
                            </a>

                            <a href="#mc-c11-proyecto">
                                Tu proyecto
                            </a>

                        </nav>


                        {{-- ============================= --}}
                        {{-- PASO 01 · TIPO DE ORGANIZACIÓN --}}
                        {{-- ============================= --}}

                        <fieldset class="mc-c11__panel"
                                  id="mc-c11-tipo">

                            <legend class="mc-c11__legend">


                                <span>
                                    Tipo de
                                    <em>organización.</em>
                                </span>

                            </legend>

                            <p class="mc-c11__descripcion">
                                Selecciona el tipo de organización
                                al que pertenece tu proyecto.
                            </p>

                            <div class="mc-c11__tipos">

                                @forelse ($tipos as $tipo)

                                    <label class="mc-c11__tipo">

                                        <input
                                            type="radio"
                                            name="id_tipo_organizacion"
                                            value="{{ $tipo->id_tipo_organizacion }}"
                                            @checked(
                                                (string) old(
                                                    'id_tipo_organizacion',
                                                    $tipoElegido
                                                ) ===
                                                (string) $tipo->id_tipo_organizacion
                                            )
                                            required
                                        >


                                        <span class="mc-c11__tipo-texto">

                                            <strong>
                                                {{ $tipo->nombre }}
                                            </strong>

                                            <small>
                                                Seleccionar organización
                                            </small>

                                        </span>

                                        <span class="mc-c11__tipo-indicador"
                                              aria-hidden="true">
                                            ✓
                                        </span>

                                    </label>

                                @empty

                                    <p class="mc-c11__sin-tipos">
                                        No hay tipos de organización
                                        disponibles para seleccionar.
                                    </p>

                                @endforelse

                            </div>

                            @error('id_tipo_organizacion')

                                <span class="mc-c11__error"
                                      role="alert">
                                    {{ $message }}
                                </span>

                            @enderror

                        </fieldset>


                        {{-- ============================= --}}
                        {{-- PASO 02 · DATOS GENERALES --}}
                        {{-- ============================= --}}

                        <fieldset class="mc-c11__panel"
                                  id="mc-c11-datos">

                            <legend class="mc-c11__legend">


                                <span>
                                    Conozcamos tu
                                    <em>organización.</em>
                                </span>

                            </legend>

                            <p class="mc-c11__descripcion">
                                Comparte el nombre y la ubicación
                                de la organización para identificar
                                correctamente tu solicitud.
                            </p>


                            <div class="mc-c11__campos">

                                {{-- ORGANIZACIÓN --}}

                                <div class="mc-c11__campo">

                                    <label for="organizacion">

                                        Nombre de la organización
                                        <span aria-hidden="true">*</span>

                                    </label>

                                    <input
                                        @class([
                                            'mc-c11__control',
                                            'control',
                                            'es-invalido' =>
                                                $errors->has('organizacion')
                                        ])
                                        type="text"
                                        id="organizacion"
                                        name="organizacion"
                                        value="{{ old('organizacion') }}"
                                        maxlength="120"
                                        autocomplete="organization"
                                        placeholder="Nombre de tu empresa u organización"
                                        @if($errors->has('organizacion'))
                                            aria-invalid="true"
                                            aria-describedby="mc-c11-error-organizacion"
                                        @endif
                                        required
                                    >

                                    @error('organizacion')

                                        <span class="mc-c11__error"
                                              id="mc-c11-error-organizacion"
                                              role="alert">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>


                                {{-- UBICACIÓN --}}

                                <div class="mc-c11__campo">

                                    <label for="ubicacion">

                                        Ubicación
                                        <span aria-hidden="true">*</span>

                                    </label>

                                    <input
                                        @class([
                                            'mc-c11__control',
                                            'control',
                                            'es-invalido' =>
                                                $errors->has('ubicacion')
                                        ])
                                        type="text"
                                        id="ubicacion"
                                        name="ubicacion"
                                        value="{{ old('ubicacion') }}"
                                        maxlength="200"
                                        placeholder="Municipio, estado"
                                        @if($errors->has('ubicacion'))
                                            aria-invalid="true"
                                            aria-describedby="mc-c11-error-ubicacion"
                                        @endif
                                        required
                                    >

                                    <span class="mc-c11__ayuda">
                                        Indica el lugar donde se
                                        brindará la consultoría.
                                    </span>

                                    @error('ubicacion')

                                        <span class="mc-c11__error"
                                              id="mc-c11-error-ubicacion"
                                              role="alert">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>

                            </div>

                        </fieldset>


                        {{-- ============================= --}}
                        {{-- PASO 03 · DESCRIPCIÓN --}}
                        {{-- ============================= --}}

                        <fieldset class="mc-c11__panel"
                                  id="mc-c11-proyecto">

                            <legend class="mc-c11__legend">


                                <span>
                                    Hablemos de
                                    <em>tu proyecto.</em>
                                </span>

                            </legend>

                            <p class="mc-c11__descripcion">
                                Describe tu necesidad,
                                qué deseas lograr y el plazo
                                en el que te gustaría
                                desarrollar la propuesta.
                            </p>


                            <div class="mc-c11__campo">

                                <label for="necesidad">

                                    ¿Qué necesitas?
                                    <span aria-hidden="true">*</span>

                                </label>

                                <textarea
                                    @class([
                                        'mc-c11__control',
                                        'mc-c11__textarea',
                                        'control',
                                        'es-invalido' =>
                                            $errors->has('necesidad')
                                    ])
                                    id="necesidad"
                                    name="necesidad"
                                    rows="7"
                                    maxlength="3000"
                                    placeholder="Cuéntanos sobre tu proyecto, qué te gustaría lograr y en qué plazo..."
                                    @if($errors->has('necesidad'))
                                        aria-invalid="true"
                                        aria-describedby="mc-c11-error-necesidad"
                                    @endif
                                    required
                                >{{ old('necesidad') }}</textarea>

                                <div class="mc-c11__campo-pie">

                                    <span>
                                        Máximo 3000 caracteres.
                                    </span>

                                    <span>
                                        Campo obligatorio
                                    </span>

                                </div>

                                @error('necesidad')

                                    <span class="mc-c11__error"
                                          id="mc-c11-error-necesidad"
                                          role="alert">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            <div class="mc-c11__consejo">


                                <p>
                                    Una descripción clara nos
                                    ayudará a comprender
                                    el contexto de tu organización
                                    y lo que esperas del acompañamiento.
                                </p>

                            </div>

                        </fieldset>


                        {{-- ============================= --}}
                        {{-- ENVÍO DE SOLICITUD --}}
                        {{-- ============================= --}}

                        <div class="mc-c11__enviar">

                            <div class="mc-c11__enviar-texto">

                                <span class="mc-c11__eyebrow">
                                    TU PROPUESTA COMIENZA AQUÍ
                                </span>

                                <h3>
                                    Demos el
                                    <em>primer paso.</em>
                                </h3>

                                <p>
                                    Revisa que la información
                                    sea correcta antes de enviarla.
                                </p>

                            </div>

                            <button
                                type="submit"
                                class="mc-c11__boton"
                            >
                                Enviar solicitud de consultoría

                                <span aria-hidden="true">
                                    ↗
                                </span>
                            </button>

                            <span class="mc-c11__enviar-nota">
                                Los campos marcados con * son obligatorios.
                            </span>

                        </div>

                    </div>


                    {{-- ============================= --}}
                    {{-- PANEL LATERAL --}}
                    {{-- ============================= --}}

                    <aside class="mc-c11__lateral">

                        {{-- DATOS DE CONTACTO --}}

                        <div class="mc-c11__contacto">

                            <span class="mc-c11__contacto-eyebrow">
                                TU INFORMACIÓN DE CONTACTO
                            </span>

                            <h2>
                                Estamos listos
                                para <em>escucharte.</em>
                            </h2>

                            <p>
                                Cuando recibamos tu solicitud,
                                utilizaremos el correo electrónico
                                de tu cuenta para contactarte.
                            </p>

                            <div class="mc-c11__correo">

                                <span class="mc-c11__correo-icono"
                                      aria-hidden="true">
                                    @
                                </span>

                                <div>

                                    <small>
                                        CORREO DE CONTACTO
                                    </small>

                                    <strong>
                                        {{ auth()->user()->email }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- ETAPAS INFORMATIVAS --}}

                        <div class="mc-c11__siguientes">

                            <span class="mc-c11__eyebrow">
                                DESPUÉS DE ENVIAR
                            </span>

                            <h3>
                                ¿Qué
                                <em>sigue?</em>
                            </h3>

                            <ol class="mc-c11__pasos">

                                <li>


                                    <div>

                                        <strong>
                                            Recibimos tu solicitud
                                        </strong>

                                        <p>
                                            Quedará registrada la
                                            información que nos compartas.
                                        </p>

                                    </div>

                                </li>

                                <li>


                                    <div>

                                        <strong>
                                            Revisamos tu necesidad
                                        </strong>

                                        <p>
                                            Conoceremos el contexto
                                            de tu organización y
                                            la propuesta presentada.
                                        </p>

                                    </div>

                                </li>

                                <li>


                                    <div>

                                        <strong>
                                            Damos seguimiento
                                        </strong>

                                        <p>
                                            Podrás consultar el estado
                                            de tu consultoría desde
                                            tu cuenta.
                                        </p>

                                    </div>

                                </li>

                            </ol>

                        </div>


                        {{-- ENLACE INFORMATIVO --}}

                        <div class="mc-c11__modelo">


                            <strong>
                                Un enfoque que considera
                                al territorio.
                            </strong>

                            <p>
                                Conoce la visión de
                                turismo regenerativo
                                que impulsa Mil Cumbres.
                            </p>

                            <a href="{{ route('modelo') }}"
                               target="_blank"
                               rel="noopener noreferrer">

                                Nuestro modelo
                                <span aria-hidden="true">↗</span>

                            </a>

                        </div>

                    </aside>

                </div>

            </form>

        </div>

    </section>

</div>

@endsection
