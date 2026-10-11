
{{-- C08 · Mis solicitudes y consultorías
     Tablas:
     solicitudes, asigna_experiencia, experiencias, pagos,
     solicitud_consultorias, organizaciones
--}}

@extends('layouts.app')

@section('titulo', 'Mis solicitudes')

@section('contenido')
<div class="contenedor seccion">

    {{-- ENCABEZADO --}}
    <div class="fila fila--entre fila--abajo">
        <div>
            <h1 class="titulo-seccion mb-0">Mis solicitudes</h1>
            <p class="texto-suave mb-0">
                Hola, {{ auth()->user()->nombre }}.
                Consulta el estado de tus reservaciones y consultorías.
            </p>
        </div>

        <a class="btn btn--primario"
           href="{{ route('solicitudes.create') }}">
            Nueva solicitud
        </a>
    </div>

    <div class="mt-3">
        <x-avisos />
    </div>

    {{-- ======================================= --}}
    {{-- SECCIÓN 1: RESERVACIONES DE EXPERIENCIAS --}}
    {{-- ======================================= --}}

    <section class="mt-3">

        <h2 class="titulo-seccion">
            Mis reservaciones
        </h2>

        <p class="texto-suave">
            Consulta las experiencias turísticas que has solicitado.
        </p>

        @if ($solicitudes->isEmpty())

            <div class="tarjeta vacio">
                Todavía no tienes solicitudes de experiencias.
                <a href="{{ route('experiencias.index') }}">
                    Explora las experiencias
                </a>.
            </div>

        @else

            <div class="tarjeta">

                @foreach ($solicitudes as $s)

                    <article class="solicitud-item">

                        <div>

                            <div class="fila">

                                <span class="mono texto-suave">
                                    {{ $s->folio }}
                                </span>

                                <x-estado :valor="$s->estado" />

                                @if ($s->ultimoPago)
                                    <span class="texto-chico texto-suave">
                                        Pago:
                                    </span>

                                    <x-estado
                                        :valor="$s->ultimoPago->estado" />
                                @endif

                            </div>

                            <h3>
                                {{ $s->experiencias->pluck('nombre')->implode(' + ') }}
                            </h3>

                            <p class="texto-suave texto-chico mb-0">

                                {{ $s->fecha_deseada->translatedFormat('j M Y') }}

                                ·

                                {{ $s->cant_persona }}
                                {{ $s->cant_persona == 1 ? 'persona' : 'personas' }}

                                ·

                                <strong>
                                    ${{ number_format($s->total, 2) }} MXN
                                </strong>

                            </p>

                        </div>

                        <div class="solicitud-item__acciones">

                            @if ($s->puedePagar())

                                <a class="btn btn--primario btn--chico"
                                   href="{{ route('pagos.create', $s) }}">

                                    {{ $s->ultimoPago?->estado === 'rechazado'
                                        ? 'Reenviar comprobante'
                                        : 'Registrar pago' }}

                                </a>

                            @endif

                            @if ($s->estado === 'pendiente')

                                <form method="POST"
                                      action="{{ route('solicitudes.cancelar', $s) }}"
                                      onsubmit="return confirm('¿Cancelar la solicitud {{ $s->folio }}?')">

                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn--peligro btn--chico"
                                            type="submit">
                                        Cancelar
                                    </button>

                                </form>

                            @endif

                        </div>

                    </article>

                @endforeach

            </div>

            {{ $solicitudes->links() }}

        @endif

    </section>


    {{-- ======================================= --}}
    {{-- SECCIÓN 2: CONSULTORÍAS DEL USUARIO --}}
    {{-- ======================================= --}}

    <section class="mt-3">

        <div class="fila fila--entre fila--abajo">

            <div>

                <h2 class="titulo-seccion mb-0">
                    Mis consultorías
                </h2>

                <p class="texto-suave mb-0">
                    Revisa las consultorías que has solicitado
                    y el estado de atención de cada una.
                </p>

            </div>

            <span class="texto-suave texto-chico">
                {{ $consultorias->count() }}
                {{ $consultorias->count() === 1
                    ? 'consultoría registrada'
                    : 'consultorías registradas' }}
            </span>

        </div>

        @if ($consultorias->isEmpty())

            <div class="tarjeta vacio mt-3">

                <h3>Sin consultorías registradas</h3>

                <p class="texto-suave mb-0">
                    Aún no has realizado ninguna solicitud
                    de consultoría.
                </p>

            </div>

        @else

            <div class="tarjeta mt-3">

                @foreach ($consultorias as $c)

                    @php
                        $estadoConsultoria = match ($c->estado) {
                            'recibida' => 'Recibida',
                            'en_proceso' => 'En proceso',
                            'atendida' => 'Atendida',
                            'cerrada' => 'Cerrada',
                            default => ucfirst($c->estado),
                        };
                    @endphp

                    <article class="solicitud-item">

                        <div>

                            {{-- IDENTIFICADOR Y ESTADO --}}

                            <div class="fila">

                                <span class="mono texto-suave">
                                    Consultoría #{{ $c->id_solicitud_consultoria }}
                                </span>

                                <span class="texto-chico texto-suave">
                                    Estado:
                                    <strong>
                                        {{ $estadoConsultoria }}
                                    </strong>
                                </span>

                            </div>


                            {{-- ORGANIZACIÓN --}}

                            <h3>
                                {{ $c->nombre_organizacion ?? 'Organización no disponible' }}
                            </h3>


                            {{-- UBICACIÓN --}}

                            <p class="texto-suave texto-chico">
                                <strong>Ubicación:</strong>
                                {{ $c->ubicacion }}
                            </p>


                            {{-- NECESIDAD REGISTRADA --}}

                            <p class="texto-suave texto-chico">
                                <strong>Necesidad:</strong>
                                {{ $c->necesidad }}
                            </p>


                            
                            {{-- FECHA DE SOLICITUD --}}

                            <p class="texto-suave texto-chico mb-0">

                                <strong>Fecha de solicitud:</strong>

                                {{ \Carbon\Carbon::parse($c->fecha_solicitud)->translatedFormat('d M Y') }}

                            </p>


                            {{-- ====================================== --}}
                            {{-- SEGUIMIENTO DE LA CONSULTORÍA --}}
                            {{-- ====================================== --}}

                            <div class="mt-3">

                                <h4 class="mb-2">
                                    Seguimiento de la consultoría
                                </h4>

                                @php
                                    $comentarios = $seguimientos->get(
                                        $c->id_solicitud_consultoria,
                                        collect()
                                    );
                                @endphp

                                @if ($comentarios->isEmpty())

                                    <div class="texto-suave texto-chico">

                                        Aún no hay comentarios de seguimiento
                                        para esta consultoría.

                                    </div>

                                @else

                                    @foreach ($comentarios as $seguimiento)

                                        <div class="mt-2">

                                            <p class="texto-suave texto-chico mb-1">

                                                <strong>Comentario:</strong>

                                                {{ $seguimiento->comentario }}

                                            </p>

                                            <p class="texto-suave texto-chico mb-0">

                                                <strong>Fecha de seguimiento:</strong>

                                                {{ \Carbon\Carbon::parse($seguimiento->fecha)->translatedFormat('d M Y, H:i') }}

                                            </p>

                                        </div>

                                        @if (!$loop->last)
                                            <hr>
                                        @endif

                                    @endforeach

                                @endif

                            </div>

                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection
