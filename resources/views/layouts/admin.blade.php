
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('titulo', 'Panel') · Admin Mil Cumbres
    </title>

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet"
          href="{{ asset('css/base.css') }}">

    <link rel="stylesheet"
          href="{{ asset('css/admin.css') }}">

    <link rel="stylesheet"
          href="{{ asset('css/admin-menu-milcumbres.css') }}">

    @stack('estilos')

</head>

<body class="admin mc-admin-body">

@php
    $pendientes = \App\Models\Solicitud::where(
        'estado', 'pendiente'
    )->count();

    $pagosPendientes = \App\Models\Pago::where(
        'estado', 'pendiente'
    )->count();

    $operacionActiva = request()->routeIs(
        'admin.solicitudes.*',
        'admin.pagos.*',
        'admin.calendario',
        'admin.consultorias.*'
    );

    $catalogoActivo = request()->routeIs(
        'admin.experiencias.*',
        'admin.usuarios.*'
    );

    $tituloActual = match (true) {
        request()->routeIs('admin.resumen')
            => 'Panel general',

        request()->routeIs('admin.solicitudes.*')
            => 'Solicitudes',

        request()->routeIs('admin.pagos.*')
            => 'Gestión de pagos',

        request()->routeIs('admin.calendario')
            => 'Calendario',

        request()->routeIs('admin.consultorias.*')
            => 'Consultorías',

        request()->routeIs('admin.experiencias.*')
            => 'Experiencias',

        request()->routeIs('admin.usuarios.*')
            => 'Usuarios',

        request()->routeIs('admin.marca.*')
            => 'Identidad del sitio',

        default => 'Administración'
    };
@endphp

