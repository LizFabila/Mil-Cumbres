
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Turismo Regenerativo') · Mil Cumbres</title>

    <meta name="description"
          content="@yield('descripcion', 'Experiencias de naturaleza, bienestar y cultura en Valle de Bravo.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sitio.css') }}">

    {{-- CSS del nuevo menú --}}
    <link rel="stylesheet" href="{{ asset('css/menu-milcumbres.css') }}">

    {{-- Rediseño: paleta, encabezado verde bosque y animaciones --}}
    <link rel="stylesheet" href="{{ asset('css/rediseno-milcumbres.css') }}">

    @stack('estilos')
</head>

<body>

@php
    // Categorías reales de la base de datos.
    // Se consultan sus identificadores para construir enlaces válidos.
    $categoriasMenu = \App\Models\CatalogoExperiencia::query()
        ->orderBy('id_catalogo_exp')
        ->get(['id_catalogo_exp', 'tipo']);

    $descripcionesMenu = [
        'Natural' => 'Senderos, paisajes y aventuras al aire libre.',
        'Bienestar' => 'Descanso, equilibrio y conexión personal.',
        'Cultural' => 'Tradiciones, historia y conocimientos locales.',
    ];
@endphp

{{-- ======================================= --}}
{{-- ENCABEZADO MIL CUMBRES --}}
{{-- ======================================= --}}

