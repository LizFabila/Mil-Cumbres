
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


@push('estilos')
<style>
.mc-detalle-calendario[hidden] { display: none !important; }
.mc-detalle-calendario {
    width: 100%;
    margin-top: 10px;
    padding: 14px;
    border: 1px solid #d8e2db;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 5px 18px rgba(0,0,0,.08);
}
#mc-detalle-fecha:not(:disabled) { cursor: pointer; background: #fff; }
.mc-detalle-calendario__cabecera {
    display: flex; justify-content: space-between; align-items: center;
    gap: 10px; margin-bottom: 14px;
}
.mc-detalle-calendario__cabecera strong {
    text-transform: capitalize; font-family: Arial, sans-serif; font-size: 14px;
}
.mc-detalle-calendario__cabecera button {
    width: 34px; height: 34px; border: 1px solid #d7ded9;
    border-radius: 8px; background: #f6f8f6; color: #274c39;
    font-size: 22px; cursor: pointer;
}
.mc-detalle-calendario__cabecera button:disabled { opacity: .35; cursor: not-allowed; }
.mc-detalle-calendario__semana, .mc-detalle-calendario__dias {
    display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px;
}
.mc-detalle-calendario__semana {
    margin-bottom: 7px; text-align: center; font-size: 12px;
    font-weight: 700; color: #53635a;
}
.mc-detalle-calendario__dias button {
    width: 100%; min-height: 36px; padding: 0; border: 1px solid transparent;
    border-radius: 7px; font: 13px Arial, sans-serif;
}
.mc-detalle-calendario__dias .dia-disponible {
    background: #d7f1df; color: #14643a; cursor: pointer;
}
.mc-detalle-calendario__dias .dia-disponible:hover { background: #a5dfb9; }
.mc-detalle-calendario__dias .dia-bloqueado {
    background: #f2f2f2; color: #aaa; cursor: not-allowed;
}
.mc-detalle-calendario__dias .dia-seleccionado {
    background: #176447; color: #fff; font-weight: 700;
}
.mc-detalle-calendario__leyenda {
    margin: 12px 0 0; font-size: 11px; color: #65736b; text-align: center;
}
</style>
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

                        

                        @if ($experiencia->catalogo)

                            <span class="mc-detalle-hero__categoria">
                                {{ $experiencia->catalogo->tipo }}
                            </span>

                        @endif

                    </div>

                    <div class="mc-detalle-hero__inferior">

                        

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

                    

                    <h1>
                        {{ $experiencia->nombre }}
                    </h1>

                    <div class="mc-detalle-hero__separador"></div>

                    <p class="mc-detalle-hero__resumen">
                        {{ $experiencia->resumen ?: 'Descubre esta experiencia en Valle de Bravo.' }}
                    </p>

                    

                    <a href="#reservar-experiencia"
                       class="mc-detalle-boton mc-detalle-boton--verde">

                        Planea tu experiencia

                        <span aria-hidden="true">↘</span>

                    </a>

                    

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


                <div>
                    <small>TEMPORADA</small>
                    <strong>
                        {{ $experiencia->temporada ?: 'Todo el año' }}
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
                        PLANEA TU VISITA
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


                </div>


                {{-- FORMULARIO DE C06 · EXPERIENCIA DE ESTA FICHA + ADICIONALES --}}

                <form
                    class="mc-detalle-reserva__form"
                    method="POST"
                    action="{{ route('solicitudes.store') }}"
                >

                    @csrf

                    <x-avisos />

                    {{-- EXPERIENCIA ACTUAL --}}

                    <div
                        class="mc-detalle-reserva__item"
                        data-precio="{{ (float) $experiencia->precio }}"
                    >

                        <div class="mc-detalle-reserva__item-cabecera">
                            <strong>{{ $experiencia->nombre }}</strong>
                            <span>{{ $experiencia->precio_texto }} por persona</span>
                        </div>

                        <input
                            type="hidden"
                            name="experiencias[]"
                            value="{{ $experiencia->id_experiencia }}"
                        >

                        <div class="mc-detalle-reserva__campo">

                            <label for="mc-detalle-fecha">
                                Fecha deseada
                            </label>

                            <input
                                type="text"
                                id="mc-detalle-fecha"
                                name="fechas[{{ $experiencia->id_experiencia }}]"
                                value="{{ old('fechas.' . $experiencia->id_experiencia, '') }}"
                                placeholder="Selecciona una fecha"
                                autocomplete="off"
                                aria-describedby="mc-detalle-feedback"
                                aria-haspopup="dialog"
                                aria-expanded="false"
                                readonly
                                required
                                @guest disabled @endguest
                            >

                            {{-- Calendario propio: solo permite días programados --}}
                            <div class="mc-detalle-calendario" id="mc-detalle-calendario"
                                 role="dialog" aria-label="Elegir fecha de experiencia" hidden>
                                <div class="mc-detalle-calendario__cabecera">
                                    <button type="button" id="mc-detalle-mes-anterior"
                                            aria-label="Mes anterior">‹</button>
                                    <strong id="mc-detalle-mes"></strong>
                                    <button type="button" id="mc-detalle-mes-siguiente"
                                            aria-label="Mes siguiente">›</button>
                                </div>
                                <div class="mc-detalle-calendario__semana" aria-hidden="true">
                                    <span>L</span><span>M</span><span>M</span><span>J</span>
                                    <span>V</span><span>S</span><span>D</span>
                                </div>
                                <div class="mc-detalle-calendario__dias" id="mc-detalle-dias"></div>
                                <p class="mc-detalle-calendario__leyenda">
                                    Verde: día programado · Gris: no disponible
                                </p>
                            </div>

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
                                name="personas[{{ $experiencia->id_experiencia }}]"
                                value="1"
                                min="1"
                                required

                                @if ($cupoMaximo)
                                    max="{{ $cupoMaximo }}"
                                @endif

                                @guest disabled @endguest
                            >

                        </div>

                        <div class="mc-detalle-reserva__campo">

                            <label for="mc-detalle-nota">
                                Información adicional
                                <span>Opcional</span>
                            </label>

                            <textarea
                                id="mc-detalle-nota"
                                name="notas[{{ $experiencia->id_experiencia }}]"
                                rows="2"
                                maxlength="1000"
                                @guest disabled @endguest
                            ></textarea>

                        </div>

                    </div>

                    {{-- AGREGAR OTRAS EXPERIENCIAS: VA A LA SOLICITUD CON ESTA SELECCIÓN --}}

                    @auth

                        @if ($tieneHorarios)

                            <button
                                type="submit"
                                class="mc-detalle-reserva__agregar"
                                id="mc-detalle-agregar"
                                formnovalidate
                                formmethod="GET"
                                formaction="{{ route('solicitudes.create') }}#mc-c06-experiencias"
                            >
                                + Agregar otra experiencia
                            </button>

                        @endif

                    @endauth

                    {{-- TOTAL DE TODAS LAS EXPERIENCIAS SELECCIONADAS --}}

                    <div class="mc-detalle-reserva__total">
                        <span>
                            Total estimado
                        </span>
                        <strong id="mc-detalle-total">
                            @if ((float) $experiencia->precio > 0)
                                ${{ number_format((float) $experiencia->precio, 2) }} MXN
                            @else
                                Por confirmar
                            @endif
                        </strong>
                        <small id="mc-detalle-total-detalle">
                            Precio por persona × participantes.
                        </small>
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

                    SIGUE DESCUBRIENDO
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

    // Total de esta experiencia: precio por persona × personas.
    const totalCampo = document.getElementById('mc-detalle-total');
    const personasPrincipal = document.getElementById('mc-detalle-personas');
    const formatoMoneda = new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN'
    });

    function recalcularTotal() {
        if (!totalCampo || !personasPrincipal) return;

        const precio = Number(
            document.querySelector('.mc-detalle-reserva__item').dataset.precio
        ) || 0;

        if (precio <= 0) {
            totalCampo.textContent = 'Por confirmar';
            return;
        }

        const cantidad = Math.max(1, Number(personasPrincipal.value) || 1);
        totalCampo.textContent = formatoMoneda.format(precio * cantidad);
    }

    if (personasPrincipal) {
        personasPrincipal.addEventListener('input', recalcularTotal);
    }

    recalcularTotal();

    // "Agregar otra experiencia" va por GET: el token CSRF no debe viajar en la URL.
    const botonAgregar = document.getElementById('mc-detalle-agregar');
    const tokenCampo = document.querySelector('.mc-detalle-reserva__form input[name="_token"]');

    if (botonAgregar && tokenCampo) {
        botonAgregar.addEventListener('click', function () {
            tokenCampo.disabled = true;
        });
    }

    const fecha = document.getElementById('mc-detalle-fecha');
    const mensaje = document.getElementById('mc-detalle-feedback');
    const calendario = document.getElementById('mc-detalle-calendario');
    const cuadricula = document.getElementById('mc-detalle-dias');
    const tituloMes = document.getElementById('mc-detalle-mes');
    const anterior = document.getElementById('mc-detalle-mes-anterior');
    const siguiente = document.getElementById('mc-detalle-mes-siguiente');
    const diasHabilitados = {{ \Illuminate\Support\Js::from($diasHabilitados) }};

    if (!fecha || !mensaje || !calendario || fecha.disabled) return;

    const hoy = new Date();
    const manana = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate() + 1);

    function formatoFecha(f) {
        return f.getFullYear() + '-' +
            String(f.getMonth() + 1).padStart(2, '0') + '-' +
            String(f.getDate()).padStart(2, '0');
    }

    function leerFecha(valor) {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(valor)) return null;
        const [anio, mes, dia] = valor.split('-').map(Number);
        const f = new Date(anio, mes - 1, dia);
        return formatoFecha(f) === valor ? f : null;
    }

    function esPermitida(f) {
        return f >= manana && diasHabilitados.includes(f.getDay());
    }

    const inicial = leerFecha(fecha.value);
    let anioVisible = inicial && inicial >= manana
        ? inicial.getFullYear() : manana.getFullYear();
    let mesVisible = inicial && inicial >= manana
        ? inicial.getMonth() : manana.getMonth();

    function validarFecha() {
        const elegida = leerFecha(fecha.value);
        const correcta = Boolean(elegida && esPermitida(elegida));
        fecha.setCustomValidity(correcta ? '' : 'Selecciona una fecha disponible.');
        mensaje.classList.toggle('mc-detalle-reserva__ayuda--error',
            Boolean(fecha.value) && !correcta);
        mensaje.textContent = !fecha.value
            ? 'Selecciona un día verde del calendario.'
            : correcta
                ? 'Día programado. La disponibilidad final se confirmará después.'
                : 'Esta fecha no está disponible para la experiencia.';
        return correcta;
    }

    function dibujar() {
        cuadricula.replaceChildren();
        const primero = new Date(anioVisible, mesVisible, 1);
        const ultimo = new Date(anioVisible, mesVisible + 1, 0).getDate();
        const desplazamiento = (primero.getDay() + 6) % 7;
        tituloMes.textContent = primero.toLocaleDateString('es-MX', {
            month: 'long', year: 'numeric'
        });
        anterior.disabled = primero <= new Date(
            manana.getFullYear(), manana.getMonth(), 1
        );

        for (let i = 0; i < desplazamiento; i++) {
            cuadricula.appendChild(document.createElement('span'));
        }

        for (let dia = 1; dia <= ultimo; dia++) {
            const f = new Date(anioVisible, mesVisible, dia);
            const permitido = esPermitida(f);
            const valor = formatoFecha(f);
            const boton = document.createElement('button');
            boton.type = 'button';
            boton.textContent = String(dia);
            boton.disabled = !permitido;
            boton.className = permitido ? 'dia-disponible' : 'dia-bloqueado';
            boton.setAttribute('aria-label', f.toLocaleDateString('es-MX', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            }));
            if (fecha.value === valor) {
                boton.classList.add('dia-seleccionado');
                boton.setAttribute('aria-pressed', 'true');
            }
            if (permitido) {
                boton.addEventListener('click', function () {
                    fecha.value = valor;
                    cerrar();
                    validarFecha();
                    fecha.dispatchEvent(new Event('change', { bubbles: true }));
                    fecha.focus();
                });
            }
            cuadricula.appendChild(boton);
        }
    }

    function cerrar() {
        calendario.hidden = true;
        fecha.setAttribute('aria-expanded', 'false');
    }

    function alternar() {
        if (fecha.disabled) return;
        calendario.hidden = !calendario.hidden;
        fecha.setAttribute('aria-expanded', String(!calendario.hidden));
        if (!calendario.hidden) dibujar();
    }

    fecha.addEventListener('click', alternar);
    fecha.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            alternar();
        } else if (e.key === 'Escape') cerrar();
    });
    anterior.addEventListener('click', function () {
        if (anterior.disabled) return;
        const f = new Date(anioVisible, mesVisible - 1, 1);
        anioVisible = f.getFullYear();
        mesVisible = f.getMonth();
        dibujar();
    });
    siguiente.addEventListener('click', function () {
        const f = new Date(anioVisible, mesVisible + 1, 1);
        anioVisible = f.getFullYear();
        mesVisible = f.getMonth();
        dibujar();
    });
    document.addEventListener('click', function (e) {
        if (!calendario.contains(e.target) && e.target !== fecha) cerrar();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') cerrar();
    });

    const formulario = fecha.closest('form');
    formulario.addEventListener('submit', function (e) {
        if (!validarFecha()) {
            e.preventDefault();
            fecha.reportValidity();
            fecha.focus();
        }
    });

    // El botón GET de agregar otra experiencia no debe exigir fecha.
    if (botonAgregar) {
        botonAgregar.addEventListener('click', function () {
            fecha.setCustomValidity('');
        });
    }
    if (fecha.value && !validarFecha()) fecha.value = '';
    validarFecha();

});
</script>

@endpush
