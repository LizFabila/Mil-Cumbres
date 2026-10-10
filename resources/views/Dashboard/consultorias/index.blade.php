{{-- A04 · Consultorías
     Tablas: solicitud_consultorias, organizaciones, tipo_organizaciones, personas, seguimiento_consultorias --}}
@extends('layouts.admin')

@section('titulo', 'Consultorías')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Consultorías</h1>
        <p>Solicitudes de empresas y proyectos agrarios.</p>
    </div>
</div>

<form class="barra-filtros" method="GET">
    <div class="campo">
        <label for="estado">Estado</label>
        <select class="control" id="estado" name="estado">
            <option value="">Todos</option>
            @foreach (\App\Models\SolicitudConsultoria::ESTADOS as $e)
                <option value="{{ $e }}" @selected(($filtros['estado'] ?? null) === $e)>{{ ucfirst(str_replace('_', ' ', $e)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="campo">
        <label for="tipo">Tipo</label>
        <select class="control" id="tipo" name="tipo">
            <option value="">Todos</option>
            @foreach ($tipos as $t)
                <option value="{{ $t->id_tipo_organizacion }}" @selected(($filtros['tipo'] ?? null) == $t->id_tipo_organizacion)>{{ $t->nombre }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn--primario" type="submit">Filtrar</button>
</form>

<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr><th>Fecha</th><th>Organización</th><th>Contacto</th><th>Ubicación</th><th class="num">Seguim.</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($consultorias as $c)
            <tr>
                <td>{{ $c->fecha_solicitud->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $c->organizacion?->nombre }}</strong>
                    <div class="texto-chico texto-suave">{{ $c->organizacion?->tipo?->nombre }}</div>
                </td>
                <td>
                    {{ $c->persona?->nombre_completo }}
                    <div class="texto-chico texto-suave">{{ $c->persona?->email }}</div>
                </td>
                <td>{{ $c->ubicacion }}</td>
                <td class="num">{{ $c->seguimientos_count }}</td>
                <td><x-estado :valor="$c->estado" /></td>
                <td><a class="btn btn--secundario btn--chico" href="{{ route('admin.consultorias.show', $c) }}">Abrir</a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="vacio">No hay solicitudes de consultoría.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $consultorias->links() }}
@endsection
