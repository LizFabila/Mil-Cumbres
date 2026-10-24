<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · Admin Mil Cumbres</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-menu-milcumbres.css') }}">
    @stack('estilos')
</head>
<body class="admin mc-admin-body">
@php
    $pendientes = \App\Models\Solicitud::where('estado', 'pendiente')->count();
    $pagosPendientes = \App\Models\Pago::where('estado', 'pendiente')->count();
    $opinionesPorAtender = \App\Models\Calificacion::requierenAtencion()->count();
    $tituloActual = match (true) {
        request()->routeIs('admin.resumen') => 'Panel general',
        request()->routeIs('admin.solicitudes.*') => 'Solicitudes',
        request()->routeIs('admin.pagos.*') => 'Gestión de pagos',
        request()->routeIs('admin.calendario') => 'Calendario',
        request()->routeIs('admin.consultorias.*') => 'Consultorías',
        request()->routeIs('admin.experiencias.*') => 'Experiencias',
        request()->routeIs('admin.usuarios.*') => 'Usuarios',
        request()->routeIs('admin.marca.*') => 'Identidad del sitio',
        request()->routeIs('admin.calificaciones.*') => 'Opiniones',
        default => 'Administración',
    };
@endphp
{{-- Iconos SVG reutilizables: no necesitan librerías externas. --}}
<svg xmlns="http://www\.w3.org/2000/svg" aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">
    <symbol id="mc-i-dashboard" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></symbol>
    <symbol id="mc-i-request" viewBox="0 0 24 24"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4.5h6M8 10h8M8 14h8M8 18h5"/></symbol>
    <symbol id="mc-i-payment" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/></symbol>
    <symbol id="mc-i-calendar" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18M8 14h3M14 14h3"/></symbol>
    <symbol id="mc-i-chat" viewBox="0 0 24 24"><path d="M20 11.5a8 8 0 0 1-8 8 9 9 0 0 1-4-.9L3 20l1.5-4A8 8 0 1 1 20 11.5Z"/><path d="M8 10h8M8 14h5"/></symbol>
    <symbol id="mc-i-leaf" viewBox="0 0 24 24"><path d="M20 4C9 4 4 9 4 16a4 4 0 0 0 4 4c7 0 12-5 12-16ZM4 20c3-6 7-9 12-12"/></symbol>
    <symbol id="mc-i-star" viewBox="0 0 24 24"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9Z"/></symbol>
    <symbol id="mc-i-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2M17 5a3 3 0 0 1 0 6M17 14a5 5 0 0 1 4 5v1"/></symbol>
    <symbol id="mc-i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="mc-i-logout" viewBox="0 0 24 24"><path d="M9 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4M14 8l4 4-4 4M8 12h10"/></symbol>
