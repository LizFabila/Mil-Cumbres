<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Turismo Regenerativo') · Mil Cumbres</title>
    <meta name="description" content="@yield('descripcion', 'Experiencias de bienestar, naturaleza y cultura en Valle de Bravo.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sitio.css') }}">
    @stack('estilos')
</head>
<body>
<header class="encabezado" id="encabezado">
    <div class="contenedor encabezado__barra">
        <x-logo />

        <button class="menu-toggle" type="button" aria-label="Abrir menú" aria-controls="encabezado"
                onclick="this.closest('.encabezado').classList.toggle('abierto')">
            <span></span><span></span><span></span>
        </button>

        <nav class="menu" aria-label="Principal">
            <a href="{{ route('inicio') }}" @class(['activo' => request()->routeIs('inicio')])>Inicio</a>
            <a href="{{ route('experiencias.index') }}" @class(['activo' => request()->routeIs('experiencias.*', 'solicitudes.create')])>Experiencias</a>
            <a href="{{ route('consultoria.index') }}" @class(['activo' => request()->routeIs('consultoria.*')])>Consultoría</a>
            <a href="{{ route('modelo') }}" @class(['activo' => request()->routeIs('modelo')])>Nuestro modelo</a>
        </nav>

        <div class="acciones">
            @guest
                <a class="enlace" href="{{ route('login') }}">Ingresar</a>
                <a class="btn btn--primario btn--chico" href="{{ route('registro') }}">Crear cuenta</a>
            @else
                @if (auth()->user()->esAdmin())
                    <a class="enlace" href="{{ route('admin.resumen') }}">Panel</a>
                @endif
                <a class="enlace" href="{{ route('solicitudes.index') }}">Mis solicitudes</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="enlace" type="submit">Salir</button>
                </form>
                <a class="btn btn--primario btn--chico" href="{{ route('solicitudes.create') }}">Solicitar</a>
            @endguest
        </div>
    </div>
</header>

<main>
    @yield('contenido')
</main>

<footer class="pie">
    <div class="contenedor">
        <span>Mil Cumbres · {{ config('milcumbres.contacto.lugar') }}</span>
        <a href="{{ route('privacidad') }}">Aviso de privacidad</a>
    </div>
</footer>

@stack('scripts')
</body>
</html>
