
{{-- C12 · Acuse de consultoría
     Tablas: solicitud_consultorias, organizaciones,
     tipo_organizaciones
--}}

@extends('layouts.app')

@section('titulo', 'Consultoría solicitada')

@section('descripcion',
    'Consulta el número de seguimiento y la información de tu solicitud de consultoría en Mil Cumbres.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/acuse-consultoria-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    $folio = 'CON-' . str_pad(
        $consultoria->id_solicitud_consultoria,
        5,
        '0',
        STR_PAD_LEFT
    );
@endphp

<div class="mc-c12">

    {{-- ====================================== --}}
    {{-- 01 · PORTADA FOTOGRÁFICA --}}
    {{-- ====================================== --}}

    <section class="mc-c12__hero">

        <img
            src="{{ config('milcumbres.imagenes.consultoria') }}"
            class="mc-c12__hero-img"
            alt="Paisaje natural representativo del territorio"
            fetchpriority="high"
        >

        <div class="mc-c12__hero-overlay"></div>

        <div class="contenedor mc-c12__hero-contenido">

            <nav class="mc-c12__migas"
                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">
                    Inicio
                </a>

                <span aria-hidden="true">/</span>

                <a href="{{ route('consultoria.index') }}">
                    Consultoría
                </a>

                <span aria-hidden="true">/</span>

                <span>Solicitud recibida</span>

            </nav>

            <div class="mc-c12__hero-principal">

                <span class="mc-c12__eyebrow mc-c12__eyebrow--claro">
                    <span class="mc-c12__raya"></span>
                    MIL CUMBRES / CONSULTORÍA
                </span>


                <h1>
                    El primer paso
                    <em>ya está dado.</em>
                </h1>

                <p>
                    Hemos recibido tu solicitud de consultoría.
                    Gracias por compartir con nosotros
                    las necesidades de tu organización
                    y tu interés en desarrollar
                    un proyecto relacionado con el territorio.
                </p>

            </div>

            <div class="mc-c12__hero-pie">

                <span>
                    NATURALEZA · COMUNIDAD · TERRITORIO
                </span>

                <span>
                    ACUSE DE SOLICITUD
                </span>

            </div>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 02 · COMPROBANTE --}}
    {{-- ====================================== --}}

    <section class="mc-c12__seccion">

        <div class="contenedor">

            <div class="mc-c12__comprobante">

                {{-- ============================== --}}
                {{-- CONFIRMACIÓN DE RECEPCIÓN --}}
                {{-- ============================== --}}

                <div class="mc-c12__confirmacion">

                    <span class="mc-c12__icono-exito"
                          aria-hidden="true">

                        <svg
                            width="39"
                            height="39"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12.5l4.5 4.5L19 7.5"/>
                        </svg>

                    </span>

                    <div class="mc-c12__confirmacion-texto">

                        <span class="mc-c12__eyebrow">
                            01 / SOLICITUD REGISTRADA
                        </span>

                        <h2>
                            Recibimos tu solicitud
                            <em>correctamente.</em>
                        </h2>

                        <p>
                            Tu información ha quedado registrada
                            para su revisión. Conserva el número
                            de seguimiento que aparece a continuación.
                        </p>

                    </div>

                    <div class="mc-c12__sello"
                         aria-hidden="true">


                        <strong>
                            MIL CUMBRES
                        </strong>

                        <small>
                            CONSULTORÍA
                        </small>

                    </div>

                </div>


                {{-- ============================== --}}
                {{-- CUERPO DEL COMPROBANTE --}}
                {{-- ============================== --}}

                <div class="mc-c12__grid">

                    {{-- COLUMNA PRINCIPAL --}}

                    <div class="mc-c12__principal">

                        <div class="mc-c12__encabezado">

                            <span class="mc-c12__eyebrow">
                                INFORMACIÓN DE TU SOLICITUD
                            </span>

                            <h2>
                                Tu comprobante
                                <em>de consultoría.</em>
                            </h2>

                        </div>


                        {{-- ========================== --}}
                        {{-- NÚMERO DE SEGUIMIENTO --}}
                        {{-- ========================== --}}

                        <div class="mc-c12__folio">

                            <div class="mc-c12__folio-superior">

                                <span>
                                    NÚMERO DE SEGUIMIENTO
                                </span>


                            </div>

                            <strong
                                class="mc-c12__folio-numero"
                                id="mc-c12-folio"
                            >
                                {{ $folio }}
                            </strong>

                            <div class="mc-c12__folio-inferior">

                                <span>
                                    Guárdalo para identificar
                                    tu solicitud.
                                </span>

                                <button
                                    type="button"
                                    class="mc-c12__copiar"
                                    id="mc-c12-copiar"
                                    aria-label="Copiar número de seguimiento"
                                >
                                    <span aria-hidden="true">⧉</span>
                                    Copiar folio
                                </button>

                            </div>

                            <p
                                id="mc-c12-copiado"
                                class="mc-c12__copiado"
                                role="status"
                                aria-live="polite"
                            ></p>

                        </div>


                        {{-- ========================== --}}
                        {{-- DATOS DE ORGANIZACIÓN --}}
                        {{-- ========================== --}}

                        <div class="mc-c12__datos-titulo">

                            <span class="mc-c12__eyebrow">
                                DATOS REGISTRADOS
                            </span>


                        </div>

                        <dl class="mc-c12__datos">

                            <div class="mc-c12__dato
                                        mc-c12__dato--ancho">

                                <dt>
                                    ORGANIZACIÓN
                                </dt>

                                <dd>
                                    {{ $consultoria->organizacion?->nombre
                                        ?? 'No disponible' }}
                                </dd>

                            </div>

                            <div class="mc-c12__dato">

                                <dt>
                                    TIPO DE ORGANIZACIÓN
                                </dt>

                                <dd>
                                    {{ $consultoria->organizacion?->tipo?->nombre
                                        ?? 'No disponible' }}
                                </dd>

                            </div>

                            <div class="mc-c12__dato">

                                <dt>
                                    UBICACIÓN
                                </dt>

                                <dd>
                                    {{ $consultoria->ubicacion }}
                                </dd>

                            </div>

                            <div class="mc-c12__dato
                                        mc-c12__dato--ancho
                                        mc-c12__dato--estado">

                                <dt>
                                    ESTADO ACTUAL DE LA CONSULTORÍA
                                </dt>

                                <dd>
                                    <x-estado :valor="$consultoria->estado" />
                                </dd>

                            </div>

                        </dl>


                        {{-- ========================== --}}
                        {{-- MENSAJE IMPORTANTE --}}
                        {{-- ========================== --}}

                        <div class="mc-c12__aviso">

                            <span class="mc-c12__aviso-icono"
                                  aria-hidden="true">
                                i
                            </span>

                            <div>

                                <strong>
                                    Tu consultoría está pendiente
                                    de revisión.
                                </strong>

                                <p>
                                    Este comprobante confirma
                                    que recibimos tu solicitud.
                                    No representa la aprobación
                                    de una propuesta, la asignación
                                    de un consultor ni la programación
                                    de una visita.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ============================== --}}
                    {{-- 03 · SIGUIENTES PASOS --}}
                    {{-- ============================== --}}

                    <aside class="mc-c12__siguientes">

                        <span class="mc-c12__eyebrow
                                     mc-c12__eyebrow--claro">
                            02 / EL PROCESO CONTINÚA
                        </span>

                        <h2>
                            ¿Qué sucede
                            <em>después?</em>
                        </h2>

                        <p class="mc-c12__siguientes-intro">
                            Estos son los pasos previstos
                            para dar continuidad
                            a tu solicitud de consultoría.
                        </p>


                        <ol class="mc-c12__pasos">

                            {{-- PASO 01 --}}

                            <li class="mc-c12__paso">

                                <span class="mc-c12__paso-numero">
                                    01
                                </span>

                                <div>

                                    <span class="mc-c12__paso-etiqueta">
                                        REVISIÓN
                                    </span>

                                    <h3>
                                        Revisamos tu necesidad
                                    </h3>

                                    <p>
                                        Un consultor revisará
                                        la información de tu proyecto
                                        en los próximos 2 días hábiles.
                                    </p>

                                </div>

                            </li>


                            {{-- PASO 02 --}}

                            <li class="mc-c12__paso">

                                <span class="mc-c12__paso-numero">
                                    02
                                </span>

                                <div>

                                    <span class="mc-c12__paso-etiqueta">
                                        CONTACTO
                                    </span>

                                    <h3>
                                        Nos comunicamos contigo
                                    </h3>

                                    <p>
                                        Coordinaremos una llamada
                                        o visita al lugar para conocer
                                        mejor las necesidades
                                        de tu organización.
                                    </p>

                                </div>

                            </li>


                            {{-- PASO 03 --}}

                            <li class="mc-c12__paso">

                                <span class="mc-c12__paso-numero">
                                    03
                                </span>

                                <div>

                                    <span class="mc-c12__paso-etiqueta">
                                        PROPUESTA
                                    </span>

                                    <h3>
                                        Presentamos una propuesta
                                    </h3>

                                    <p>
                                        Recibirás un plan de trabajo
                                        que describa el alcance
                                        y los costos del acompañamiento.
                                    </p>

                                </div>

                            </li>

                        </ol>


                        <div class="mc-c12__siguientes-cierre">


                            <p>
                                Todo gran proyecto
                                comienza por
                                <em>escuchar al territorio.</em>
                            </p>

                        </div>

                    </aside>

                </div>


                {{-- ================================= --}}
                {{-- 04 · ACCIONES DEL USUARIO --}}
                {{-- ================================= --}}

                <div class="mc-c12__acciones">

                    <div>

                        <span class="mc-c12__eyebrow">
                            03 / CONTINÚA EN MIL CUMBRES
                        </span>

                        <h2>
                            Seguimos
                            <em>en contacto.</em>
                        </h2>

                        <p>
                            Puedes consultar el seguimiento
                            de tus consultorías desde
                            tu espacio personal.
                        </p>

                    </div>


                    <div class="mc-c12__botones">

                        <a
                            href="{{ route('solicitudes.index', ['seccion' => 'consultorias']) }}#mis-consultorias"
                            class="mc-c12__boton
                                   mc-c12__boton--verde"
                        >

                            Ver mis consultorías

                            <span aria-hidden="true">↗</span>

                        </a>


                        <a
                            href="{{ route('inicio') }}"
                            class="mc-c12__boton
                                   mc-c12__boton--borde"
                        >

                            Volver al inicio

                            <span aria-hidden="true">→</span>

                        </a>


                        <a
                            href="{{ route('experiencias.index') }}"
                            class="mc-c12__enlace"
                        >

                            Explorar experiencias
                            <span aria-hidden="true">↗</span>

                        </a>

                    </div>

                </div>

            </div>


            {{-- ================================= --}}
            {{-- 05 · MENSAJE FINAL --}}
            {{-- ================================= --}}

            <div class="mc-c12__despedida">


                <p>
                    Gracias por imaginar
                    un futuro diferente
                    <em>para tu territorio.</em>
                </p>

                <small>
                    MIL CUMBRES · TURISMO REGENERATIVO
                </small>

            </div>

        </div>

    </section>