</svg>
<div class="mc-adm-shell">
    <button type="button" class="mc-adm-backdrop" aria-label="Cerrar menú administrativo" data-mc-adm-close hidden></button>
    <aside class="mc-adm-sidebar" id="mc-admin-sidebar">
        <div class="mc-adm-brand">
            <div class="mc-adm-brand__logo">
                <x-logo :href="route('admin.marca.edit')" etiqueta="Editar nombre y logotipo del sitio" />
            </div>
            <button class="mc-adm-sidebar-close" type="button" aria-label="Cerrar menú" data-mc-adm-close>×</button>
            <span class="mc-adm-brand__subtitle">PANEL DE ADMINISTRACIÓN</span>
        </div>
        <nav class="mc-adm-nav" aria-label="Administración Mil Cumbres">
            <a href="{{ route('admin.resumen') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.resumen')])>
                <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-dashboard"/></svg></span>
                <span class="mc-adm-nav__name">Resumen general</span>
            </a>
            <section class="mc-adm-group" aria-label="Gestión de reservaciones y pagos">
                <h2 class="mc-adm-group__title">GESTIÓN DE RESERVACIONES Y PAGOS</h2>
                <div class="mc-adm-group__content">
                    <a href="{{ route('admin.solicitudes.index') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.solicitudes.*')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-request"/></svg></span>
                        <span class="mc-adm-nav__name">Solicitudes</span>
                        @if($pendientes > 0)<span class="mc-adm-nav__count">{{ $pendientes }}</span>@endif
                    </a>
                    <a href="{{ route('admin.pagos.index') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.pagos.*')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-payment"/></svg></span>
                        <span class="mc-adm-nav__name">Pagos</span>
                        @if($pagosPendientes > 0)<span class="mc-adm-nav__count">{{ $pagosPendientes }}</span>@endif
                    </a>
                    <a href="{{ route('admin.calendario') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.calendario')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-calendar"/></svg></span>
                        <span class="mc-adm-nav__name">Calendario</span>
                    </a>
                    <a href="{{ route('admin.consultorias.index') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.consultorias.*')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-chat"/></svg></span>
                        <span class="mc-adm-nav__name">Consultorías</span>
                    </a>
                    <a href="{{ route('admin.calificaciones.index') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.calificaciones.*')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-star"/></svg></span>
                        <span class="mc-adm-nav__name">Opiniones</span>
                        @if($opinionesPorAtender > 0)<span class="mc-adm-nav__count">{{ $opinionesPorAtender }}</span>@endif
                    </a>
                </div>
            </section>
            <section class="mc-adm-group" aria-label="Administración del catálogo y usuarios">
                <h2 class="mc-adm-group__title">ADMINISTRACIÓN DE CATÁLOGO Y USUARIOS</h2>
                <div class="mc-adm-group__content">
                    <a href="{{ route('admin.experiencias.index') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.experiencias.*')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-leaf"/></svg></span>
                        <span class="mc-adm-nav__name">Experiencias</span>
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" @class(['mc-adm-nav__link', 'is-active' => request()->routeIs('admin.usuarios.*')])>
                        <span class="mc-adm-nav__icon" aria-hidden="true"><svg><use href="#mc-i-users"/></svg></span>
                        <span class="mc-adm-nav__name">Usuarios</span>
                    </a>
                </div>
            </section>
        </nav>
        <div class="mc-adm-sidebar-bottom">
            <div class="mc-adm-profile">
                <div class="mc-adm-profile__identity" style="padding:4px 2px 12px;">
                    <strong>{{ auth()->user()->nombre_completo }}</strong>
                    <small>Administrador</small>
                </div>
            </div>
            <form class="mc-adm-logout" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><svg aria-hidden="true"><use href="#mc-i-logout"/></svg>Cerrar sesión</button>
            </form>
        </div>
    </aside>
    <div class="mc-adm-workspace">
        <header class="mc-adm-topbar">
            <div class="mc-adm-topbar__left">
                <button type="button" class="mc-adm-mobile-toggle" aria-label="Abrir menú administrativo" aria-controls="mc-admin-sidebar" aria-expanded="false" data-mc-adm-toggle><span></span><span></span><span></span></button>
                <div class="mc-adm-topbar__location"><span>MIL CUMBRES / ADMINISTRACIÓN</span><strong>{{ $tituloActual }}</strong></div>
            </div>
            <div class="mc-adm-topbar__actions">
                <a href="{{ route('admin.solicitudes.index') }}" class="mc-adm-topbar__indicator" title="Consultar solicitudes"><span class="mc-adm-topbar__indicator-icon" aria-hidden="true">▤</span><span>Solicitudes <strong>{{ $pendientes }}</strong></span></a>
                <a href="{{ route('admin.pagos.index') }}" class="mc-adm-topbar__indicator" title="Consultar pagos"><span class="mc-adm-topbar__indicator-icon" aria-hidden="true">◈</span><span>Pagos <strong>{{ $pagosPendientes }}</strong></span></a>
                <a href="{{ route('inicio') }}" class="mc-adm-topbar__site">Ver vista cliente <span aria-hidden="true">↗</span></a>
            </div>
        </header>
        <main class="mc-adm-main" id="contenido-administrativo">
            <x-avisos />
            @yield('contenido')
        </main>
        <footer class="mc-adm-footer"><span>© {{ date('Y') }} Mil Cumbres</span><span>ADMINISTRACIÓN · TURISMO REGENERATIVO</span></footer>
    </div>
</div>
<script src="{{ asset('js/admin-menu-milcumbres.js') }}" defer></script>
@stack('scripts')
</body>
</html>
