
{{-- C03 · Detalle de experiencia
     Tablas: experiencias, zonas, catalogo_experiencias,
     asigna_disponibilidad, disponibilidad
--}}

@extends('layouts.app')

@section('titulo', $experiencia->nombre)

@section('descripcion',
    \Illuminate\Support\Str::limit(
        $experiencia->resumen ?? $experiencia->nombre,
        150
    )
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/detalle-milcumbres.css') }}">
@endpush

@section('contenido')

@php
    $horarios = $experiencia->disponibilidades;
    $tieneHorarios = $horarios->isNotEmpty();

    // Relación entre los días de MySQL y JavaScript.
    $diasSemana = [
        'Domingo' => 0,
        'Lunes' => 1,
        'Martes' => 2,
        'Miercoles' => 3,
        'Jueves' => 4,
        'Viernes' => 5,
        'Sabado' => 6,
    ];

    $diasHabilitados = $horarios
        ->pluck('dias_semana')
        ->map(fn ($dia) => $diasSemana[$dia] ?? null)
        ->filter(fn ($dia) => $dia !== null)
        ->unique()
        ->values()
        ->all();
@endphp

<div class="mc-detalle">

    {{-- ====================================== --}}
    {{-- 01 · PORTADA DE LA EXPERIENCIA --}}
    {{-- ====================================== --}}

    <section class="mc-detalle-hero">

        <div class="contenedor">

            {{-- MIGAS DE PAN --}}

            <nav class="mc-detalle-breadcrumb"
                 aria-label="Ruta de navegación">

                <a href="{{ route('inicio') }}">
                    Inicio
                </a>

                <span>/</span>

                <a href="{{ route('experiencias.index') }}">
                    Experiencias
                </a>

                <span>/</span>

                <span>
                    {{ $experiencia->nombre }}
                </span>

            </nav>


            {{-- PRESENTACIÓN PRINCIPAL --}}

            <div class="mc-detalle-hero__grid">

                {{-- IMAGEN PROTAGONISTA --}}

                <div class="mc-detalle-hero__visual">

                    <img
                        src="{{ $experiencia->imagen_url }}"
                        alt="{{ $experiencia->nombre }}"
                        fetchpriority="high"
                    >

                    <div class="mc-detalle-hero__degradado"></div>

                    <div class="mc-detalle-hero__superior">

                        <span class="mc-detalle-hero__marca">
                            ✳ MIL CUMBRES
                        </span>

                        @if ($experiencia->catalogo)

                            <span class="mc-detalle-hero__categoria">
                                {{ $experiencia->catalogo->tipo }}
                            </span>

                        @endif

                    </div>

                    <div class="mc-detalle-hero__inferior">

                        <div>

                            <span>
                                DESCUBRE EL TERRITORIO
                            </span>

                            <strong>
                                Valle de Bravo,
                                Estado de México.
                            </strong>

                        </div>

                        <span class="mc-detalle-hero__numero"
                              aria-hidden="true">
                            01
                        </span>

                    </div>

                </div>


                {{-- PRESENTACIÓN DE LA ACTIVIDAD --}}

                <div class="mc-detalle-hero__contenido">

                    <span class="mc-detalle-etiqueta">
                        <span class="mc-detalle-linea"></span>
                        UNA EXPERIENCIA MIL CUMBRES
                    </span>

                    <span class="mc-detalle-hero__subtitulo">
                        DESCUBRE UNA NUEVA FORMA DE VIAJAR
                    </span>

                    <h1>
                        {{ $experiencia->nombre }}
                    </h1>

                    <div class="mc-detalle-hero__separador"></div>

                    <p class="mc-detalle-hero__resumen">
                        {{ $experiencia->resumen ?: 'Descubre esta experiencia en Valle de Bravo.' }}
                    </p>

                    <div class="mc-detalle-hero__ubicacion">

                        <span class="mc-detalle-hero__ubicacion-icono"
                              aria-hidden="true">
                            ⌖
                        </span>

                        <div>
                            <small>
                                UBICACIÓN DE LA EXPERIENCIA
                            </small>

                            <strong>
                                {{ $experiencia->zona?->nombre ?? 'Valle de Bravo' }}
                            </strong>
                        </div>

                    </div>

                    <a href="#reservar-experiencia"
                       class="mc-detalle-boton mc-detalle-boton--verde">

                        Planea tu experiencia

                        <span aria-hidden="true">↘</span>

                    </a>

                    <span class="mc-detalle-hero__pie">
                        NATURALEZA · BIENESTAR · CULTURA
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 02 · DATOS PRINCIPALES --}}
    {{-- ====================================== --}}

    <section class="mc-detalle-datos">

        <div class="contenedor mc-detalle-datos__grid">

            <div class="mc-detalle-dato">

                <span class="mc-detalle-dato__icono"
                      aria-hidden="true">
                    ⌖
                </span>

                <div>
                    <small>UBICACIÓN</small>
                    <strong>
                        {{ $experiencia->zona?->nombre ?? 'Valle de Bravo' }}
                    </strong>
                </div>

            </div>


            <div class="mc-detalle-dato">

                <span class="mc-detalle-dato__icono"
                      aria-hidden="true">
                    ◷
                </span>

                <div>
                    <small>DURACIÓN</small>
                    <strong>
                        {{ $experiencia->duracion_texto }}
                    </strong>
                </div>

            </div>


            <div class="mc-detalle-dato">

                <span class="mc-detalle-dato__icono"
                      aria-hidden="true">
                    ✳
                </span>

                <div>
                    <small>TEMPORADA</small>
                    <strong>
                        {{ $experiencia->temporada ?: 'Todo el año' }}
                    </strong>
                </div>

            </div>


            <div class="mc-detalle-dato">

                <span class="mc-detalle-dato__icono"
                      aria-hidden="true">
                    $
                </span>

                <div>
                    <small>PRECIO POR PERSONA</small>
                    <strong>
                        {{ $experiencia->precio_texto }}
                    </strong>
                </div>

            </div>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 03 · INFORMACIÓN Y RESERVACIÓN --}}
    {{-- ====================================== --}}

    <section class="mc-detalle-principal">

        <div class="contenedor mc-detalle-principal__grid">

            {{-- COLUMNA DE INFORMACIÓN --}}

            <div class="mc-detalle-contenido">

                {{-- ENLACES INTERNOS --}}

                <nav class="mc-detalle-navegacion"
                     aria-label="Información de la experiencia">

                    <a href="#sobre-experiencia">
                        Descripción
                    </a>

                    <a href="#horarios-experiencia">
                        Horarios
                    </a>

                    <a href="#antes-experiencia">
                        Antes de ir
                    </a>

                </nav>


                {{-- DESCRIPCIÓN --}}

                <article class="mc-detalle-seccion"
                         id="sobre-experiencia">

                    <span class="mc-detalle-etiqueta">
                        <span class="mc-detalle-linea"></span>
                        01 / DESCUBRE
                    </span>

                    <h2>
                        Una experiencia
                        <em>para recordar.</em>
                    </h2>

                    <p class="mc-detalle-seccion__texto">
                        {{ $experiencia->resumen ?: 'Explora esta experiencia y conoce sus detalles.' }}
                    </p>

                    <div class="mc-detalle-frase">

                        <span aria-hidden="true">✳</span>

                        <p>
                            Cada lugar tiene algo que contar.
                            Descubre nuevas formas de conectar
                            con el territorio.
                        </p>

                    </div>

                </article>


                {{-- ================================= --}}
                {{-- HORARIOS --}}
                {{-- ================================= --}}

                <article class="mc-detalle-seccion"
                         id="horarios-experiencia">

                    <span class="mc-detalle-etiqueta">
                        <span class="mc-detalle-linea"></span>
                        02 / ORGANIZA TU VISITA
                    </span>

                    <h2>
                        Encuentra el momento
                        <em>ideal para venir.</em>
                    </h2>

                    <p class="mc-detalle-seccion__texto">
                        Conoce los días y horarios habituales
                        de esta experiencia. La disponibilidad
                        para una fecha específica se revisará
                        al enviar tu solicitud.
                    </p>

                    @if (!$tieneHorarios)

                        <div class="mc-detalle-sin-horarios">

                            <span aria-hidden="true">
                                ◷
                            </span>

                            <div>
                                <strong>
                                    Horarios por publicar
                                </strong>

                                <p>
                                    Todavía no se han registrado
                                    horarios para esta experiencia.
                                </p>
                            </div>

                        </div>

                    @else

                        <div class="mc-detalle-horarios">

                            @foreach ($horarios as $d)

                                <div class="mc-detalle-horario">

                                    <div class="mc-detalle-horario__dia">

                                        <span class="mc-detalle-horario__numero">
                                            {{ sprintf('%02d', $loop->iteration) }}
                                        </span>

                                        <strong>
                                            {{ $d->dia_texto }}
                                        </strong>

                                    </div>

                                    <div class="mc-detalle-horario__hora">

                                        <small>HORARIO</small>

                                        <strong>
                                            {{ $d->horario }}
                                        </strong>

                                    </div>

                                    <div class="mc-detalle-horario__cupo">

                                        <small>
                                            CUPO POR FECHA
                                        </small>

                                        <strong>
                                            {{ $d->pivot->cupo }}
                                            personas
                                        </strong>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <div class="mc-detalle-horarios__aviso">

                            <span aria-hidden="true">ⓘ</span>

                            <p>
                                Los horarios corresponden
                                a la programación habitual.
                                El cupo indicado es la capacidad
                                máxima, no los lugares libres
                                en tiempo real.
                            </p>

                        </div>

                    @endif

                </article>


                {{-- ================================= --}}
                {{-- REQUISITOS --}}
                {{-- ================================= --}}

                <article class="mc-detalle-seccion"
                         id="antes-experiencia">

                    <span class="mc-detalle-etiqueta">
                        <span class="mc-detalle-linea"></span>
                        03 / PREPARA TU VISITA
                    </span>

                    <h2>
                        Todo lo que necesitas
                        <em>saber antes de ir.</em>
                    </h2>

                    <div class="mc-detalle-preparacion">

                        <div class="mc-detalle-preparacion__icono"
                             aria-hidden="true">
                            ✳
                        </div>

                        <div>

                            <h3>
                                Requisitos y recomendaciones
                            </h3>

                            <p>
                                {{ $experiencia->requisitos ?: 'Sin requisitos especiales.' }}
                            </p>

                        </div>

                    </div>

                    <div class="mc-detalle-preparacion">

                        <div class="mc-detalle-preparacion__icono"
                             aria-hidden="true">
                            ◷
                        </div>

                        <div>

                            <h3>
                                Confirmación de tu fecha
                            </h3>

                            <p>
                                La fecha que selecciones
                                es una preferencia.
                                Revisaremos la disponibilidad
                                antes de confirmar tu
                                reservación y solicitar el pago.
                            </p>

                        </div>

                    </div>

                </article>


                {{-- ================================= --}}
                {{-- PROCESO DE RESERVACIÓN --}}
                {{-- ================================= --}}

                <section class="mc-detalle-proceso">

                    <span class="mc-detalle-etiqueta">
                        <span class="mc-detalle-linea"></span>
                        ASÍ FUNCIONA
                    </span>

                    <h2>
                        Tres pasos para
                        <em>vivir tu experiencia.</em>
                    </h2>

                    <div class="mc-detalle-proceso__grid">

                        <div>
                            <span>01</span>
                            <h3>Solicita</h3>
                            <p>
                                Elige tu fecha preferida
                                y el número de personas.
                            </p>
                        </div>

                        <div>
                            <span>02</span>
                            <h3>Confirma</h3>
                            <p>
                                Revisaremos la disponibilidad
                                y confirmaremos tu solicitud.
                            </p>
                        </div>

                        <div>
                            <span>03</span>
                            <h3>Disfruta</h3>
                            <p>
                                Registra tu pago cuando
                                corresponda y prepara tu visita.
                            </p>
                        </div>

                    </div>

                </section>

            </div>


            {{-- ================================= --}}
            {{-- PANEL LATERAL DE RESERVACIÓN --}}
            {{-- ================================= --}}

            <aside class="mc-detalle-reserva"
                   id="reservar-experiencia"
                   aria-label="Solicitar experiencia">

                <div class="mc-detalle-reserva__cabecera">

                    <span>
                        ✳ PLANEA TU VISITA
                    </span>

                    <h2>
                        Tu próxima
                        <em>experiencia.</em>
                    </h2>

                    <p>
                        Selecciona una fecha preferida
                        y el número de participantes.
                    </p>

                </div>


                {{-- PRECIO --}}

                <div class="mc-detalle-reserva__precio">

                    <div>
                        <span>
                            DESDE
                        </span>

                        <strong>
                            {{ $experiencia->precio_texto }}
                        </strong>

                        <small>
                            Precio por persona
                        </small>
                    </div>

                    <span class="mc-detalle-reserva__decoracion"
                          aria-hidden="true">
                        ✳
                    </span>

                </div>


                {{-- FORMULARIO ORIGINAL DE C06 --}}

                <form
                    class="mc-detalle-reserva__form"
                    method="GET"
                    action="{{ route('solicitudes.create') }}"
                >

                    <input
                        type="hidden"
                        name="experiencia[]"
                        value="{{ $experiencia->id_experiencia }}"
                    >


                    <div class="mc-detalle-reserva__campo">

                        <label for="mc-detalle-fecha">
                            Fecha deseada
                        </label>

                        <input
                            type="date"
                            id="mc-detalle-fecha"
                            name="fecha"
                            min="{{ now()->addDay()->toDateString() }}"
                            aria-describedby="mc-detalle-feedback"
                            required
                            @guest disabled @endguest
                        >

                        <p
                            class="mc-detalle-reserva__ayuda"
                            id="mc-detalle-feedback"
                            aria-live="polite"
                        >
                            Selecciona un día con horario
                            en la programación habitual.
                        </p>

                    </div>


                    <div class="mc-detalle-reserva__campo">

                        <label for="mc-detalle-personas">
                            Número de personas

                            @if ($cupoMaximo)

                                <span>
                                    Máximo {{ $cupoMaximo }}
                                </span>

                            @endif

                        </label>

                        <input
                            type="number"
                            id="mc-detalle-personas"
                            name="personas"
                            value="1"
                            min="1"
                            required

                            @if ($cupoMaximo)
                                max="{{ $cupoMaximo }}"
                            @endif

                            @guest disabled @endguest
                        >

                        <p class="mc-detalle-reserva__ayuda">
                            Sujeto al cupo del horario
                            y fecha seleccionados.
                        </p>

                    </div>


                    {{-- ACCIONES SEGÚN USUARIO --}}

                    @if (!$tieneHorarios)

                        <button
                            type="button"
                            class="mc-detalle-reserva__submit"
                            disabled
                        >
                            Horarios por publicar
                        </button>

                    @else

                        @auth

                            <button
                                type="submit"
                                class="mc-detalle-reserva__submit"
                            >
                                Continuar con la solicitud

                                <span aria-hidden="true">↗</span>
                            </button>

                        @else

                            <button
                                type="button"
                                class="mc-detalle-reserva__submit"
                                disabled
                            >
                                Solicitar experiencia
                            </button>

                        @endauth

                    @endif

                </form>


                {{-- MENSAJE PARA INVITADOS --}}

                @guest

                    <div class="mc-detalle-reserva__invitado">

                        <strong>
                            ¿Quieres reservar?
                        </strong>

                        <p>
                            Necesitas una cuenta
                            para enviar tu solicitud.
                        </p>

                        <div>
                            <a href="{{ route('login') }}">
                                Iniciar sesión
                            </a>

                            <a href="{{ route('registro') }}">
                                Crear cuenta
                            </a>
                        </div>

                    </div>

                @endguest


                {{-- NOTA IMPORTANTE --}}

                <div class="mc-detalle-reserva__nota">

                    <span aria-hidden="true">ⓘ</span>

                    <p>
                        Tu solicitud no confirma
                        automáticamente la reservación.
                        Primero se revisa la disponibilidad.
                    </p>

                </div>

            </aside>

        </div>

    </section>


    {{-- ====================================== --}}
    {{-- 04 · INVITACIÓN FINAL --}}
    {{-- ====================================== --}}

    <section class="mc-detalle-final">

        <div class="contenedor mc-detalle-final__grid">

            <div>

                <span class="mc-detalle-etiqueta
                             mc-detalle-etiqueta--clara">

                    ✳ SIGUE DESCUBRIENDO
                </span>

                <h2>
                    Hay mucho más
                    <em>por explorar.</em>
                </h2>

                <p>
                    Descubre otras experiencias
                    de naturaleza, bienestar
                    y cultura en Valle de Bravo.
                </p>

            </div>

            <a href="{{ route('experiencias.index') }}"
               class="mc-detalle-boton mc-detalle-boton--claro">

                Explorar más experiencias

                <span aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

