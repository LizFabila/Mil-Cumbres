{{-- C07 · Acuse de solicitud
     Tablas: solicitudes, asigna_experiencia, experiencias --}}
@extends('layouts.app')

@section('titulo', 'Solicitud enviada')

@section('contenido')
<div class="contenedor contenedor--angosto seccion">
    <div class="tarjeta tarjeta--sombra acuse">
        <div class="acuse__icono" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
        </div>
        <h1 class="titulo-seccion">¡Recibimos tu solicitud!</h1>
        <p class="texto-suave mb-0">Guarda tu folio para darle seguimiento.</p>
        <div class="folio">{{ $solicitud->folio }}</div>

        <dl class="datos">
            <div>
                <div>
        <dt>Personas</dt>
        <dd>{{ $solicitud->cant_persona }}</dd>
    </div>

    <div>
        <dt>Experiencias y fechas</dt>
        <dd>
            <ul>
                @foreach ($solicitud->experiencias as $experiencia)
                    <li>
                        <strong>{{ $experiencia->nombre }}</strong> —
                        @php
                            $fechaExperiencia =
                                $experiencia->pivot->fecha_deseada
                                ?? $solicitud->fecha_deseada;
                        @endphp

                        {{ \Carbon\Carbon::parse($fechaExperiencia)->translatedFormat('l j \d\e F \d\e Y') }}
                    </li>
                @endforeach
            </ul>
        </dd>
    </div>
            <div>
                <dt>Total estimado</dt>
                <dd>${{ number_format($solicitud->total, 2) }} MXN</dd>
            </div>
        </dl>

        <ol class="pasos">
            <li><strong>Revisamos disponibilidad.</strong> <span class="texto-suave">En menos de 24 h te confirmamos la fecha.</span></li>
            <li><strong>Registras tu pago.</strong> <span class="texto-suave">Cuando esté confirmada, sube tu comprobante desde “Mis solicitudes”.</span></li>
            <li><strong>¡A la montaña!</strong> <span class="texto-suave">Te enviaremos el punto de encuentro.</span></li>
        </ol>

        <div class="fila mt-3" >
            <a class="btn btn--primario" href="{{ route('solicitudes.index') }}">Ver mis solicitudes</a>
            <a class="btn btn--secundario" href="{{ route('experiencias.index') }}">Seguir explorando</a>
        </div>
    </div>
</div>
@endsection
