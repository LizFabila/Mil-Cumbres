{{-- C12 · Acuse de consultoría
     Tablas: solicitud_consultorias, organizaciones, tipo_organizaciones --}}
@extends('layouts.app')

@section('titulo', 'Consultoría solicitada')

@section('contenido')
<div class="contenedor contenedor--angosto seccion">
    <div class="tarjeta tarjeta--sombra acuse">
        <div class="acuse__icono" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
        </div>
        <h1 class="titulo-seccion">Recibimos tu solicitud de consultoría</h1>
        <p class="texto-suave mb-0">Número de seguimiento</p>
        <div class="folio">CON-{{ str_pad($consultoria->id_solicitud_consultoria, 5, '0', STR_PAD_LEFT) }}</div>

        <dl class="datos">
            <div><dt>Organización</dt><dd>{{ $consultoria->organizacion->nombre }}</dd></div>
            <div><dt>Tipo</dt><dd>{{ $consultoria->organizacion->tipo->nombre }}</dd></div>
            <div><dt>Ubicación</dt><dd>{{ $consultoria->ubicacion }}</dd></div>
            <div><dt>Estado</dt><dd><x-estado :valor="$consultoria->estado" /></dd></div>
        </dl>

        <ol class="pasos">
            <li><strong>Revisamos tu necesidad.</strong> <span class="texto-suave">Un consultor la lee en los próximos 2 días hábiles.</span></li>
            <li><strong>Te contactamos.</strong> <span class="texto-suave">Agendamos una llamada o visita al lugar.</span></li>
            <li><strong>Propuesta.</strong> <span class="texto-suave">Recibes un plan de trabajo con alcance y costos.</span></li>
        </ol>

        <div class="fila mt-3">
            <a class="btn btn--primario" href="{{ route('inicio') }}">Volver al inicio</a>
            <a class="btn btn--secundario" href="{{ route('experiencias.index') }}">Ver experiencias</a>
        </div>
    </div>
</div>
@endsection
