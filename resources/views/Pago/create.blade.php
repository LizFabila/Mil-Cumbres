
{{-- C09 · Registrar pago
     Tablas: pagos, solicitudes
--}}

@extends('layouts.app')

@section('titulo', 'Registrar pago')

@section('descripcion',
    'Consulta los datos bancarios y registra el comprobante de pago de tu solicitud en Mil Cumbres.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/pago-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-c09">

    {{-- ========================================== --}}
    {{-- 01 · PORTADA COMPACTA (frases originales, poco espacio) --}}
    {{-- ========================================== --}}

    <section class="mc-c09__hero mc-c09__hero--compacto">

        <img
            src="{{ config('milcumbres.imagenes.hero_catalogo') }}"
            alt="Paisaje natural de Valle de Bravo"
            class="mc-c09__hero-foto"
            fetchpriority="high"
        >

        <div class="mc-c09__hero-overlay"></div>

        <div class="contenedor mc-c09__hero-contenido">

            <nav class="mc-c09__migas"
                 aria-label="Ruta de navegación">
                <a href="{{ route('inicio') }}">Inicio</a>
                <span>/</span>
                <a href="{{ route('solicitudes.index') }}">
                    Mis solicitudes
                </a>
                <span>/</span>
                <span>Registrar pago</span>
            </nav>

            <span class="mc-c09__etiqueta
                         mc-c09__etiqueta--clara">
                MIL CUMBRES · PROCESO DE PAGO
            </span>

            <h1>
                Un paso más
                <em>hacia tu experiencia.</em>
            </h1>

            <p>
                Consulta los datos para realizar
                tu transferencia y registra el comprobante
                correspondiente. Nuestro equipo revisará
                la información antes de aprobar el pago.
            </p>

            <div class="mc-c09__hero-pie">
                <span>PLANEA</span>
                <span aria-hidden="true">→</span>
                <span>CONFIRMA</span>
                <span aria-hidden="true">→</span>
                <span>PREPARA TU VISITA</span>
            </div>

        </div>

    </section>


    {{-- ========================================== --}}
    {{-- 02 · CONTENIDO --}}
    {{-- ========================================== --}}

    <section class="mc-c09__contenido mc-c09__contenido--subido">

        <div class="contenedor">

            <x-avisos />


            {{-- ====================================== --}}
            {{-- LO QUE SOLICITASTE (en primera plana) --}}
            {{-- ====================================== --}}

            <article class="mc-c09__pedido" aria-labelledby="mc-c09-pedido-titulo">

                {{-- Columna verde: folio, estado y monto --}}
                <aside class="mc-c09__pedido-lateral">

                    <span class="mc-c09__etiqueta mc-c09__etiqueta--clara">
                        <span class="mc-c09__linea"></span>
                        INFORMACIÓN DE TU RESERVACIÓN
                    </span>

                    <h2 id="mc-c09-pedido-titulo">
                        Tu solicitud,
                        <em>identificada.</em>
                    </h2>

                    <p>
                        Comprueba que los datos
                        correspondan a la reservación
                        que deseas pagar.
                    </p>

                    <div class="mc-c09__pedido-folio">
                        <span>FOLIO DE SOLICITUD</span>

                        <strong id="mc-c09-folio">{{ $solicitud->folio }}</strong>

                        <div class="mc-c09__pedido-folio-acciones">
                            <button
                                type="button"
                                class="mc-c09__copiar"
                                data-mc-c09-copiar="mc-c09-folio"
                                aria-label="Copiar folio de solicitud"
                            >
                                <span aria-hidden="true">⧉</span>
                                Copiar folio
                            </button>

                            <span @class([
                                'mc-c09__estado',
                                'mc-c09__estado--' . $solicitud->estado,
                            ])>
                                {{ ucfirst(str_replace('_', ' ', $solicitud->estado)) }}
                            </span>

                            <span class="mc-c09__copiado"
                                  aria-live="polite"
                                  role="status"></span>
                        </div>
                    </div>

                    <div class="mc-c09__pedido-total">
                        <span>MONTO A PAGAR</span>
                        <strong>
                            ${{ number_format($solicitud->total, 2) }}
                            <small>MXN</small>
                        </strong>
                    </div>

                </aside>

                {{-- Columna blanca: experiencias solicitadas --}}
                <div class="mc-c09__pedido-detalle">

                    <div class="mc-c09__pedido-detalle-titulo">
                        <h3>Lo que solicitaste</h3>
                        <span>
                            {{ $solicitud->experiencias->count() }}
                            {{ $solicitud->experiencias->count() === 1 ? 'experiencia' : 'experiencias' }}
                        </span>
                    </div>

                    <ul class="mc-c09__pedido-lista">
                        @foreach ($solicitud->experiencias as $experiencia)
                            @php
                                $personas = (int) ($experiencia->pivot->cant_persona ?: $solicitud->cant_persona);
                                $fecha = $experiencia->pivot->fecha_deseada ?? $solicitud->fecha_deseada;
                            @endphp

                            <li class="mc-c09__pedido-item">
                                <img src="{{ $experiencia->imagen_url }}" alt="">

                                <div class="mc-c09__pedido-item-info">
                                    <strong>{{ $experiencia->nombre }}</strong>

                                    <div class="mc-c09__pedido-chips">
                                        <span>
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                                            {{ $fecha?->translatedFormat('j M Y') ?? 'Fecha por definir' }}
                                        </span>
                                        <span>
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c.6-3.6 3.2-5.5 6.5-5.5s5.9 1.9 6.5 5.5M16 4.8a3.3 3.3 0 0 1 0 6.4M18 14.8c2 .7 3.2 2.5 3.5 5.2"/></svg>
                                            {{ $personas }} {{ $personas === 1 ? 'persona' : 'personas' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mc-c09__pedido-item-precio">
                                    <strong>${{ number_format($experiencia->precio * $personas, 2) }}</strong>
                                    <small>${{ number_format($experiencia->precio, 2) }} × {{ $personas }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <p class="mc-c09__pedido-nota">
                        <span aria-hidden="true">↓</span>
                        Si todo es correcto, continúa con los datos bancarios y sube tu comprobante.
                    </p>

                </div>

            </article>


            {{-- ====================================== --}}
            {{-- 03 · SECCIÓN DE PAGO --}}
            {{-- ====================================== --}}

            <div class="mc-c09__encabezado">

                <div>

                    <span class="mc-c09__etiqueta">
                        <span class="mc-c09__linea"></span>
                        PROCESO DE REGISTRO
                    </span>

                    <h2>
                        Completa tu
                        <em>proceso de pago.</em>
                    </h2>

                </div>

                <p>
                    El registro se realiza en dos pasos:
                    transferencia bancaria y envío del
                    comprobante para su revisión.
                </p>

            </div>


            {{-- ====================================== --}}
            {{-- VALIDAR SI PUEDE PAGAR --}}
            {{-- ====================================== --}}

            @if (! $solicitud->puedePagar())

                <div class="mc-c09__bloqueado"
                     role="status">

                    <span class="mc-c09__bloqueado-icono"
                          aria-hidden="true">
                        ◷
                    </span>

                    <div>

                        @if ($solicitud->estado !== 'confirmada')

                            <span class="mc-c09__etiqueta">
                                SOLICITUD EN REVISIÓN
                            </span>

                            <h3>
                                Tu reservación todavía
                                no está confirmada.
                            </h3>

                            <p>
                                Podrás registrar tu pago
                                cuando confirmemos la solicitud.
                                Su estado actual es:
                                <strong>
                                    {{ ucfirst(str_replace(
                                        '_',
                                        ' ',
                                        $solicitud->estado
                                    )) }}.
                                </strong>
                            </p>

                        @else

                            <span class="mc-c09__etiqueta">
                                COMPROBANTE REGISTRADO
                            </span>

                            <h3>
                                Ya recibimos un comprobante
                                de esta solicitud.
                            </h3>

                            <p>
                                El comprobante registrado
                                se encuentra en revisión
                                o ya fue aprobado.
                                Puedes consultar su estado
                                en el historial de pagos.
                            </p>

                        @endif

                        <a
                            href="{{ route('solicitudes.index') }}"
                            class="mc-c09__boton
                                   mc-c09__boton--verde"
                        >
                            Volver a mis solicitudes
                            <span aria-hidden="true">↗</span>
                        </a>

                    </div>

                </div>

            @else

                <div class="mc-c09__pasos">

                    <span>
                        Realiza tu transferencia
                    </span>

                    <span>
                        Sube el comprobante
                    </span>

                    <span>
                        Espera la revisión
                    </span>

                </div>


                <div class="mc-c09__grid">

                    {{-- ================================= --}}
                    {{-- 04 · DATOS BANCARIOS --}}
                    {{-- ================================= --}}

                    <section class="mc-c09__banco">

                        <div class="mc-c09__banco-header">

                            <span class="mc-c09__etiqueta
                                         mc-c09__etiqueta--clara">
                                TRANSFERENCIA BANCARIA
                            </span>


                            <h3>
                                Datos para
                                <em>tu transferencia.</em>
                            </h3>

                            <p>
                                Utiliza la información
                                proporcionada para realizar
                                la transferencia correspondiente
                                a tu solicitud.
                            </p>

                        </div>


                        <div class="mc-c09__banco-contenido">

                            <dl class="mc-c09__datos-banco">

                                {{-- BANCO --}}

                                <div>

                                    <dt>INSTITUCIÓN BANCARIA</dt>

                                    <dd>
                                        {{ config('milcumbres.pago.banco') }}
                                    </dd>

                                </div>


                                {{-- TITULAR --}}

                                <div>

                                    <dt>TITULAR DE LA CUENTA</dt>

                                    <dd>
                                        {{ config('milcumbres.pago.titular') }}
                                    </dd>

                                </div>


                                {{-- CLABE --}}

                                <div>

                                    <dt>CLABE INTERBANCARIA</dt>

                                    <dd class="mc-c09__dato-copiable">

                                        <strong
                                            id="mc-c09-clabe"
                                            class="mc-c09__numero-copiable"
                                        >
                                            {{ config('milcumbres.pago.clabe') }}
                                        </strong>

                                        <button
                                            type="button"
                                            class="mc-c09__copiar"
                                            data-mc-c09-copiar="mc-c09-clabe"
                                            aria-label="Copiar CLABE interbancaria"
                                        >
                                            <span aria-hidden="true">⧉</span>
                                            Copiar
                                        </button>

                                        <span
                                            class="mc-c09__copiado"
                                            aria-live="polite"
                                            role="status"
                                        ></span>

                                    </dd>

                                </div>


                                {{-- CONCEPTO --}}

                                <div>

                                    <dt>CONCEPTO DE LA TRANSFERENCIA</dt>

                                    <dd>
                                        <strong>
                                            {{ $solicitud->folio }}
                                        </strong>
                                    </dd>

                                </div>

                            </dl>


                            {{-- MONTO --}}

                            <div class="mc-c09__banco-total">

                                <span>
                                    MONTO A TRANSFERIR
                                </span>

                                <strong>
                                    ${{ number_format(
                                        $solicitud->total,
                                        2
                                    ) }}
                                    <small>MXN</small>
                                </strong>

                            </div>

                        </div>


                        <div class="mc-c09__banco-nota">

                            <span aria-hidden="true">ⓘ</span>

                            <p>
                                Utiliza el folio
                                <strong>{{ $solicitud->folio }}</strong>
                                como concepto de tu transferencia
                                para facilitar la identificación
                                del pago.
                            </p>

                        </div>

                    </section>


                    {{-- ================================= --}}
                    {{-- 05 · SUBIR COMPROBANTE --}}
                    {{-- ================================= --}}

                    <section class="mc-c09__comprobante">

                        <div class="mc-c09__comprobante-header">

                            <span class="mc-c09__etiqueta">
                                <span class="mc-c09__linea"></span>
                                REGISTRO DE PAGO
                            </span>

                            <h3>
                                Adjunta tu
                                <em>comprobante.</em>
                            </h3>

                            <p>
                                Después de realizar la transferencia,
                                proporciona la referencia bancaria
                                y adjunta el comprobante
                                correspondiente.
                            </p>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('pagos.store', $solicitud) }}"
                            enctype="multipart/form-data"
                            class="mc-c09__form"
                            novalidate
                        >

                            @csrf


                            {{-- REFERENCIA BANCARIA --}}

                            <div class="mc-c09__campo">

                                <label for="referencia">
                                    Referencia bancaria
                                    <span aria-hidden="true">*</span>
                                </label>

                                <input
                                    @class([
                                        'mc-c09__control',
                                        'control',
                                        'es-invalido' =>
                                            $errors->has('referencia')
                                    ])
                                    type="text"
                                    id="referencia"
                                    name="referencia"
                                    value="{{ old('referencia') }}"
                                    maxlength="50"
                                    placeholder="Escribe la referencia de tu transferencia"
                                    required
                                >

                                
                                    {{-- AYUDA PARA IDENTIFICAR LA REFERENCIA BANCARIA --}}

                                    <details class="mc-c09__guia-referencia">

                                        <summary>
                                            <span aria-hidden="true">ⓘ</span>

                                            ¿Dónde encuentro mi referencia bancaria?

                                            <span class="mc-c09__guia-flecha"
                                                aria-hidden="true">⌄</span>
                                        </summary>

                                        <div class="mc-c09__guia-contenido">

                                            <p>
                                                Busca en tu comprobante de transferencia
                                                el apartado llamado
                                                <strong>"Referencia bancaria"</strong>
                                                o <strong>"Número de referencia"</strong>.
                                                Su ubicación puede variar según el banco.
                                            </p>

                                            <img
                                                src="{{ asset('img/ejemplo-referencia-bancaria.png') }}"
                                                alt="Ejemplo de un comprobante de transferencia con la referencia bancaria resaltada en verde."
                                                loading="lazy"
                                                width="900"
                                                height="920"
                                            >

                                            <small>
                                                * Imagen ilustrativa. Utiliza únicamente
                                                la referencia real de tu transferencia.
                                            </small>

                                        </div>

                                    </details>


                                @error('referencia')

                                    <span class="mc-c09__error error"
                                          role="alert">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            {{-- ARCHIVO DEL COMPROBANTE --}}

                            <div class="mc-c09__campo">

                                <label for="comprobante">
                                    Comprobante de pago
                                    <span aria-hidden="true">*</span>
                                </label>

                                <div class="mc-c09__archivo">

                                    <span class="mc-c09__archivo-icono"
                                          aria-hidden="true">
                                        ↑
                                    </span>

                                    <strong>
                                        Selecciona tu comprobante
                                    </strong>

                                    <p>
                                        Archivo JPG, PNG o PDF
                                        de hasta 4 MB.
                                    </p>

                                    <input
                                        type="file"
                                        id="comprobante"
                                        name="comprobante"
                                        class="mc-c09__archivo-input"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        aria-describedby="mc-c09-archivo-ayuda"
                                        required
                                    >

                                    <span
                                        class="mc-c09__archivo-seleccionado"
                                        id="mc-c09-archivo-ayuda"
                                        aria-live="polite"
                                    >
                                        Aún no has seleccionado un archivo.
                                    </span>

                                </div>

                                @error('comprobante')

                                    <span class="mc-c09__error error"
                                          role="alert">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </div>


                            {{-- ENVÍO DEL COMPROBANTE --}}

                            <div class="mc-c09__form-pie">

                                <div class="mc-c09__form-aviso">

                                    <span aria-hidden="true">ⓘ</span>

                                    <p>
                                        El comprobante será revisado.
                                        Subirlo no significa que
                                        el pago haya sido aprobado.
                                    </p>

                                </div>


                                <button
                                    type="submit"
                                    class="mc-c09__boton
                                           mc-c09__boton--verde
                                           mc-c09__enviar"
                                >
                                    Enviar comprobante
                                    <span aria-hidden="true">↗</span>
                                </button>

                            </div>

                        </form>

                    </section>

                </div>

            @endif


            {{-- ====================================== --}}
            {{-- 06 · HISTORIAL DE PAGOS --}}
            {{-- ====================================== --}}

            @if ($solicitud->pagos->isNotEmpty())

                <section class="mc-c09__historial">

                    <div class="mc-c09__historial-header">

                        <div>

                            <span class="mc-c09__etiqueta">
                                <span class="mc-c09__linea"></span>
                                HISTORIAL DE LA SOLICITUD
                            </span>

                            <h2>
                                Pagos
                                <em>registrados.</em>
                            </h2>

                            <p>
                                Consulta los comprobantes
                                que has enviado y el estado
                                de revisión de cada uno.
                            </p>

                        </div>

                        <span class="mc-c09__historial-contador">
                            {{ $solicitud->pagos->count() }}
                            {{ $solicitud->pagos->count() === 1
                                ? 'registro'
                                : 'registros' }}
                        </span>

                    </div>


                    <div class="mc-c09__tabla-wrap">

                        <table class="mc-c09__tabla">

                            <thead>
                                <tr>
                                    <th scope="col">
                                        FECHA DE REGISTRO
                                    </th>

                                    <th scope="col">
                                        REFERENCIA BANCARIA
                                    </th>

                                    <th scope="col">
                                        ESTADO DEL PAGO
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($solicitud->pagos as $pago)

                                    <tr>

                                        <td data-label="Fecha">

                                            <span class="mc-c09__tabla-fecha">
                                                {{ $pago->fecha->format('d/m/Y H:i') }}
                                            </span>

                                        </td>

                                        <td data-label="Referencia">

                                            <strong class="mc-c09__tabla-referencia">
                                                {{ $pago->referencia }}
                                            </strong>

                                        </td>

                                        <td data-label="Estado">

                                            <x-estado :valor="$pago->estado" />

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </section>

            @endif


            {{-- ====================================== --}}
            {{-- 07 · ACCIONES INFERIORES --}}
            {{-- ====================================== --}}

            <div class="mc-c09__final">

                <div>


                    <p>
                        Continúa consultando tu solicitud
                        desde tu espacio personal.
                    </p>

                </div>

                <a
                    href="{{ route('solicitudes.index') }}"
                    class="mc-c09__regresar"
                >
                    ← Volver a mis solicitudes
                </a>

            </div>

        </div>

    </section>

</div>

@endsection


{{-- ========================================== --}}
{{-- COPIAR DATOS Y MOSTRAR ARCHIVO --}}
{{-- ========================================== --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Copiar folio o CLABE.
    document.querySelectorAll(
        '[data-mc-c09-copiar]'
    ).forEach(function (boton) {

        boton.addEventListener('click', async function () {

            const origen = document.getElementById(
                boton.dataset.mcC09Copiar
            );

            const mensaje = boton.parentElement.querySelector(
                '.mc-c09__copiado'
            );

            if (!origen) return;

            const valor = origen.textContent.trim();

            try {

                if (!navigator.clipboard?.writeText) {
                    throw new Error('Portapapeles no disponible');
                }

                await navigator.clipboard.writeText(valor);

                if (mensaje) {
                    mensaje.textContent = 'Copiado correctamente.';
                }

            } catch (error) {

                if (mensaje) {
                    mensaje.textContent =
                        'No se pudo copiar automáticamente. ' +
                        'Selecciona el dato para copiarlo.';
                }

            }

        });

    });


    // Mostrar nombre del comprobante elegido.
    const archivo = document.getElementById('comprobante');

    const mensajeArchivo = document.getElementById(
        'mc-c09-archivo-ayuda'
    );

    if (archivo && mensajeArchivo) {

        archivo.addEventListener('change', function () {

            const seleccionado = archivo.files?.[0];

            if (!seleccionado) {

                mensajeArchivo.textContent =
                    'Aún no has seleccionado un archivo.';

                return;
            }

            const pesoMB = seleccionado.size / (1024 * 1024);

            mensajeArchivo.textContent =
                'Archivo seleccionado: ' +
                seleccionado.name;

            if (pesoMB > 4) {

                mensajeArchivo.textContent +=
                    '. Supera el máximo indicado de 4 MB.';

            }

        });

    }

});
</script>

@endpush
