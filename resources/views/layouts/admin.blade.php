<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · Admin Mil Cumbres</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('estilos')
</head>
<body class="admin">
@php
    $pendientes = \App\Models\Solicitud::where('estado', 'pendiente')->count();
    $pagosPendientes = \App\Models\Pago::where('estado', 'pendiente')->count();
@endphp
<div class="admin-layout">
    <aside class="lateral">
        <x-logo />

        <nav aria-label="Panel">
            <a href="{{ route('admin.resumen') }}" @class(['activo' => request()->routeIs('admin.resumen')])>Resumen</a>

            <div class="lateral__grupo">Operación</div>
            <a href="{{ route('admin.solicitudes.index') }}" @class(['activo' => request()->routeIs('admin.solicitudes.*')])>
                Solicitudes @if ($pendientes)<span class="contador">{{ $pendientes }}</span>@endif
            </a>
            <a href="{{ route('admin.pagos.index') }}" @class(['activo' => request()->routeIs('admin.pagos.*')])>
                Pagos @if ($pagosPendientes)<span class="contador">{{ $pagosPendientes }}</span>@endif
            </a>
            <a href="{{ route('admin.calendario') }}" @class(['activo' => request()->routeIs('admin.calendario')])>Calendario</a>
            <a href="{{ route('admin.consultorias.index') }}" @class(['activo' => request()->routeIs('admin.consultorias.*')])>Consultorías</a>

            <div class="lateral__grupo">Catálogo</div>
            <a href="{{ route('admin.experiencias.index') }}" @class(['activo' => request()->routeIs('admin.experiencias.*')])>Experiencias</a>
            <a href="{{ route('admin.usuarios.index') }}" @class(['activo' => request()->routeIs('admin.usuarios.*')])>Usuarios</a>
        </nav>

        <div class="lateral__pie">
            {{ auth()->user()->nombre_completo }}<br>
            <a href="{{ route('inicio') }}">Ver sitio</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <x-avisos />
        @yield('contenido')
    </main>
</div>
@stack('scripts')
</body>
</html>
