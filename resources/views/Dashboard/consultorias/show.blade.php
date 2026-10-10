{{-- A04 · Detalle de consultoría con seguimiento
     Tablas: solicitud_consultorias, organizaciones, tipo_organizaciones, personas, seguimiento_consultorias --}}
@extends('layouts.admin')

@section('titulo', 'Consultoría')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.consultorias.index') }}">← Consultorías</a></p>
        <h1>{{ $consultoria->organizacion?->nombre }}</h1>
        <p>{{ $consultoria->organizacion?->tipo?->nombre }} · {{ $consultoria->ubicacion }}</p>
    </div>

    <form method="POST" action="{{ route('admin.consultorias.estado', $consultoria) }}" class="fila">
        @csrf @method('PATCH')
        <label class="oculto-visual" for="estado">Estado</label>
        <select class="select-inline" id="estado" name="estado">
            @foreach (\App\Models\SolicitudConsultoria::ESTADOS as $e)
                <option value="{{ $e }}" @selected($consultoria->estado === $e)>{{ ucfirst(str_replace('_', ' ', $e)) }}</option>
            @endforeach
        </select>
        <button class="btn btn--secundario btn--chico" type="submit">Cambiar estado</button>
    </form>
</div>

<div class="detalle-admin">
    <div>
        <section class="panel">
            <h2>Necesidad</h2>
            <p class="texto-suave mb-0">{!! nl2br(e($consultoria->necesidad)) !!}</p>
        </section>

        <section class="panel">
            <h2>Seguimiento</h2>
            <form method="POST" action="{{ route('admin.consultorias.seguimiento', $consultoria) }}" class="pila">
                @csrf
                <div class="campo">
                    <label for="comentario">Nuevo comentario</label>
                    <textarea class="control" id="comentario" name="comentario" maxlength="2000" required
                              placeholder="Llamada, visita, propuesta enviada…">{{ old('comentario') }}</textarea>
                </div>
                <button class="btn btn--primario" type="submit">Agregar seguimiento</button>
            </form>

            <ul class="linea-tiempo mt-4">
                @forelse ($consultoria->seguimientos as $seg)
                    <li>
                        <time datetime="{{ $seg->fecha->toIso8601String() }}">{{ $seg->fecha->format('d/m/Y H:i') }}</time>
                        <p class="mb-0">{!! nl2br(e($seg->comentario)) !!}</p>
                    </li>
                @empty
                    <li class="texto-suave">Sin seguimiento todavía.</li>
                @endforelse
            </ul>
        </section>
    </div>

    <section class="panel">
        <h2>Contacto</h2>
        <p class="mb-0"><strong>{{ $consultoria->persona?->nombre_completo }}</strong></p>
        <p class="mb-0"><a href="mailto:{{ $consultoria->persona?->email }}">{{ $consultoria->persona?->email }}</a></p>
        @if ($consultoria->persona?->telefono)
            <p class="mb-0"><a href="tel:{{ $consultoria->persona->telefono }}">{{ $consultoria->persona->telefono }}</a></p>
        @endif
        <p class="texto-chico texto-suave mt-2 mb-0">Recibida el {{ $consultoria->fecha_solicitud->format('d/m/Y H:i') }}</p>
        <p class="mt-2 mb-0"><x-estado :valor="$consultoria->estado" /></p>
    </section>
</div>
@endsection