<header class="mc-hub" id="mc-hub">

    {{-- FRANJA DE IDENTIDAD --}}
    <div class="mc-hub__ribbon">
        <div class="contenedor mc-hub__ribbon-inner">

            <span class="mc-hub__ribbon-marca">
                MIL CUMBRES · TURISMO REGENERATIVO
            </span>

            {{-- CONTACTO DIRECTO (teléfono, redes, correo) --}}
            <x-contacto-rapido />

        </div>
    </div>

    {{-- NAVEGACIÓN PRINCIPAL --}}
    <div class="contenedor mc-hub__bar">

        <div class="mc-hub__brand">
            <x-logo />
        </div>

        {{-- BOTÓN MÓVIL --}}
        <button
            type="button"
            class="mc-hub__mobile-toggle"
            aria-label="Abrir menú principal"
            aria-controls="mc-hub-links"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="mc-hub__drawer" id="mc-hub-links">

            <nav class="mc-hub__nav"
                 aria-label="Navegación principal">

                {{-- INICIO --}}
                <a href="{{ route('inicio') }}"
                   @class([
                       'mc-hub__nav-link',
                       'is-active' => request()->routeIs('inicio')
                   ])>
                    Inicio
                </a>

                {{-- ================================= --}}
                {{-- DESPLEGABLE DE EXPERIENCIAS --}}
                {{-- ================================= --}}

                <div class="mc-hub__group">

                    <button
                        type="button"
                        class="mc-hub__nav-link mc-hub__nav-button"
                        data-mc-menu-toggle
                        aria-controls="mc-hub-experiencias"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        Experiencias
                        <span class="mc-hub__chevron"
                              aria-hidden="true"></span>
                    </button>

                    <div
                        id="mc-hub-experiencias"
                        class="mc-hub__dropdown mc-hub__dropdown--experiencias"
                        hidden
                    >
                        <div class="mc-hub__dropdown-content">

                            <div class="mc-hub__dropdown-heading">

                                <div>
                                    <span class="mc-hub__eyebrow">
                                        DESCUBRE VALLE DE BRAVO
                                    </span>

                                    <h2>
                                        Experiencias para
                                        <em>cada forma de viajar.</em>
                                    </h2>
                                </div>

                                <p>
                                    Naturaleza, cultura y bienestar.
                                    Explora nuestras experiencias
                                    y encuentra tu próxima aventura.
                                </p>

                            </div>

                            {{-- CATEGORÍAS CON FOTOGRAFÍAS --}}

                            <div class="mc-hub__category-grid">

                                @foreach ($categoriasMenu as $categoria)

                                    <a
                                        class="mc-hub__category"
                                        href="{{ route('experiencias.index', ['tipo' => $categoria->id_catalogo_exp]) }}#catalogo-resultados"
                                    >

                                        <img
                                            src="{{ config("milcumbres.imagenes.tipo.{$categoria->tipo}", config('milcumbres.imagenes.experiencia_default')) }}"
                                            alt="Experiencias de {{ $categoria->tipo }}"
                                            loading="lazy"
                                        >

                                        <span class="mc-hub__category-shade"></span>

                                        <span class="mc-hub__category-content">

                                            <strong>{{ $categoria->tipo }}</strong>

                                            <small>
                                                {{ $descripcionesMenu[$categoria->tipo] ?? 'Descubre esta categoría de experiencias.' }}
                                            </small>

                                            <span class="mc-hub__category-arrow">
                                                Explorar <b>↗</b>
                                            </span>

                                        </span>

                                    </a>

                                @endforeach

                            </div>

                            {{-- ACCESOS RÁPIDOS --}}

                            <div class="mc-hub__quick-links">

                                <a href="{{ route('experiencias.index') }}#catalogo-resultados">
                                    <span>Ver todo el catálogo</span>
                                    <span aria-hidden="true">↗</span>
                                </a>

                                @auth
                                    <a href="{{ route('solicitudes.create') }}#mc-c06-formulario">
                                        <span>Solicitar una experiencia</span>
                                        <span aria-hidden="true">↗</span>
                                    </a>

                                    <a href="{{ route('solicitudes.index', ['seccion' => 'reservaciones']) }}">
                                        <span>Mis reservaciones</span>
                                        <span aria-hidden="true">↗</span>
                                    </a>
                                @else
                                    <a href="{{ route('registro') }}">
                                        <span>Crear cuenta para reservar</span>
                                        <span aria-hidden="true">↗</span>
                                    </a>
                                @endauth

                            </div>

                        </div>
                    </div>

                </div>

                {{-- ================================= --}}
                {{-- DESPLEGABLE DE CONSULTORÍA --}}
                {{-- ================================= --}}

                <div class="mc-hub__group">

                    <button
                        type="button"
                        class="mc-hub__nav-link mc-hub__nav-button"
                        data-mc-menu-toggle
                        aria-controls="mc-hub-consultoria"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        Consultoría
                        <span class="mc-hub__chevron"
                              aria-hidden="true"></span>
                    </button>

                    <div
                        id="mc-hub-consultoria"
                        class="mc-hub__dropdown"
                        hidden
                    >

                        <div class="mc-hub__consult-grid">

                            <div class="mc-hub__consult-photo">

                                <img
                                    src="{{ config('milcumbres.imagenes.consultoria') }}"
                                    alt="Proyectos de turismo regenerativo"
                                    loading="lazy"
                                >

                                <div class="mc-hub__consult-photo-text">
                                    <span>CRECER CON EL TERRITORIO</span>
                                    <strong>
                                        El futuro también
                                        se construye juntos.
                                    </strong>
                                </div>

                            </div>

                            <div class="mc-hub__consult-content">

                                <span class="mc-hub__eyebrow">
                                    CONSULTORÍA REGENERATIVA
                                </span>

                                <h2>
                                    Transformemos
                                    <em>las ideas en proyectos.</em>
                                </h2>

                                <p>
                                    Acompañamos a empresas
                                    y organizaciones agrarias
                                    interesadas en el turismo
                                    regenerativo.
                                </p>

                                <div class="mc-hub__option-list">

                                    <a href="{{ route('consultoria.index') }}">
                                        <span>
                                            <strong>Conoce el servicio</strong>
                                            <small>Descubre nuestra propuesta de consultoría.</small>
                                        </span>
                                        <b>↗</b>
                                    </a>

                                    @auth

                                        <a href="{{ route('consultoria.create') }}#mc-c11-form">
                                            <span>
                                                <strong>Solicitar consultoría</strong>
                                                <small>Registra las necesidades de tu organización.</small>
                                            </span>
                                            <b>↗</b>
                                        </a>

                                        <a href="{{ route('solicitudes.index', ['seccion' => 'consultorias']) }}">
                                            <span>
                                                <strong>Mis consultorías</strong>
                                                <small>Consulta estados y comentarios de seguimiento.</small>
                                            </span>
                                            <b>↗</b>
                                        </a>

                                    @else

                                        <a href="{{ route('registro') }}">
                                            <span>
                                                <strong>Solicitar consultoría</strong>
                                                <small>Crea una cuenta para enviar tu solicitud.</small>
                                            </span>
                                            <b>↗</b>
                                        </a>

                                    @endauth

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- NUESTRO MODELO --}}
                <a href="{{ route('modelo') }}"
                   @class([
                       'mc-hub__nav-link',
                       'is-active' => request()->routeIs('modelo')
                   ])>
                    Nuestro modelo
                </a>

            </nav>


            {{-- ================================= --}}
            {{-- OPCIONES DE CUENTA --}}
            {{-- ================================= --}}

            <div class="mc-hub__actions">

                <div class="mc-hub__group mc-hub__group--account">

                    <button
                        type="button"
                        class="mc-hub__account"
                        data-mc-menu-toggle
                        aria-controls="mc-hub-cuenta"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >

                        <span class="mc-hub__account-icon"
                              aria-hidden="true">
                            ♙
                        </span>

                        <span class="mc-hub__account-label">
                            @guest
                                Mi cuenta
                            @else
                                {{ auth()->user()->nombre }}
                            @endguest
                        </span>

                        <span class="mc-hub__chevron"
                              aria-hidden="true"></span>

                    </button>

                    <div
                        id="mc-hub-cuenta"
                        class="mc-hub__dropdown mc-hub__dropdown--account"
                        hidden
                    >

                        @guest

                            <div class="mc-hub__account-heading">
                                <span class="mc-hub__eyebrow">
                                    BIENVENIDO A MIL CUMBRES
                                </span>

                                <h3>Tu aventura comienza aquí.</h3>

                                <p>
                                    Accede a tu cuenta o regístrate
                                    para solicitar experiencias.
                                </p>
                            </div>

                            <div class="mc-hub__account-options">

                                <a href="{{ route('login') }}">
                                    Iniciar sesión <span>↗</span>
                                </a>

                                <a href="{{ route('registro') }}">
                                    Crear cuenta <span>↗</span>
                                </a>

                            </div>

                        @else

                            <div class="mc-hub__account-heading">

                                <span class="mc-hub__eyebrow">
                                    TU ESPACIO PERSONAL
                                </span>

                                <h3>
                                    Hola, {{ auth()->user()->nombre }}.
                                </h3>

                                <p>
                                    Administra tus solicitudes
                                    y consulta tus actividades.
                                </p>

                            </div>

                            <div class="mc-hub__account-options">

                                <a href="{{ route('solicitudes.index') }}">
                                    Mis solicitudes <span>↗</span>
                                </a>

                                <a href="{{ route('solicitudes.create') }}">
                                    Nueva reservación <span>↗</span>
                                </a>

                                <a href="{{ route('consultoria.create') }}#mc-c11-form">
                                    Solicitar consultoría <span>↗</span>
                                </a>

                                @if (auth()->user()->esAdmin())

                                    <a href="{{ route('admin.resumen') }}">
                                        Panel administrativo <span>↗</span>
                                    </a>

                                @endif

                            </div>

                            <form method="POST"
                                  action="{{ route('logout') }}"
                                  class="mc-hub__logout">

                                @csrf

                                <button type="submit">
                                    Cerrar sesión <span>→</span>
                                </button>

                            </form>

                        @endguest

                    </div>

                </div>

                {{-- BOTÓN DESTACADO DEL ENCABEZADO --}}
                @auth

                    <a href="{{ route('solicitudes.create') }}"
                       class="mc-hub__primary-cta">
                        Reservar
                        <span aria-hidden="true">↗</span>
                    </a>

                @else

                    <a href="{{ route('experiencias.index') }}"
                       class="mc-hub__primary-cta">
                        Explorar
                        <span aria-hidden="true">↗</span>
                    </a>

                @endauth

            </div>

        </div>

    </div>

</header>


{{-- ======================================= --}}
{{-- CONTENIDO DE LAS PÁGINAS --}}
{{-- ======================================= --}}

<main id="contenido-principal">
    @yield('contenido')
</main>


{{-- ======================================= --}}
{{-- PIE DE PÁGINA ORIGINAL --}}
{{-- ======================================= --}}

<footer class="pie">

    <div class="contenedor">

        <span>
            Mil Cumbres ·
            {{ config('milcumbres.contacto.lugar') }}
        </span>

        <a href="{{ route('privacidad') }}">
            Aviso de privacidad
        </a>

    </div>

</footer>


{{-- SCRIPT DEL MENÚ INTERACTIVO --}}
<script src="{{ asset('js/menu-milcumbres.js') }}" defer></script>
<script src="{{ asset('js/reveal-milcumbres.js') }}" defer></script>
<script src="{{ asset('js/acentos-milcumbres.js') }}" defer></script>

@stack('scripts')

</body>
</html>
