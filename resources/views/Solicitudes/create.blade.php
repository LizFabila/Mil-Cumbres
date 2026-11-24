
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
      href="{{ \App\Support\Temas::hoja('solicitar-milcumbres.css') }}">
@endpush
@section('contenido')
@php
    $marcadas = old('experiencias', $seleccionadas);
    $marcadasIds = array_map('intval', (array) $marcadas);
    $fechasPrevias = (array) old('fechas', $fechas);
    $personasPrevias = (array) old('personas', $personas);
    $notasPrevias = (array) old('notas', $notas);
    $horariosPrevios = (array) old('horarios', $horarios ?? []);
    $modalidadPrevias = (array) old('modalidad', []);
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
                MIL CUMBRES · PLANEA TU EXPERIENCIA
            </span>
            <h1>
                Tu próxima aventura
                <em>comienza aquí.</em>
            </h1>
            <p>
                Elige tus experiencias favoritas,
                elige una fecha para cada actividad y
                ven a visitarnos y prepara un día especial
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
                    experiencias y elegir una fecha diferente para cada una.
                    Te confirmaremos la disponibilidad
                    antes de solicitar cualquier pago.
                </p>
                <span>
                    UNA SOLICITUD · MÚLTIPLES EXPERIENCIAS
                </span>
            </div>
        </div>
    </section>
    {{-- ======================================= --}}
    {{-- 03 · FORMULARIO DE SOLICITUD --}}
    {{-- ======================================= --}}
    <section class="mc-c06__seccion" id="mc-c06-formulario">
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
                                Experiencias
                            </a>
                            <a href="#mc-c06-visita">
                                Fechas y personas
                            </a>
                            <a href="#mc-c06-resumen">
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
                                <span>
                                    Selecciona tus
                                    <em>experiencias.</em>
                                </span>
                            </legend>
                            <p class="mc-c06__descripcion"
                               id="ayuda-exp">
                                Elige una o varias actividades.
                                Cada experiencia tiene su propia
                                fecha, número de personas e
                                información adicional. El total
                                suma el precio por persona de
                                cada una por sus participantes.
                            </p>
                            {{-- LISTADO DE EXPERIENCIAS --}}
                            <div class="mc-c06__opciones">
                                @forelse ($experiencias as $exp)
                                    <div
                                        class="mc-c06__tarjeta"
                                        data-nombre="{{ $exp->nombre }}"
                                    >
                                    <label
                                        class="opcion-exp mc-c06__opcion"
                                    >
                                        {{-- CHECKBOX ORIGINAL --}}
                                        <input
                                            type="checkbox"
                                            name="experiencias[]"
                                            value="{{ $exp->id_experiencia }}"
                                            data-precio="{{ $exp->precio_actual }}"
                                            data-tipo-precio="{{ $exp->tipo_precio }}"
                                            data-grupos='@json($exp->gruposParaJs())'
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
                                                {{ $exp->zonas_texto ?: 'Zona por confirmar' }}
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
                                                {{ $exp->oferta_actual ? 'OFERTA · POR PERSONA' : 'POR PERSONA' }}
                                            </small>
                                            @if ($exp->oferta_actual)
                                                <del class="mc-precio-antes">{{ $exp->precio_texto }}</del>
                                            @endif
                                            {{ $exp->precio_actual_texto }}
                                        </strong>
                                        @foreach ($exp->grupos as $grupo)
                                            <small class="mc-c06__opcion-grupo">
                                                Grupo {{ $grupo->rangoTexto() }}: ${{ number_format($exp->precioGrupoActual($grupo), 2) }}
                                            </small>
                                        @endforeach
                                    </label>
                                        <div
                                            class="campo fecha-experiencia"
                                            data-experiencia="{{ $exp->id_experiencia }}"
                                            data-dias="{{ $exp->disponibilidades->pluck('dias_semana')->unique()->implode(',') }}"
                                            @php $apartadosExp = $exp->horariosApartados(); @endphp
                                            data-apartados='@json((object) $apartadosExp)'
                                            data-sin-horarios='@json($exp->fechasSinHorarios($apartadosExp))'
                                            hidden
                                        >
                                            <label for="fecha_{{ $exp->id_experiencia }}">
                                                {{ $exp->nombre }}
                                            </label>
                                            <input
                                                class="control calendario-valor"
                                                type="text"
                                                id="fecha_{{ $exp->id_experiencia }}"
                                                name="fechas[{{ $exp->id_experiencia }}]"
                                                value="{{ old('fechas.' . $exp->id_experiencia, $fechasPrevias[$exp->id_experiencia] ?? '') }}"
                                                placeholder="Selecciona una fecha"
                                                readonly
                                                disabled
                                            >
                                            <div class="calendario-propio" hidden>
                                                <div class="calendario-cabecera">
                                                    <button type="button"
                                                            class="calendario-anterior"
                                                            aria-label="Mes anterior">
                                                        ‹
                                                    </button>
                                                    <strong class="calendario-mes"></strong>
                                                    <button type="button"
                                                            class="calendario-siguiente"
                                                            aria-label="Mes siguiente">
                                                        ›
                                                    </button>
                                                </div>
                                                <div class="calendario-dias-semana">
                                                    <span>L</span>
                                                    <span>M</span>
                                                    <span>M</span>
                                                    <span>J</span>
                                                    <span>V</span>
                                                    <span>S</span>
                                                    <span>D</span>
                                                </div>
                                                <div class="calendario-cuadricula"></div>
                                                <p class="calendario-ayuda">
                                                    Verde: disponible · Gris: no disponible
                                                </p>
                                            </div>
                                            <div class="campo">
                                                <label for="horario_{{ $exp->id_experiencia }}">
                                                    Horario
                                                    <span class="mc-c06__obligatorio">*</span>
                                                </label>
                                                <select
                                                    class="control mc-c06__control horario-exp"
                                                    id="horario_{{ $exp->id_experiencia }}"
                                                    name="horarios[{{ $exp->id_experiencia }}]"
                                                    disabled
                                                >
                                                    <option value="">Elige primero una fecha</option>
                                                    @foreach ($exp->disponibilidades as $d)
                                                        <option
                                                            value="{{ $d->id_disponibilidad }}"
                                                            data-dia="{{ strtolower($d->dias_semana) }}"
                                                            data-cupo="{{ $d->pivot->cupo }}"
                                                            @selected((string) ($horariosPrevios[$exp->id_experiencia] ?? '') === (string) $d->id_disponibilidad)
                                                        >
                                                            {{ $d->dia_texto }} · {{ $d->horario }} (máx. {{ $d->pivot->cupo }} personas)
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('horarios.' . $exp->id_experiencia)
                                                    <span class="error mc-c06__error" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mc-c06__campo-extra">
                                                <label for="personas-{{ $exp->id_experiencia }}">
                                                    Número de personas
                                                    <span class="mc-c06__obligatorio">*</span>
                                                </label>
                                                <input
                                                    class="control mc-c06__control personas-exp"
                                                    type="number"
                                                    id="personas-{{ $exp->id_experiencia }}"
                                                    name="personas[{{ $exp->id_experiencia }}]"
                                                    value="{{ old('personas.' . $exp->id_experiencia, $personasPrevias[$exp->id_experiencia] ?? 1) }}"
                                                    min="1"
                                                    max="500"
                                                    disabled
                                                >
                                                @error('personas.' . $exp->id_experiencia)
                                                    <span class="error mc-c06__error" role="alert">{{ $message }}</span>
                                                @enderror
                                                @if ($exp->tipo_precio === 'ambos')
                                                    <fieldset class="mc-c06__modalidad">
                                                        <legend>¿Cómo quieres pagar?</legend>
                                                        <label class="mc-c06__modalidad-opcion">
                                                            <input type="radio" name="modalidad[{{ $exp->id_experiencia }}]" value="persona" data-modalidad-radio @checked(($modalidadPrevias[$exp->id_experiencia] ?? 'persona') === 'persona') disabled>
                                                            <span>Por persona</span>
                                                            <small data-modalidad-detalle="persona"></small>
                                                        </label>
                                                        <label class="mc-c06__modalidad-opcion">
                                                            <input type="radio" name="modalidad[{{ $exp->id_experiencia }}]" value="grupo" data-modalidad-radio @checked(($modalidadPrevias[$exp->id_experiencia] ?? null) === 'grupo') disabled>
                                                            <span>Por grupo</span>
                                                            <small data-modalidad-detalle="grupo"></small>
                                                        </label>
                                                    </fieldset>
                                                @endif
                                                <label for="notas-{{ $exp->id_experiencia }}">
                                                    Información adicional
                                                    <span class="mc-c06__opcional">(opcional)</span>
                                                </label>
                                                <textarea
                                                    class="control mc-c06__control mc-c06__textarea notas-exp"
                                                    id="notas-{{ $exp->id_experiencia }}"
                                                    name="notas[{{ $exp->id_experiencia }}]"
                                                    rows="3"
                                                    maxlength="1000"
                                                    disabled
                                                >{{ old('notas.' . $exp->id_experiencia, $notasPrevias[$exp->id_experiencia] ?? '') }}</textarea>
                                            </div>
                                            <small class="ayuda">
                                                Días disponibles:
                                                {{ $exp->disponibilidades->map->dia_texto->unique()->implode(', ') ?: 'Sin horarios disponibles' }}
                                            </small>
                                            @error('fechas.' . $exp->id_experiencia)
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    <p
                                        class="mc-c06__subtotal"
                                        data-subtotal="{{ $exp->id_experiencia }}"
                                        hidden
                                    >
                                        Subtotal:
                                        <strong></strong>
                                    </p>
                                    </div>
                                @empty
                                    <div class="mc-c06__vacio">
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
                            <p id="mensaje-seleccion" class="ayuda" aria-live="polite">
                                Selecciona una experiencia para elegir su fecha.
                            </p>
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
                        {{-- PASO 02 · FECHAS Y PERSONAS --}}
                        {{-- ================================= --}}
                        <fieldset
                            class="form-grid mc-c06__bloque
                                   mc-c06__bloque--visita"
                            id="mc-c06-visita"
                        >
                            <legend class="mc-c06__legend completo">
                                <span>
                                    Planea
                                    <em>tu visita.</em>
                                </span>
                            </legend>
                            <p class="mc-c06__descripcion completo">
                                Indícanos cuándo te gustaría
                                realizar cada actividad y cuántas
                                personas asistirán a cada una.
                            </p>
                        </fieldset>
                        {{-- RECORDATORIO --}}
                        <div class="mc-c06__recordatorio">
                            <div>
                                <strong>
                                    Las fechas elegidas son preferencias.
                                </strong>
                                <p>
                                    Revisaremos las fechas, los horarios y
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
                                    TU SOLICITUD
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
                                        Actividades
                                    </span>
                                    <strong id="resumen-actividades">
                                        Ninguna todavía
                                    </strong>
                                </div>
                                <div class="mc-c06__resumen-fila">
                                    <span>
                                        <span aria-hidden="true">◷</span>
                                        Fechas
                                    </span>
                                    <strong id="resumen-fechas">
                                        Pendiente
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
@push('estilos')
<style>
    .fecha-experiencia[hidden],
    .calendario-propio[hidden] {
        display: none !important;
    }
    .fecha-experiencia {
        margin-top: 12px;
    }
    .calendario-valor {
        cursor: pointer;
        background: #fff;
    }
    .calendario-propio {
        width: 100%;
        max-width: 350px;
        margin-top: 10px;
        padding: 14px;
        background: #fff;
        border: 1px solid #d8e2db;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(0,0,0,.08);
    }
    .calendario-cabecera {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
    }
    .calendario-mes {
        text-transform: capitalize;
        font-family: Arial, sans-serif;
        font-size: 15px;
    }
    .calendario-anterior,
    .calendario-siguiente {
        width: 35px;
        height: 35px;
        border: 1px solid #d7ded9;
        border-radius: 8px;
        background: #f6f8f6;
        cursor: pointer;
        font-size: 23px;
        color: var(--tm-274c39, #274c39);
    }
    .calendario-dias-semana,
    .calendario-cuadricula {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 4px;
    }
    .calendario-dias-semana {
        margin-bottom: 7px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: var(--tm-53635a, #53635a);
    }
    .calendario-cuadricula button {
        width: 100%;
        min-height: 37px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-family: Arial, sans-serif;
        font-size: 13px;
    }
    .calendario-cuadricula .dia-disponible {
        background: var(--tm-d7f1df, #d7f1df);
        color: var(--tm-14643a, #14643a);
        cursor: pointer;
    }
    .calendario-cuadricula .dia-disponible:hover {
        background: var(--tm-a5dfb9, #a5dfb9);
    }
    .calendario-cuadricula .dia-bloqueado {
        background: #f2f2f2;
        color: #aaa;
        cursor: not-allowed;
    }
    .calendario-cuadricula .dia-seleccionado {
        background: var(--tm-176447, #176447);
        color: white;
        font-weight: bold;
    }
    .calendario-ayuda {
        margin: 12px 0 0;
        font-size: 11px;
        color: var(--tm-65736b, #65736b);
        text-align: center;
    }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-solicitud');
    if (!form) return;
    const checks = Array.from(
        form.querySelectorAll('input[name="experiencias[]"]')
    );
    const bloques = Array.from(
        form.querySelectorAll('.fecha-experiencia')
    );
    const total = document.getElementById('total');
    const aviso = document.getElementById('aviso-dia');
    const mensaje = document.getElementById('mensaje-seleccion');
    const resumenActividades = document.getElementById('resumen-actividades');
    const resumenFechas = document.getElementById('resumen-fechas');
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    const manana = new Date(
        hoy.getFullYear(),
        hoy.getMonth(),
        hoy.getDate() + 1
    );
    const nombresDias = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles',
        'Jueves', 'Viernes', 'Sabado'
    ];
    const formatoMoneda = new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN'
    });
    function normalizar(texto) {
        return String(texto || '')
            .trim()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase();
    }
    function fechaTexto(fecha) {
        const anio = fecha.getFullYear();
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const dia = String(fecha.getDate()).padStart(2, '0');
        return `${anio}-${mes}-${dia}`;
    }
    function leerFecha(valor) {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(valor)) return null;
        const [anio, mes, dia] = valor.split('-').map(Number);
        const fecha = new Date(anio, mes - 1, dia);
        return fechaTexto(fecha) === valor ? fecha : null;
    }
    function diasPermitidos(bloque) {
        return (bloque.dataset.dias || '')
            .split(',')
            .map(normalizar)
            .filter(Boolean);
    }
    // Datos de reservaciones confirmadas: fechas sin horarios libres y horarios apartados por fecha.
    function leerJson(texto, vacio) {
        try { return JSON.parse(texto || '') || vacio; } catch (e) { return vacio; }
    }
    function fechaPermitida(fecha, dias, bloque) {
        const sinHorarios = bloque ? leerJson(bloque.dataset.sinHorarios, []) : [];
        return fecha >= manana &&
            dias.includes(normalizar(nombresDias[fecha.getDay()])) &&
            !sinHorarios.includes(fechaTexto(fecha));
    }

    // Horarios: solo se muestran los de la fecha elegida. Si hay uno, queda seleccionado.
    function filtrarHorarios(bloque) {
        const select = bloque.querySelector('.horario-exp');
        if (!select) return;
        if (!bloque._horarios) {
            bloque._horarios = Array.from(select.options)
                .filter(o => o.value)
                .map(o => o.cloneNode(true));
        }
        const fecha = leerFecha(bloque.querySelector('.calendario-valor').value);
        const dia = fecha ? normalizar(nombresDias[fecha.getDay()]) : null;
        const previa = select.value;
        // Se ocultan los horarios apartados por una reservación confirmada en esa fecha.
        const apartados = fecha ? (leerJson(bloque.dataset.apartados, {})[fechaTexto(fecha)] || []) : [];
        const coinciden = dia
            ? bloque._horarios.filter(o => normalizar(o.dataset.dia) === dia && !apartados.includes(Number(o.value)))
            : [];

        select.innerHTML = '';
        select.add(new Option(
            dia ? (coinciden.length ? 'Elige un horario' : 'Sin horarios ese día') : 'Elige primero una fecha',
            ''
        ));
        coinciden.forEach(o => select.add(o.cloneNode(true)));

        if (coinciden.length === 1) {
            select.value = coinciden[0].value;
        } else if (coinciden.some(o => o.value === previa)) {
            select.value = previa;
        }
        ajustarMaxPersonas(bloque);
    }

    // El cupo del horario elegido limita el número de personas.
    function ajustarMaxPersonas(bloque) {
        const select = bloque.querySelector('.horario-exp');
        const personas = bloque.querySelector('.personas-exp');
        if (!select || !personas) return;
        const opcion = select.selectedOptions[0];
        const cupo = opcion && opcion.dataset.cupo ? Number(opcion.dataset.cupo) : 0;
        if (cupo > 0) {
            personas.max = cupo;
            if (Number(personas.value) > cupo) personas.value = cupo;
        } else {
            personas.removeAttribute('max');
        }
        actualizarTotal();
    }
    // Tipo 'ambos': el cliente elige precio por persona o por grupo.
    // La opción de grupo solo se habilita si el número de personas cae en un rango.
    function actualizarModalidades(bloque, precio, grupos, cantidad) {
        if (!bloque) return;

        const radioPersona = bloque.querySelector('[data-modalidad-radio][value="persona"]');
        const radioGrupo = bloque.querySelector('[data-modalidad-radio][value="grupo"]');
        if (!radioPersona || !radioGrupo) return;

        const grupo = grupos.find(g => cantidad >= g.desde && cantidad <= g.hasta);
        const detalleGrupo = bloque.querySelector('[data-modalidad-detalle="grupo"]');

        bloque.querySelector('[data-modalidad-detalle="persona"]').textContent =
            formatoMoneda.format(precio) + ' × ' + cantidad + ' = ' + formatoMoneda.format(precio * cantidad);

        if (grupo) {
            radioGrupo.disabled = false;
            detalleGrupo.textContent = 'De ' + grupo.desde + ' a ' + grupo.hasta + ' personas: ' + formatoMoneda.format(grupo.precio) + ' total';
        } else {
            radioGrupo.disabled = true;
            radioGrupo.checked = false;
            radioPersona.checked = true;
            detalleGrupo.textContent = 'No hay precio de grupo para ' + cantidad + ' personas';
        }
    }

    // Total = Σ (precio de la modalidad elegida × personas de esa experiencia).
    // Cada tarjeta marcada muestra su propio subtotal.
    function actualizarTotal() {
        let suma = 0;
        let sinPrecio = false;

        checks.forEach(function (check) {
            const subtotal = document.querySelector(
                '[data-subtotal="' + check.value + '"]'
            );

            if (!check.checked) {
                if (subtotal) subtotal.hidden = true;
                return;
            }

            const precio = Number(check.dataset.precio) || 0;
            const grupos = JSON.parse(check.dataset.grupos || '[]');
            const bloque = bloques.find(b => b.dataset.experiencia === check.value);
            const campo = bloque ? bloque.querySelector('.personas-exp') : null;
            const cantidad = Math.max(0, Number(campo ? campo.value : 0) || 0);
            actualizarModalidades(bloque, precio, grupos, cantidad);

            const elegida = bloque ? bloque.querySelector('[data-modalidad-radio]:checked') : null;
            const modo = elegida ? elegida.value : (check.dataset.tipoPrecio === 'grupo' ? 'grupo' : 'persona');
            const grupo = modo === 'grupo' ? grupos.find(g => cantidad >= g.desde && cantidad <= g.hasta) : null;
            const monto = modo === 'grupo' ? (grupo ? grupo.precio : 0) : precio * cantidad;
            const sinMonto = modo === 'grupo' ? !grupo : precio <= 0;

            if (sinMonto) {
                sinPrecio = true;
            }

            suma += monto;

            if (subtotal) {
                subtotal.hidden = false;
                subtotal.querySelector('strong').textContent = sinMonto
                    ? 'Por confirmar'
                    : formatoMoneda.format(monto);
            }
        });

        // Sin precio capturado no se muestra $0: se indica que está por confirmar.
        total.textContent = sinPrecio
            ? 'Por confirmar'
            : formatoMoneda.format(suma);

        actualizarResumen();
    }

    // Resumen lateral: nombres de las experiencias y su fecha, conforme se marcan.
    function formatoFecha(valor) {
        const fecha = leerFecha(valor);

        if (!fecha) return '';

        return fecha.toLocaleDateString('es-MX', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function actualizarResumen() {
        const activas = checks.filter(c => c.checked);

        if (resumenActividades) {
            resumenActividades.textContent = activas.length
                ? activas.map(c => c.closest('.mc-c06__tarjeta').dataset.nombre).join(', ')
                : 'Ninguna todavía';
        }

        if (resumenFechas) {
            resumenFechas.textContent = activas.length
                ? activas.map(function (c) {
                    const bloque = bloques.find(b => b.dataset.experiencia === c.value);
                    const valor = bloque ? bloque.querySelector('.calendario-valor').value : '';
                    const texto = valor ? formatoFecha(valor) : 'falta elegir fecha';

                    return c.closest('.mc-c06__tarjeta').dataset.nombre + ': ' + texto;
                }).join(' · ')
                : 'Pendiente';
        }
    }
    bloques.forEach(function (bloque) {
        const campo = bloque.querySelector('.calendario-valor');
        const calendario = bloque.querySelector('.calendario-propio');
        const cuadricula = bloque.querySelector('.calendario-cuadricula');
        const tituloMes = bloque.querySelector('.calendario-mes');
        const anterior = bloque.querySelector('.calendario-anterior');
        const siguiente = bloque.querySelector('.calendario-siguiente');
        const dias = diasPermitidos(bloque);
        const guardada = leerFecha(campo.value);
        let anioVisible = guardada
            ? guardada.getFullYear()
            : manana.getFullYear();
        let mesVisible = guardada
            ? guardada.getMonth()
            : manana.getMonth();
        function dibujar() {
            cuadricula.innerHTML = '';
            const primerDia = new Date(anioVisible, mesVisible, 1);
            const desplazamiento = (primerDia.getDay() + 6) % 7;
            const ultimoDia = new Date(
                anioVisible, mesVisible + 1, 0
            ).getDate();
            tituloMes.textContent = primerDia.toLocaleDateString('es-MX', {
                month: 'long',
                year: 'numeric'
            });
            const mesActual = new Date(
                manana.getFullYear(), manana.getMonth(), 1
            );
            anterior.disabled = primerDia <= mesActual;
            for (let i = 0; i < desplazamiento; i++) {
                cuadricula.appendChild(document.createElement('span'));
            }
            for (let dia = 1; dia <= ultimoDia; dia++) {
                const fecha = new Date(anioVisible, mesVisible, dia);
                const valor = fechaTexto(fecha);
                const disponible = fechaPermitida(fecha, dias, bloque);
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.textContent = dia;
                boton.disabled = !disponible;
                boton.className = disponible
                    ? 'dia-disponible'
                    : 'dia-bloqueado';
                if (campo.value === valor) {
                    boton.classList.add('dia-seleccionado');
                }
                if (disponible) {
                    boton.addEventListener('click', function () {
                        campo.value = valor;
                        campo.setCustomValidity('');
                        calendario.hidden = true;
                        dibujar();
                        campo.dispatchEvent(
                            new Event('change', { bubbles: true })
                        );
                    });
                }
                cuadricula.appendChild(boton);
            }
        }
        campo.addEventListener('click', function () {
            if (campo.disabled) return;
            document.querySelectorAll('.calendario-propio')
                .forEach(function (otro) {
                    if (otro !== calendario) otro.hidden = true;
                });
            calendario.hidden = !calendario.hidden;
            if (!calendario.hidden) dibujar();
        });
        anterior.addEventListener('click', function () {
            const fecha = new Date(anioVisible, mesVisible - 1, 1);
            anioVisible = fecha.getFullYear();
            mesVisible = fecha.getMonth();
            dibujar();
        });
        siguiente.addEventListener('click', function () {
            const fecha = new Date(anioVisible, mesVisible + 1, 1);
            anioVisible = fecha.getFullYear();
            mesVisible = fecha.getMonth();
            dibujar();
        });
        bloque.actualizarCalendario = dibujar;
        dibujar();
    });
    function actualizarExperiencias() {
        let seleccionadas = 0;
        bloques.forEach(function (bloque) {
            const id = bloque.dataset.experiencia;
            const check = checks.find(c => c.value === id);
            const campo = bloque.querySelector('.calendario-valor');
            const calendario = bloque.querySelector('.calendario-propio');
            const activo = Boolean(check && check.checked);
            bloque.hidden = !activo;
            campo.disabled = !activo;
            campo.required = activo;
            bloque.querySelector('.personas-exp').disabled = !activo;
            bloque.querySelector('.notas-exp').disabled = !activo;
            bloque.querySelectorAll('[data-modalidad-radio]').forEach(function (radio) {
                radio.disabled = !activo;
            });
            const horario = bloque.querySelector('.horario-exp');
            if (horario) horario.disabled = !activo;
            if (activo) {
                seleccionadas++;
                filtrarHorarios(bloque);
            } else {
                campo.value = '';
                campo.setCustomValidity('');
                calendario.hidden = true;
                if (horario) horario.value = '';
            }
        });
        mensaje.hidden = seleccionadas > 0;
        actualizarTotal();
    }
    checks.forEach(function (check) {
        check.addEventListener('change', actualizarExperiencias);
    });
    bloques.forEach(function (bloque) {
        bloque.querySelector('.horario-exp')
            .addEventListener('change', function () { ajustarMaxPersonas(bloque); });
        bloque.querySelector('.personas-exp')
            .addEventListener('input', actualizarTotal);
        bloque.querySelectorAll('[data-modalidad-radio]').forEach(function (radio) {
            radio.addEventListener('change', actualizarTotal);
        });
        bloque.querySelector('.calendario-valor')
            .addEventListener('change', function () {
                filtrarHorarios(bloque);
                actualizarResumen();
            });
    });
    form.addEventListener('submit', function (evento) {
        const seleccionadas = checks.filter(c => c.checked);
        if (!seleccionadas.length) {
            evento.preventDefault();
            aviso.textContent = 'Selecciona al menos una experiencia.';
            return;
        }
        for (const bloque of bloques) {
            if (bloque.hidden) continue;
            const campo = bloque.querySelector('.calendario-valor');
            const fecha = leerFecha(campo.value);
            const dias = diasPermitidos(bloque);
            if (!fecha || !fechaPermitida(fecha, dias, bloque)) {
                evento.preventDefault();
                aviso.textContent =
                    'Selecciona una fecha disponible para cada experiencia.';
                campo.setCustomValidity('Selecciona una fecha disponible.');
                campo.reportValidity();
                return;
            }
            campo.setCustomValidity('');

            const horario = bloque.querySelector('.horario-exp');
            if (horario && !horario.value) {
                evento.preventDefault();
                aviso.textContent =
                    'Elige un horario para cada experiencia.';
                horario.reportValidity();
                return;
            }
        }
        aviso.textContent = '';
    });
    actualizarExperiencias();
});
</script>
@endpush
