
{{-- C07 · Acuse de solicitud
     Tablas: solicitudes, asigna_experiencia, experiencias
--}}

@extends('layouts.app')

@section('titulo', 'Solicitud enviada')

@section('descripcion',
    'Consulta el comprobante y folio de tu solicitud de experiencias en Mil Cumbres.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/acuse-milcumbres.css') }}">
<link rel="stylesheet"
      href="{{ asset('css/acuse-celebracion-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-acuse" data-celebrar="{{ $solicitud->folio }}">

    {{-- ====================================== --}}
    {{-- 01 · PORTADA FOTOGRÁFICA --}}
    {{-- ====================================== --}}

    <section class="mc-acuse-hero">

        <img
            class="mc-acuse-hero__imagen"
            src="{{ config('milcumbres.imagenes.hero_inicio_local') ? asset(config('milcumbres.imagenes.hero_inicio_local')) : config('milcumbres.imagenes.hero_inicio') }}"
            alt="Paisaje natural de Valle de Bravo"
            fetchpriority="high"
        >

        <div class="mc-acuse-hero__overlay"></div>

        <div class="contenedor mc-acuse-hero__contenido">

            <nav class="mc-acuse-hero__migas"
                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">
                    Inicio
                </a>

                <span>/</span>

                <a href="{{ route('solicitudes.index') }}">
                    Mis solicitudes
                </a>

                <span>/</span>

                <span>Comprobante</span>

            </nav>


            <span class="mc-acuse-eyebrow mc-acuse-eyebrow--claro">


                MIL CUMBRES · TU PRÓXIMA EXPERIENCIA

            </span>

            <h1>
                ¡Recibimos
                <em>tu solicitud!</em>
            </h1>

            <p>
                El primer paso de tu próxima aventura
                ya está hecho. Conserva tu folio
                y consulta los siguientes pasos
                para continuar con tu reservación.
            </p>

            <span class="mc-acuse-hero__detalle">
                NATURALEZA · BIENESTAR · CULTURA
            </span>

        </div>


    </section>



    {{-- ====================================== --}}
    {{-- 02 · COMPROBANTE PRINCIPAL --}}
    {{-- ====================================== --}}

    <section class="mc-acuse-contenido">

        <div class="contenedor">

            <div class="mc-acuse-comprobante">

                {{-- ENCABEZADO DEL COMPROBANTE --}}

                <div class="mc-acuse-comprobante__header">

                    <div class="mc-acuse-comprobante__exito">

                        <span class="mc-acuse-comprobante__icono"
                              aria-hidden="true">

                            <svg
                                width="35"
                                height="35"
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

                        <div>

                            <span class="mc-acuse-eyebrow">
                                SOLICITUD REGISTRADA
                            </span>

                            <h2>
                                Tu solicitud fue
                                <em>enviada correctamente.</em>
                            </h2>

                            <p>
                                Hemos recibido la información
                                de tu visita. Ahora corresponde
                                revisar la disponibilidad
                                antes de confirmar la reservación.
                            </p>

                        </div>

                    </div>

                    <span class="mc-acuse-comprobante__sello">
                        MIL CUMBRES
                    </span>

                </div>


                {{-- CUERPO EN DOS COLUMNAS --}}

                <div class="mc-acuse-comprobante__grid">

                    {{-- ================================= --}}
                    {{-- DATOS DE LA SOLICITUD --}}
                    {{-- ================================= --}}

                    <div class="mc-acuse-resumen">

                        <div class="mc-acuse-resumen__titulo">

                            <span class="mc-acuse-eyebrow">
                                DETALLES DE TU SOLICITUD
                            </span>

                            <h3>
                                Tu comprobante
                                <em>de viaje.</em>
                            </h3>

                        </div>


                        {{-- FOLIO DESTACADO --}}

                        <div class="mc-acuse-folio">

                            <div class="mc-acuse-folio__superior">

                                <span>
                                    NÚMERO DE FOLIO
                                </span>


                            </div>

                            <strong
                                class="mc-acuse-folio__numero"
                                id="mc-acuse-numero-folio"
                            >
                                {{ $solicitud->folio }}
                            </strong>

                            <div class="mc-acuse-folio__inferior">

                                <span>
                                    Guárdalo para consultar
                                    el seguimiento.
                                </span>

                                <button
                                    type="button"
                                    class="mc-acuse-folio__copiar"
                                    id="mc-acuse-copiar"
                                >
                                    <span aria-hidden="true">⧉</span>
                                    Copiar folio
                                </button>

                            </div>

                            <span
                                class="mc-acuse-folio__mensaje"
                                id="mc-acuse-copiado"
                                role="status"
                                aria-live="polite"
                            ></span>

                        </div>


                        {{-- DATOS PRINCIPALES --}}

                        <dl class="mc-acuse-datos">

                            <div class="mc-acuse-dato">

                                <dt>
                                    <span aria-hidden="true">◷</span>
                                    FECHA DE REFERENCIA DE LA SOLICITUD
                                </dt>

                                <dd>
                                    {{ $solicitud->fecha_deseada->translatedFormat('l j \d\e F \d\e Y') }}
                                </dd>

                            </div>


                            <div class="mc-acuse-dato">

                                <dt>
                                    <span aria-hidden="true">♧</span>
                                    PARTICIPANTES
                                </dt>

                                <dd>
                                    Según cada experiencia
                                </dd>

                            </div>


                            <div class="mc-acuse-dato
                                        mc-acuse-dato--total">

                                <dt>
                                    TOTAL ESTIMADO
                                </dt>

                                <dd>
                                    ${{ number_format($solicitud->total, 2) }}
                                    <small>MXN</small>
                                </dd>

                            </div>

                        </dl>


                        {{-- EXPERIENCIAS INCLUIDAS --}}

                        <div class="mc-acuse-experiencias">

                            <div class="mc-acuse-experiencias__encabezado">

                                <span class="mc-acuse-eyebrow">
                                    EXPERIENCIAS SELECCIONADAS
                                </span>

                                <span class="mc-acuse-experiencias__conteo">
                                    {{ $solicitud->experiencias->count() }}
                                </span>

                            </div>

                            <ul>

                                @forelse ($solicitud->experiencias as $experiencia)

                                    <li>


                                        <span>
                                            {{ $experiencia->nombre }}
                                            @php
                                                $fechaExperiencia = $experiencia->pivot->fecha_deseada
                                                    ?? $solicitud->fecha_deseada;
                                            @endphp
                                            @if ($fechaExperiencia)
                                                <small style="display:block; margin-top:4px;">
                                                    Fecha: {{ \Carbon\Carbon::parse($fechaExperiencia)->translatedFormat('l j \d\e F \d\e Y') }}
                                                </small>
                                            @endif
                                            @php
                                                $personasExperiencia = (int) ($experiencia->pivot->cant_persona ?? $solicitud->cant_persona);
                                            @endphp
                                            <small style="display:block; margin-top:4px;">
                                                {{ $personasExperiencia }}
                                                {{ $personasExperiencia == 1 ? 'persona' : 'personas' }}
                                                · ${{ number_format((float) $experiencia->precio * $personasExperiencia, 2) }} MXN
                                            </small>
                                            @if ($experiencia->pivot->nota)
                                                <small style="display:block; margin-top:4px;">
                                                    Nota: {{ $experiencia->pivot->nota }}
                                                </small>
                                            @endif
                                        </span>

                                        <span
                                            class="mc-acuse-experiencias__check"
                                            aria-hidden="true"
                                        >
                                            ✓
                                        </span>

                                    </li>

                                @empty

                                    <li>
                                        No hay experiencias asociadas
                                        a esta solicitud.
                                    </li>

                                @endforelse

                            </ul>

                        </div>

                    </div>


                    {{-- ================================= --}}
                    {{-- SIGUIENTES PASOS --}}
                    {{-- ================================= --}}

                    <aside class="mc-acuse-siguientes">

                        <span class="mc-acuse-eyebrow
                                     mc-acuse-eyebrow--claro">

                            EL CAMINO CONTINÚA

                        </span>

                        <h3>
                            ¿Qué pasa
                            <em>después?</em>
                        </h3>

                        <p class="mc-acuse-siguientes__intro">
                            Estos son los pasos que siguen
                            después de registrar tu solicitud.
                        </p>


                        <ol class="mc-acuse-pasos">

                            <li class="mc-acuse-paso">


                                <div>

                                    <span class="mc-acuse-paso__etiqueta">
                                        PRIMER PASO
                                    </span>

                                    <h4>
                                        Registras tu pago
                                    </h4>

                                    <p>
                                        Cuando la reservación
                                        esté confirmada, podrás
                                        subir tu comprobante
                                        desde «Mis solicitudes».
                                    </p>

                                </div>

                            </li>


                            <li class="mc-acuse-paso">


                                <div>

                                    <span class="mc-acuse-paso__etiqueta">
                                        SEGUNDO PASO
                                    </span>

                                    <h4>
                                        ¡A disfrutar la montaña!
                                    </h4>

                                    <p>
                                        Te enviaremos el punto
                                        de encuentro para que
                                        prepares tu visita.
                                    </p>

                                </div>

                            </li>

                        </ol>


                        <div class="mc-acuse-siguientes__cierre">


                            <p>
                                Tu aventura comienza
                                mucho antes de llegar
                                al destino.
                            </p>

                        </div>

                    </aside>

                </div>


                {{-- ================================= --}}
                {{-- RECORDATORIO IMPORTANTE --}}
                {{-- ================================= --}}

                <div class="mc-acuse-importante">

                    <span class="mc-acuse-importante__icono"
                          aria-hidden="true">
                        i
                    </span>

                    <div>

                        <strong>
                            Tu reservación aún no está confirmada
                        </strong>

                        <p>
                            Este comprobante confirma la recepción
                            de tu solicitud, pero no garantiza
                            la fecha ni los lugares disponibles.
                            Espera la confirmación antes
                            de realizar el pago.
                        </p>

                    </div>

                </div>


                {{-- ================================= --}}
                {{-- PREPÁRATE PARA TU VISITA --}}
                {{-- ================================= --}}

                @php
                    $conRequisitos = $solicitud->experiencias
                        ->filter(fn ($e) => filled($e->requisitos));
                @endphp

                <section class="mc-acuse-prepara"
                         aria-labelledby="mc-acuse-prepara-titulo">

                    <div class="mc-acuse-prepara__encabezado">

                        <span class="mc-acuse-eyebrow">
                            ANTES DE SALIR
                        </span>

                        <h3 id="mc-acuse-prepara-titulo">
                            Prepárate para
                            <em>tu visita.</em>
                        </h3>

                        <p>
                            Las experiencias se realizan al aire libre y en zona
                            de montaña. Con esta lista llegarás listo para
                            disfrutar con seguridad.
                        </p>

                    </div>

                    <ul class="mc-acuse-prepara__lista">

                        <li>
                            <span class="mc-acuse-prepara__icono" aria-hidden="true">🥾</span>
                            <div>
                                <strong>Botas o calzado de montaña</strong>
                                <p>Con suela antiderrapante y que ya hayas usado antes. Evita sandalias y zapatos nuevos.</p>
                            </div>
                        </li>

                        <li>
                            <span class="mc-acuse-prepara__icono" aria-hidden="true">🧥</span>
                            <div>
                                <strong>Ropa en capas</strong>
                                <p>Las mañanas en la montaña son frías y el clima cambia rápido. Lleva chamarra ligera e impermeable.</p>
                            </div>
                        </li>

                        <li>
                            <span class="mc-acuse-prepara__icono" aria-hidden="true">💧</span>
                            <div>
                                <strong>Agua y algo de comer</strong>
                                <p>Al menos 1 litro de agua por persona y un snack ligero. Usa botella reutilizable.</p>
                            </div>
                        </li>

                        <li>
                            <span class="mc-acuse-prepara__icono" aria-hidden="true">🧢</span>
                            <div>
                                <strong>Protección solar</strong>
                                <p>Gorra o sombrero, lentes de sol y bloqueador. A esta altitud el sol quema más.</p>
                            </div>
                        </li>

                        <li>
                            <span class="mc-acuse-prepara__icono" aria-hidden="true">🎒</span>
                            <div>
                                <strong>Mochila pequeña</strong>
                                <p>Que te deje las manos libres. Incluye repelente, medicamentos personales y una bolsa para tu basura.</p>
                            </div>
                        </li>

                        <li>
                            <span class="mc-acuse-prepara__icono" aria-hidden="true">🪪</span>
                            <div>
                                <strong>Identificación y tu folio</strong>
                                <p>Ten a la mano tu folio <b>{{ $solicitud->folio }}</b> y una identificación. Llega 15 minutos antes.</p>
                            </div>
                        </li>

                    </ul>

                    @if ($conRequisitos->isNotEmpty())

                        <div class="mc-acuse-prepara__requisitos">

                            <strong class="mc-acuse-prepara__subtitulo">
                                Requisitos de tus experiencias
                            </strong>

                            <ul>
                                @foreach ($conRequisitos as $exp)
                                    <li>
                                        <span>{{ $exp->nombre }}</span>
                                        <p>{{ $exp->requisitos }}</p>
                                    </li>
                                @endforeach
                            </ul>

                        </div>

                    @endif

                    <p class="mc-acuse-prepara__nota">
                        <span aria-hidden="true">🌱</span>
                        Cuidamos la montaña juntos: no dejes basura, no salgas
                        de los senderos y no alimentes a la fauna.
                    </p>

                </section>


                {{-- ================================= --}}
                {{-- ACCIONES FINALES --}}
                {{-- ================================= --}}

                <div class="mc-acuse-acciones">

                    <div class="mc-acuse-acciones__texto">

                        <span class="mc-acuse-eyebrow">
                            CONTINÚA TU EXPERIENCIA
                        </span>

                        <h3>
                            ¿Qué quieres
                            <em>hacer ahora?</em>
                        </h3>

                    </div>

                    <div class="mc-acuse-acciones__botones">

                        <a
                            href="{{ route('solicitudes.index') }}"
                            class="mc-acuse-boton mc-acuse-boton--verde"
                        >
                            Ver mis solicitudes
                            <span aria-hidden="true">↗</span>
                        </a>

                        <a
                            href="{{ route('experiencias.index') }}"
                            class="mc-acuse-boton mc-acuse-boton--borde"
                        >
                            Seguir explorando
                            <span aria-hidden="true">→</span>
                        </a>

                        <button
                            type="button"
                            class="mc-acuse-imprimir"
                            id="mc-acuse-imprimir"
                            title="Se abre el diálogo de impresión: elige Guardar como PDF"
                        >
                            <span aria-hidden="true">▤</span>
                            Descargar comprobante en PDF
                        </button>

                    </div>

                </div>

            </div>


            {{-- ================================= --}}
            {{-- MENSAJE FINAL DE MARCA --}}
            {{-- ================================= --}}

            <div class="mc-acuse-despedida">


                <p>
                    Gracias por elegir una forma
                    diferente de conocer
                    <em>Valle de Bravo.</em>
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
{{-- FUNCIONES DEL COMPROBANTE --}}
{{-- ====================================== --}}

@push('scripts')

<script src="{{ asset('js/acuse-celebracion.js') }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const botonCopiar = document.getElementById(
        'mc-acuse-copiar'
    );

    const elementoFolio = document.getElementById(
        'mc-acuse-numero-folio'
    );

    const mensajeCopiado = document.getElementById(
        'mc-acuse-copiado'
    );

    const botonImprimir = document.getElementById(
        'mc-acuse-imprimir'
    );


    // Copiar el folio al portapapeles
    if (botonCopiar && elementoFolio && mensajeCopiado) {

        botonCopiar.addEventListener('click', async function () {

            const folio = elementoFolio.textContent.trim();

            try {

                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {

                    await navigator.clipboard.writeText(folio);

                } else {

                    // Alternativa para entornos locales
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

                mensajeCopiado.textContent =
                    'Folio copiado correctamente.';

                botonCopiar.textContent = '✓ Copiado';

            } catch (error) {

                mensajeCopiado.textContent =
                    'No fue posible copiar automáticamente. ' +
                    'Selecciona y copia el folio.';

            }

        });

    }
    // Imprimir o guardar como PDF desde el navegador
    if (botonImprimir) {

        botonImprimir.addEventListener('click', function () {

            window.print();

        });

    }

});
</script>

@endpush
