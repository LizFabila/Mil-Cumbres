{{-- A05 · Calendario
     Tablas: solicitudes, asigna_experiencia, experiencias, personas --}}
@extends('layouts.admin')

@section('titulo', 'Calendario')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Calendario</h1>
        <p>Solicitudes pendientes, confirmadas y completadas por fecha deseada.</p>
    </div>
    <div class="fila">
        <a class="btn btn--secundario btn--chico" href="{{ route('admin.calendario', ['mes' => $mes->copy()->subMonth()->format('Y-m')]) }}">‹ Anterior</a>
        <strong class="subtitulo">{{ ucfirst($mes->translatedFormat('F Y')) }}</strong>
        <a class="btn btn--secundario btn--chico" href="{{ route('admin.calendario', ['mes' => $mes->copy()->addMonth()->format('Y-m')]) }}">Siguiente ›</a>
        <a class="btn btn--texto btn--chico" href="{{ route('admin.calendario') }}">Hoy</a>
    </div>
</div>

<div class="calendario" role="grid">
    @foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $d)
        <div class="calendario__dia-semana" role="columnheader">{{ $d }}</div>
    @endforeach

    @foreach ($dias as $dia)
        @php $del_dia = $solicitudes->get($dia->format('Y-m-d'), collect()); @endphp
        <div @class(['calendario__celda', 'fuera' => ! $dia->isSameMonth($mes), 'hoy' => $dia->isToday()]) role="gridcell">
            <span class="calendario__num">{{ $dia->day }}</span>
            @foreach ($del_dia as $s)
                <a class="evento evento--{{ $s->estado }}" href="{{ route('admin.solicitudes.show', $s) }}"
                   title="{{ $s->folio }} · {{ $s->persona?->nombre_completo }}">
                    {{ $s->cant_persona }}p · {{ $s->experiencias->pluck('nombre')->implode(' + ') }}
                </a>
            @endforeach
        </div>
    @endforeach
</div>

<div class="fila mt-2 texto-chico">
    <x-estado valor="pendiente" /> <x-estado valor="confirmada" /> <x-estado valor="completada" />
</div>
@endsection