<div class="mc-adm-shell">

    {{-- ======================================= --}}
    {{-- CAPA OSCURA PARA MÓVILES --}}
    {{-- ======================================= --}}

    <button
        type="button"
        class="mc-adm-backdrop"
        aria-label="Cerrar menú administrativo"
        data-mc-adm-close
        hidden
    ></button>


    {{-- ======================================= --}}
    {{-- MENÚ LATERAL --}}
    {{-- ======================================= --}}

    <aside class="mc-adm-sidebar"
           id="mc-admin-sidebar">

        {{-- IDENTIDAD --}}

        <div class="mc-adm-brand">

            <div class="mc-adm-brand__logo">
                <x-logo :href="route('admin.marca.edit')" etiqueta="Editar nombre y logotipo del sitio" />
            </div>

            <button
                class="mc-adm-sidebar-close"
                type="button"
                aria-label="Cerrar menú"
                data-mc-adm-close
            >
                ×
            </button>

            <span class="mc-adm-brand__subtitle">
                CENTRO DE ADMINISTRACIÓN
            </span>

        </div>


        {{-- MENSAJE DE IDENTIDAD --}}

        <div class="mc-adm-intro">

            <span class="mc-adm-intro__label">
                ESPACIO DE TRABAJO
            </span>

            <strong>
                Gestionamos
                <em>con propósito.</em>
            </strong>

            <p>
                Control de experiencias,
                reservaciones y proyectos.
            </p>

        </div>


        {{-- ================================= --}}
        {{-- NAVEGACIÓN --}}
        {{-- ================================= --}}

        <nav class="mc-adm-nav"
             aria-label="Administración Mil Cumbres">

            {{-- RESUMEN --}}

            <a href="{{ route('admin.resumen') }}"
               @class([
                   'mc-adm-nav__link',
                   'is-active' => request()->routeIs(
                       'admin.resumen'
                   )
               ])>

                <span class="mc-adm-nav__icon"
                      aria-hidden="true">
                    ◫
                </span>

                <span class="mc-adm-nav__name">
                    Resumen general
                </span>

                <span class="mc-adm-nav__arrow"
                      aria-hidden="true">
                    ↗
                </span>

            </a>


            {{-- ================================= --}}
            {{-- GRUPO OPERACIÓN --}}
            {{-- ================================= --}}

            <details
                class="mc-adm-group"
                @if ($operacionActiva) open @endif
            >

                <summary class="mc-adm-group__title">

                    <span>
                        OPERACIÓN
                    </span>

                    <span class="mc-adm-group__chevron"
                          aria-hidden="true">
                        ⌄
                    </span>

                </summary>

                <div class="mc-adm-group__content">

                    {{-- SOLICITUDES --}}

                    <a
                        href="{{ route('admin.solicitudes.index') }}"
                        @class([
                            'mc-adm-nav__link',
                            'is-active' =>
                                request()->routeIs(
                                    'admin.solicitudes.*'
                                )
                        ])
                    >

                        <span class="mc-adm-nav__icon"
                              aria-hidden="true">
                            ▤
                        </span>

                        <span class="mc-adm-nav__name">
                            Solicitudes
                        </span>

                        @if ($pendientes > 0)

                            <span class="mc-adm-nav__count">
                                {{ $pendientes }}
                            </span>

                        @endif

                    </a>


                    {{-- PAGOS --}}

                    <a
                        href="{{ route('admin.pagos.index') }}"
                        @class([
                            'mc-adm-nav__link',
                            'is-active' =>
                                request()->routeIs(
                                    'admin.pagos.*'
                                )
                        ])
                    >

                        <span class="mc-adm-nav__icon"
                              aria-hidden="true">
                            ◈
                        </span>

                        <span class="mc-adm-nav__name">
                            Pagos
                        </span>

                        @if ($pagosPendientes > 0)

                            <span class="mc-adm-nav__count">
                                {{ $pagosPendientes }}
                            </span>

                        @endif

                    </a>


                    {{-- CALENDARIO --}}

                    <a
                        href="{{ route('admin.calendario') }}"
                        @class([
                            'mc-adm-nav__link',
                            'is-active' =>
                                request()->routeIs(
                                    'admin.calendario'
                                )
                        ])
                    >

                        <span class="mc-adm-nav__icon"
                              aria-hidden="true">
                            ▦
                        </span>

                        <span class="mc-adm-nav__name">
                            Calendario
                        </span>

                    </a>


                    {{-- CONSULTORÍAS --}}

                    <a
                        href="{{ route('admin.consultorias.index') }}"
                        @class([
                            'mc-adm-nav__link',
                            'is-active' =>
                                request()->routeIs(
                                    'admin.consultorias.*'
                                )
                        ])
                    >


                        <span class="mc-adm-nav__name">
                            Consultorías
                        </span>

                    </a>

                </div>

            </details>


            {{-- ================================= --}}
            {{-- GRUPO CATÁLOGO --}}
            {{-- ================================= --}}

            <details
                class="mc-adm-group"
                @if ($catalogoActivo) open @endif
            >

                <summary class="mc-adm-group__title">

                    <span>
                        CATÁLOGO Y USUARIOS
                    </span>

                    <span class="mc-adm-group__chevron"
                          aria-hidden="true">
                        ⌄
                    </span>

                </summary>

                <div class="mc-adm-group__content">

                    {{-- EXPERIENCIAS --}}

                    <a
                        href="{{ route('admin.experiencias.index') }}"
                        @class([
                            'mc-adm-nav__link',
                            'is-active' =>
                                request()->routeIs(
                                    'admin.experiencias.*'
                                )
                        ])
                    >

                        <span class="mc-adm-nav__icon"
                              aria-hidden="true">
                            ◇
                        </span>

                        <span class="mc-adm-nav__name">
                            Experiencias
                        </span>

                    </a>


                    {{-- USUARIOS --}}

                    <a
                        href="{{ route('admin.usuarios.index') }}"
                        @class([
                            'mc-adm-nav__link',
                            'is-active' =>
                                request()->routeIs(
                                    'admin.usuarios.*'
                                )
                        ])
                    >

                        <span class="mc-adm-nav__icon"
                              aria-hidden="true">
                            ♙
                        </span>

                        <span class="mc-adm-nav__name">
                            Usuarios
                        </span>

                    </a>

                </div>

            </details>


            {{-- ================================= --}}
            {{-- ACCIONES RÁPIDAS DESPLEGABLES --}}
            {{-- ================================= --}}

            <details class="mc-adm-group mc-adm-group--quick">

                <summary class="mc-adm-group__title">

                    <span>
                        ACCIONES RÁPIDAS
                    </span>

                    <span class="mc-adm-group__chevron"
                          aria-hidden="true">
                        ⌄
                    </span>

                </summary>

                <div class="mc-adm-quick-actions">

                    <a href="{{ route('admin.experiencias.create') }}">

                        <span>+</span>

                        Nueva experiencia

                    </a>

                    <a href="{{ route(
                        'admin.solicitudes.index',
                        ['estado' => 'pendiente']
                    ) }}">

                        <span>↗</span>

                        Solicitudes pendientes

                    </a>

                    <a href="{{ route(
                        'admin.pagos.index',
                        ['estado' => 'pendiente']
                    ) }}">

                        <span>↗</span>

                        Pagos por revisar

                    </a>

                    <a href="{{ route(
                        'admin.consultorias.index',
                        ['estado' => 'recibida']
                    ) }}">

                        <span>↗</span>

                        Nuevas consultorías

                    </a>

                </div>

            </details>

        </nav>


        {{-- ================================= --}}
        {{-- IDENTIDAD INFERIOR --}}
        {{-- ================================= --}}

        <div class="mc-adm-sidebar-bottom">

            <div class="mc-adm-sidebar-message">


                <strong>
                    Mil Cumbres
                </strong>

                <p>
                    Naturaleza, comunidad
                    y experiencias con propósito.
                </p>

            </div>


            {{-- USUARIO DESPLEGABLE --}}

            <details class="mc-adm-profile">

                <summary>

                    <span class="mc-adm-profile__avatar">
                        {{ \Illuminate\Support\Str::upper(
                            \Illuminate\Support\Str::substr(
                                auth()->user()->nombre ?? 'A',
                                0,
                                1
                            )
                        ) }}
                    </span>

                    <span class="mc-adm-profile__identity">

                        <strong>
                            {{ auth()->user()->nombre_completo }}
                        </strong>

                        <small>
                            Administrador
                        </small>

                    </span>

                    <span class="mc-adm-profile__chevron"
                          aria-hidden="true">
                        ⌄
                    </span>

                </summary>

                <div class="mc-adm-profile__options">

                    <a href="{{ route('inicio') }}">
                        Ver sitio público
                        <span aria-hidden="true">↗</span>
                    </a>

                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit">

                            Cerrar sesión

                            <span aria-hidden="true">→</span>

                        </button>

                    </form>

                </div>

            </details>

        </div>

    </aside>


    {{-- ======================================= --}}
    {{-- ÁREA PRINCIPAL --}}
    {{-- ======================================= --}}

    <div class="mc-adm-workspace">

        {{-- BARRA SUPERIOR --}}

        <header class="mc-adm-topbar">

            <div class="mc-adm-topbar__left">

                <button
                    type="button"
                    class="mc-adm-mobile-toggle"
                    aria-label="Abrir menú administrativo"
                    aria-controls="mc-admin-sidebar"
                    aria-expanded="false"
                    data-mc-adm-toggle
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="mc-adm-topbar__location">

                    <span>
                        MIL CUMBRES / ADMINISTRACIÓN
                    </span>

                    <strong>
                        {{ $tituloActual }}
                    </strong>

                </div>

            </div>


            {{-- NOTIFICACIONES Y ACCIONES --}}

            <div class="mc-adm-topbar__actions">

                <a
                    href="{{ route('admin.solicitudes.index') }}"
                    class="mc-adm-topbar__indicator"
                    title="Consultar solicitudes"
                >

                    <span class="mc-adm-topbar__indicator-icon"
                          aria-hidden="true">
                        ▤
                    </span>

                    <span>
                        Solicitudes
                        <strong>{{ $pendientes }}</strong>
                    </span>

                </a>


                <a
                    href="{{ route('admin.pagos.index') }}"
                    class="mc-adm-topbar__indicator"
                    title="Consultar pagos"
                >

                    <span class="mc-adm-topbar__indicator-icon"
                          aria-hidden="true">
                        ◈
                    </span>

                    <span>
                        Pagos
                        <strong>{{ $pagosPendientes }}</strong>
                    </span>

                </a>


                <a
                    href="{{ route('inicio') }}"
                    class="mc-adm-topbar__site"
                >

                    Ver vista cliente

                    <span aria-hidden="true">↗</span>

                </a>

            </div>

        </header>


        {{-- FRANJA DE PRESENTACIÓN --}}

        @unless($__env->hasSection('sin-contexto'))
        <div class="mc-adm-context">

            <span class="mc-adm-context__eyebrow">
                CENTRO DE CONTROL
            </span>

            <div class="mc-adm-context__inner">

                <div>

                    <h2>
                        {{ $tituloActual }}
                    </h2>

                    <p>
                        Gestiona la información y las operaciones
                        de Mil Cumbres desde un solo lugar.
                    </p>

                </div>


            </div>

        </div>


        @endunless

        {{-- CONTENIDO DE CADA VISTA --}}

        <main class="mc-adm-main"
              id="contenido-administrativo">

            <x-avisos />

            @yield('contenido')

        </main>


        {{-- PIE DEL PANEL --}}

        <footer class="mc-adm-footer">

            <span>
                © {{ date('Y') }} Mil Cumbres
            </span>

            <span>
                ADMINISTRACIÓN · TURISMO REGENERATIVO
            </span>

        </footer>

    </div>

</div>


<script
    src="{{ asset('js/admin-menu-milcumbres.js') }}"
    defer
></script>

@stack('scripts')

</body>
</html>
