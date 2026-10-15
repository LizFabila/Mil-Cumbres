
{{-- Aviso de privacidad · Mil Cumbres
     Texto provisional sujeto a revisión legal.
--}}

@extends('layouts.app')

@section('titulo', 'Aviso de privacidad')

@section('descripcion',
    'Información sobre el tratamiento de datos personales en Mil Cumbres.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/privacidad-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-privacidad">

    {{-- ======================================= --}}
    {{-- 01 · PORTADA --}}
    {{-- ======================================= --}}

    <section class="priv-hero">

        <div class="contenedor priv-hero__grid">

            <div class="priv-hero__contenido">

                <span class="priv-eyebrow">
                    <span class="priv-eyebrow__linea"></span>
                    MIL CUMBRES / TRANSPARENCIA
                </span>

                <span class="priv-hero__capitulo">
                    INFORMACIÓN Y PROTECCIÓN DE DATOS
                </span>

                <h1>
                    Tu confianza
                    <em>también es parte</em>
                    del camino.
                </h1>

                <p>
                    Conoce cómo se utiliza la información
                    que proporcionas al solicitar experiencias
                    y consultorías en Mil Cumbres.
                </p>

                <a href="#aviso-contenido"
                   class="priv-boton">

                    Leer aviso de privacidad

                    <span aria-hidden="true">↘</span>

                </a>

                <div class="priv-hero__pie">

                    <span class="priv-hero__asterisco"
                          aria-hidden="true">
                        ✳
                    </span>

                    <span>
                        MIL CUMBRES<br>
                        TURISMO REGENERATIVO
                    </span>

                </div>

            </div>


            {{-- FOTOGRAFÍA EDITORIAL --}}

            <div class="priv-hero__visual">

                <img
                    src="{{ config('milcumbres.imagenes.modelo') }}"
                    alt="Paisaje natural de Valle de Bravo"
                    fetchpriority="high"
                >

                <div class="priv-hero__visual-overlay"></div>

                <div class="priv-hero__visual-texto">

                    <span>VALLE DE BRAVO · MÉXICO</span>

                    <strong>
                        Una relación basada
                        en la confianza.
                    </strong>

                </div>

                <span class="priv-hero__visual-numero"
                      aria-hidden="true">
                    MC.
                </span>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- 02 · CONTENIDO DEL AVISO --}}
    {{-- ======================================= --}}

    <section class="priv-documento" id="aviso-contenido">

        <div class="contenedor priv-documento__grid">

            {{-- ÍNDICE LATERAL --}}

            <aside class="priv-indice">

                <span class="priv-eyebrow">
                    <span class="priv-eyebrow__linea"></span>
                    CONTENIDO
                </span>

                <h2>
                    Información
                    <em>importante.</em>
                </h2>

                <nav aria-label="Secciones del aviso de privacidad">

                    <a href="#priv-responsable">
                        <span>01</span>
                        Responsable y datos
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a href="#priv-finalidades">
                        <span>02</span>
                        Uso de la información
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a href="#priv-derechos">
                        <span>03</span>
                        Tus derechos
                        <span aria-hidden="true">↗</span>
                    </a>

                </nav>

                <div class="priv-indice__detalle">

                    <span aria-hidden="true">✳</span>

                    <p>
                        Un espacio para conocer
                        cómo se utiliza la información
                        que compartes con nosotros.
                    </p>

                </div>

            </aside>


            {{-- INFORMACIÓN PRINCIPAL --}}

            <div class="priv-contenido">

                {{-- AVISO PROVISIONAL --}}

                <div class="priv-alerta" role="note">

                    <div class="priv-alerta__icono"
                         aria-hidden="true">
                        !
                    </div>

                    <div>

                        <strong>
                            Aviso de privacidad provisional
                        </strong>

                        <p>
                            El presente contenido es informativo
                            y se encuentra pendiente de revisión
                            y sustitución por el aviso de privacidad
                            legal definitivo.
                        </p>

                    </div>

                </div>


                {{-- ================================= --}}
                {{-- RESPONSABLE Y DATOS --}}
                {{-- ================================= --}}

                <article class="priv-seccion"
                         id="priv-responsable">

                    <div class="priv-seccion__encabezado">

                        <span class="priv-seccion__numero">
                            01 / 03
                        </span>

                        <span class="priv-seccion__categoria">
                            RESPONSABLE DE LA INFORMACIÓN
                        </span>

                    </div>

                    <h2>
                        ¿Qué información
                        <em>nos proporcionas?</em>
                    </h2>

                    <p>
                        Mil Cumbres, con domicilio en
                        <strong>
                            {{ config('milcumbres.contacto.lugar') }}
                        </strong>,
                        es responsable del tratamiento
                        de los datos personales
                        que nos proporcionas.
                    </p>

                    <p>
                        La información personal mencionada
                        en este aviso comprende los
                        siguientes datos:
                    </p>

                    <div class="priv-datos">

                        <div class="priv-dato">
                            <span class="priv-dato__icono"
                                  aria-hidden="true">
                                01
                            </span>

                            <span>
                                Nombre
                            </span>
                        </div>

                        <div class="priv-dato">
                            <span class="priv-dato__icono"
                                  aria-hidden="true">
                                02
                            </span>

                            <span>
                                Correo electrónico
                            </span>
                        </div>

                        <div class="priv-dato">
                            <span class="priv-dato__icono"
                                  aria-hidden="true">
                                03
                            </span>

                            <span>
                                Teléfono
                            </span>
                        </div>

                    </div>

                </article>


                {{-- ================================= --}}
                {{-- FINALIDADES --}}
                {{-- ================================= --}}

                <article class="priv-seccion"
                         id="priv-finalidades">

                    <div class="priv-seccion__encabezado">

                        <span class="priv-seccion__numero">
                            02 / 03
                        </span>

                        <span class="priv-seccion__categoria">
                            FINALIDADES DEL TRATAMIENTO
                        </span>

                    </div>

                    <h2>
                        ¿Para qué utilizamos
                        <em>tus datos?</em>
                    </h2>

                    <p>
                        Utilizamos los datos personales
                        que nos proporcionas únicamente
                        para gestionar tus solicitudes
                        de experiencias y de consultoría,
                        confirmar reservaciones
                        y validar pagos.
                    </p>

                    <div class="priv-finalidades">

                        <div class="priv-finalidad">

                            <span class="priv-finalidad__numero">
                                01
                            </span>

                            <div>
                                <h3>
                                    Gestión de solicitudes
                                </h3>

                                <p>
                                    Administrar las solicitudes
                                    de experiencias turísticas
                                    y consultorías.
                                </p>
                            </div>

                        </div>

                        <div class="priv-finalidad">

                            <span class="priv-finalidad__numero">
                                02
                            </span>

                            <div>
                                <h3>
                                    Confirmación de reservaciones
                                </h3>

                                <p>
                                    Gestionar la confirmación
                                    de las reservaciones
                                    solicitadas.
                                </p>
                            </div>

                        </div>

                        <div class="priv-finalidad">

                            <span class="priv-finalidad__numero">
                                03
                            </span>

                            <div>
                                <h3>
                                    Validación de pagos
                                </h3>

                                <p>
                                    Revisar la información
                                    necesaria para validar
                                    los pagos registrados.
                                </p>
                            </div>

                        </div>

                    </div>

                </article>


                {{-- ================================= --}}
                {{-- DERECHOS ARCO --}}
                {{-- ================================= --}}

                <article class="priv-seccion"
                         id="priv-derechos">

                    <div class="priv-seccion__encabezado">

                        <span class="priv-seccion__numero">
                            03 / 03
                        </span>

                        <span class="priv-seccion__categoria">
                            DERECHOS SOBRE TUS DATOS
                        </span>

                    </div>

                    <h2>
                        Tu información.
                        <em>Tus derechos.</em>
                    </h2>

                    <p>
                        Puedes ejercer tus derechos
                        de acceso, rectificación,
                        cancelación u oposición
                        sobre tus datos personales.
                    </p>

                    <div class="priv-derechos-grid">

                        <div>
                            <span>A</span>
                            <strong>Acceso</strong>
                        </div>

                        <div>
                            <span>R</span>
                            <strong>Rectificación</strong>
                        </div>

                        <div>
                            <span>C</span>
                            <strong>Cancelación</strong>
                        </div>

                        <div>
                            <span>O</span>
                            <strong>Oposición</strong>
                        </div>

                    </div>

                    <p>
                        Para ejercer cualquiera de estos
                        derechos, puedes escribir a
                        nuestro correo de contacto:
                    </p>

                    <div class="priv-contacto">

                        <span class="priv-eyebrow">
                            CONTACTO DE PRIVACIDAD
                        </span>

                        <h3>
                            ¿Necesitas comunicarte
                            con nosotros?
                        </h3>

                        <p>
                            Escríbenos para presentar
                            tu solicitud relacionada
                            con tus datos personales.
                        </p>

                        <a
                            href="mailto:{{ config('milcumbres.contacto.correo') }}"
                            class="priv-contacto__enlace"
                        >

                            <span>
                                {{ config('milcumbres.contacto.correo') }}
                            </span>

                            <span aria-hidden="true">
                                ↗
                            </span>

                        </a>

                    </div>

                </article>

            </div>

        </div>

    </section>


    {{-- ======================================= --}}
    {{-- 03 · CIERRE --}}
    {{-- ======================================= --}}

    <section class="priv-cierre">

        <div class="contenedor priv-cierre__contenido">

            <span class="priv-eyebrow priv-eyebrow--claro">
                ✳ MIL CUMBRES
            </span>

            <h2>
                La confianza también
                <em>se construye con transparencia.</em>
            </h2>

            <p>
                Gracias por conocer la información
                relacionada con el tratamiento
                de datos personales en Mil Cumbres.
            </p>

            <a href="{{ route('inicio') }}"
               class="priv-boton priv-boton--claro">

                Volver al inicio

                <span aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

</div>

@endsection
