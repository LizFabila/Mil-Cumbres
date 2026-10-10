{{-- C08 · Mis solicitudes
     Tablas: solicitudes, asigna_experiencia, experiencias, pagos --}}
@extends('layouts.app')

@section('titulo', 'Mis solicitudes')

@section('contenido')
<div class="contenedor seccion">
    <div class="fila fila--entre fila--abajo">
        <div>
            <h1 class="titulo-seccion mb-0">Mis solicitudes</h1>
            <p class="texto-suave mb-0">Hola, {{ auth()->user()->nombre }}. Aquí ves el estado de tus reservaciones.</p>
        </div>
        <a class="btn btn--primario" href="{{ route('solicitudes.create') }}">Nueva solicitud</a>
    </div>

    <div class="mt-3"><x-avisos /></div>

    @if ($solicitudes->isEmpty())
        <div class="tarjeta vacio">
            Todavía no tienes solicitudes.
            <a href="{{ route('experiencias.index') }}">Explora las experiencias</a>.
        </div>
    @else
        <div class="tarjeta">
            @foreach ($solicitudes as $s)
                <article class="solicitud-item">
                    <div>
                        <div class="fila">
                            <span class="mono texto-suave">{{ $s->folio }}</span>
                            <x-estado :valor="$s->estado" />
                            @if ($s->ultimoPago)
                                <span class="texto-chico texto-suave">Pago:</span>
                                <x-estado :valor="$s->ultimoPago->estado" />
                            @endif
                        </div>
                        <h3>{{ $s->experiencias->pluck('nombre')->implode(' + ') }}</h3>
                        <p class="texto-suave texto-chico mb-0">
                            {{ $s->fecha_deseada->translatedFormat('j M Y') }} ·
                            {{ $s->cant_persona }} {{ $s->cant_persona == 1 ? 'persona' : 'personas' }} ·
                            <strong>${{ number_format($s->total, 2) }} MXN</strong>
                        </p>
                    </div>

                    <div class="solicitud-item__acciones">
                        @if ($s->puedePagar())
                            <a class="btn btn--primario btn--chico" href="{{ route('pagos.create', $s) }}">
                                {{ $s->ultimoPago?->estado === 'rechazado' ? 'Reenviar comprobante' : 'Registrar pago' }}
                            </a>
                        @endif

                        @if ($s->estado === 'pendiente')
                            <form method="POST" action="{{ route('solicitudes.cancelar', $s) }}"
                                  onsubmit="return confirm('¿Cancelar la solicitud {{ $s->folio }}?')">
                                @csrf @method('PATCH')
                                <button class="btn btn--peligro btn--chico" type="submit">Cancelar</button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        {{ $solicitudes->links() }}
    @endif
</div>
@endsection