</div>

@endsection


{{-- ====================================== --}}
{{-- COPIAR NÚMERO DE SEGUIMIENTO --}}
{{-- ====================================== --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const boton = document.getElementById('mc-c12-copiar');

    const elementoFolio = document.getElementById(
        'mc-c12-folio'
    );

    const mensaje = document.getElementById(
        'mc-c12-copiado'
    );

    if (!boton || !elementoFolio || !mensaje) {
        return;
    }

    boton.addEventListener('click', async function () {

        const folio = elementoFolio.textContent.trim();

        try {

            if (
                navigator.clipboard &&
                window.isSecureContext
            ) {

                await navigator.clipboard.writeText(folio);

            } else {

                const temporal = document.createElement(
                    'textarea'
                );

                temporal.value = folio;
                temporal.style.position = 'fixed';
                temporal.style.opacity = '0';

                document.body.appendChild(temporal);

                temporal.select();

                const copiado = document.execCommand('copy');

                temporal.remove();

                if (!copiado) {
                    throw new Error('No se pudo copiar');
                }

            }

            mensaje.textContent =
                'Folio copiado correctamente.';

        } catch (error) {

            mensaje.textContent =
                'No se pudo copiar automáticamente. ' +
                'Selecciona el folio para copiarlo.';

        }

    });

});
</script>

@endpush
