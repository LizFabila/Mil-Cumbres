
@extends('layouts.app')

@section('titulo', 'Solicitar experiencia')

@section('contenido')

<div class="contenedor contenedor--angosto seccion">

    <nav class="migas mt-0" aria-label="Migas de pan">
        <a href="{{ route('experiencias.index') }}">Experiencias</a>
        / Solicitar
    </nav>

    <h1 class="titulo-seccion">Solicitar experiencia</h1>

    <p class="texto-suave">
        Elige una o varias experiencias y selecciona una fecha
        para cada una. Te confirmaremos la disponibilidad antes
        de pedirte el pago.
    </p>

    <x-avisos />

    <form method="POST"
          action="{{ route('solicitudes.store') }}"
          class="tarjeta pila mt-3"
          id="form-solicitud">

        @csrf

        <fieldset class="pila" aria-describedby="ayuda-exp">
            <legend class="subtitulo">1. Experiencias</legend>

            <span class="ayuda" id="ayuda-exp">
                El total se calcula según el precio por persona,
                el número de personas y las experiencias seleccionadas.
            </span>

            @php
                $marcadas = array_map(
                    'intval',
                    (array) old('experiencias', $seleccionadas ?? [])
                );
            @endphp

            @foreach ($experiencias as $exp)
                <label class="opcion-exp">
                    <input
                        type="checkbox"
                        name="experiencias[]"
                        value="{{ $exp->id_experiencia }}"
                        data-precio="{{ $exp->precio }}"
                        @checked(in_array($exp->id_experiencia, $marcadas))
                    >

                    <img src="{{ $exp->imagen_url }}"
                         alt=""
                         loading="lazy">

                    <span>
                        <strong>{{ $exp->nombre }}</strong>
                        <small>
                            {{ $exp->zona?->nombre }}
                            · {{ $exp->duracion_texto }}

                            @if ($exp->disponibilidades->isNotEmpty())
                                · {{ $exp->disponibilidades->map->dia_texto->unique()->implode(', ') }}
                            @endif
                        </small>
                    </span>

                    <strong>{{ $exp->precio_texto }}</strong>
                </label>
            @endforeach

            @error('experiencias')
                <span class="error">{{ $message }}</span>
            @enderror

            @error('experiencias.*')
                <span class="error">{{ $message }}</span>
            @enderror
        </fieldset>

        <fieldset class="form-grid">
            <legend class="subtitulo completo">
                2. Fechas y personas
            </legend>

            <div class="campo completo">
                <label>Fecha para cada experiencia</label>

                <p class="ayuda">
                    Selecciona una fecha en verde para cada experiencia.
                    Los días grises no están disponibles.
                </p>

                <div id="fechas-experiencias" class="pila">

                    @foreach ($experiencias as $exp)
                        <div
                            class="campo fecha-experiencia"
                            data-experiencia="{{ $exp->id_experiencia }}"
                            data-dias="{{ $exp->disponibilidades->pluck('dias_semana')->unique()->implode(',') }}"
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
                                value="{{ old('fechas.' . $exp->id_experiencia) }}"
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

                            <small class="ayuda">
                                Días disponibles:
                                {{ $exp->disponibilidades->map->dia_texto->unique()->implode(', ') ?: 'Sin horarios disponibles' }}
                            </small>

                            @error('fechas.' . $exp->id_experiencia)
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <p id="mensaje-seleccion" class="ayuda" aria-live="polite">
                    Selecciona una experiencia para elegir su fecha.
                </p>
            </div>

            <div class="campo">
                <label for="cant_persona">Número de personas</label>

                <input
                    class="control"
                    type="number"
                    id="cant_persona"
                    name="cant_persona"
                    value="{{ old('cant_persona', $personas ?? 1) }}"
                    min="1"
                    max="500"
                    required
                >

                @error('cant_persona')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="campo completo">
                <label for="nota">
                    Nota <span class="ayuda">(opcional)</span>
                </label>

                <textarea
                    class="control"
                    id="nota"
                    name="nota"
                    maxlength="1000"
                    placeholder="Alergias, movilidad reducida, si viajan con niños…"
                >{{ old('nota') }}</textarea>

                @error('nota')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </fieldset>

        <div class="resumen-total">
            <span class="texto-suave">Total estimado</span>
            <strong id="total">$0.00 MXN</strong>
        </div>

        <p id="aviso-dia" class="ayuda" aria-live="polite"></p>

        <button class="btn btn--primario btn--bloque" type="submit">
            Enviar solicitud
        </button>

        <p class="ayuda centrado mb-0">
            Aún no se cobra nada. Recibirás un folio
            para dar seguimiento.
        </p>
    </form>
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
        color: #274c39;
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
        color: #53635a;
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
        background: #d7f1df;
        color: #14643a;
        cursor: pointer;
    }

    .calendario-cuadricula .dia-disponible:hover {
        background: #a5dfb9;
    }

    .calendario-cuadricula .dia-bloqueado {
        background: #f2f2f2;
        color: #aaa;
        cursor: not-allowed;
    }

    .calendario-cuadricula .dia-seleccionado {
        background: #176447;
        color: white;
        font-weight: bold;
    }

    .calendario-ayuda {
        margin: 12px 0 0;
        font-size: 11px;
        color: #65736b;
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

    const personas = document.getElementById('cant_persona');
    const total = document.getElementById('total');
    const aviso = document.getElementById('aviso-dia');
    const mensaje = document.getElementById('mensaje-seleccion');

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

    function fechaPermitida(fecha, dias) {
        return fecha >= manana &&
            dias.includes(normalizar(nombresDias[fecha.getDay()]));
    }

    function actualizarTotal() {
        const cantidad = Math.max(0, Number(personas.value) || 0);

        const suma = checks.reduce(function (acumulado, check) {
            return acumulado +
                (check.checked ? Number(check.dataset.precio) || 0 : 0);
        }, 0);

        total.textContent = formatoMoneda.format(suma * cantidad);
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
                const disponible = fechaPermitida(fecha, dias);

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

            if (activo) {
                seleccionadas++;
            } else {
                campo.value = '';
                campo.setCustomValidity('');
                calendario.hidden = true;
            }
        });

        mensaje.hidden = seleccionadas > 0;
        actualizarTotal();
    }

    checks.forEach(function (check) {
        check.addEventListener('change', actualizarExperiencias);
    });

    personas.addEventListener('input', actualizarTotal);

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

            if (!fecha || !fechaPermitida(fecha, dias)) {
                evento.preventDefault();
                aviso.textContent =
                    'Selecciona una fecha disponible para cada experiencia.';
                campo.setCustomValidity('Selecciona una fecha disponible.');
                campo.reportValidity();
                return;
            }

            campo.setCustomValidity('');
        }

        aviso.textContent = '';
    });

    actualizarExperiencias();
});
</script>
@endpush