</div>

@endsection


{{-- ====================================== --}}
{{-- AYUDA PARA SELECCIONAR FECHA --}}
{{-- ====================================== --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const fecha = document.getElementById('mc-detalle-fecha');
    const mensaje = document.getElementById('mc-detalle-feedback');

    if (!fecha || !mensaje || fecha.disabled) {
        return;
    }

    const diasHabilitados =
        {{ \Illuminate\Support\Js::from($diasHabilitados) }};

    function validarFecha() {

        fecha.setCustomValidity('');

        if (!fecha.value) {

            mensaje.textContent =
                'Selecciona un día con horario en la programación habitual.';

            return;
        }

        // Mediodía local: evita desfases de fecha por UTC.
        const seleccion = new Date(fecha.value + 'T12:00:00');

        const dia = seleccion.getDay();

        if (!diasHabilitados.includes(dia)) {

            fecha.setCustomValidity(
                'Esta experiencia no tiene horario habitual ese día.'
            );

            mensaje.textContent =
                'No hay un horario configurado para ese día. ' +
                'Selecciona uno de los días publicados.';

            mensaje.classList.add(
                'mc-detalle-reserva__ayuda--error'
            );

        } else {

            mensaje.textContent =
                'Este día tiene un horario habitual. ' +
                'La disponibilidad final se confirmará después.';

            mensaje.classList.remove(
                'mc-detalle-reserva__ayuda--error'
            );

        }

    }

    fecha.addEventListener('change', validarFecha);
    fecha.addEventListener('input', validarFecha);

});
</script>

@endpush
