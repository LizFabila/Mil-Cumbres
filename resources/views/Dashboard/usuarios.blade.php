{{-- A09 · Usuarios
     Tablas: personas, roles, solicitudes, solicitud_consultorias --}}
@extends('layouts.admin')

@section('titulo', $vista === 'cartera' ? 'Cartera de clientes' : 'Usuarios')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-cartera-milcumbres.css') }}">
@endpush

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>{{ $vista === 'cartera' ? 'Cartera de clientes' : 'Usuarios registrados' }}</h1>
        <p>{{ $vista === 'cartera'
            ? 'Clientes agrupados por tipo de servicio y frecuencia de solicitud.'
            : 'Personas registradas y su rol.' }}</p>
    </div>
    <form method="GET" class="fila">
        <input type="hidden" name="vista" value="{{ $vista }}">
        <label class="oculto-visual" for="q">Buscar</label>
        <input class="control" id="q" name="q" value="{{ $q }}" placeholder="Nombre o correo">
        <button class="btn btn--primario" type="submit">Buscar</button>
    </form>
</div>

<nav class="pestanas" aria-label="Vistas de usuarios">
    <a href="{{ route('admin.usuarios.index', ['vista' => 'cartera', 'q' => $q]) }}"
       @class(['activo' => $vista === 'cartera']) @if ($vista === 'cartera') aria-current="page" @endif>Cartera de clientes</a>
    <a href="{{ route('admin.usuarios.index', ['vista' => 'registrados', 'q' => $q]) }}"
       @class(['activo' => $vista === 'registrados']) @if ($vista === 'registrados') aria-current="page" @endif>Todos los registrados</a>
</nav>

@if ($vista === 'cartera')
    @include('Dashboard.usuarios._cartera')
@else
    @include('Dashboard.usuarios._registrados')
@endif
@endsection
