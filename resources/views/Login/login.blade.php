
{{-- C04 · Iniciar sesión
     Tablas: personas, roles
--}}

@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('descripcion',
    'Accede a tu cuenta de Mil Cumbres para gestionar tus experiencias y solicitudes.'
)

@push('estilos')
    <link rel="stylesheet"
          href="{{ asset('css/login-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-login">

    <div class="mc-login__layout">

        {{-- ======================================= --}}
        {{-- 01 · PANEL FOTOGRÁFICO --}}
        {{-- ======================================= --}}

        <section class="mc-login__visual"
                 aria-label="Bienvenida a Mil Cumbres">

            <img
                src="{{ config('milcumbres.imagenes.acceso') }}"
                alt="Paisaje natural de Valle de Bravo"
                class="mc-login__imagen"
                fetchpriority="high"
            >

            <div class="mc-login__overlay"></div>

            {{-- SUPERIOR --}}

            <div class="mc-login__visual-superior">

                <a href="{{ route('inicio') }}"
                   class="mc-login__regresar">

                    <span aria-hidden="true">←</span>

                    Volver al inicio

                </a>

                <span class="mc-login__visual-numero">
                    MIL CUMBRES
                </span>

            </div>


            {{-- CONTENIDO EDITORIAL --}}

            <div class="mc-login__visual-contenido">

                <span class="mc-login__eyebrow
                             mc-login__eyebrow--claro">

                    <span class="mc-login__linea"></span>

                    UNA FORMA DIFERENTE DE VIAJAR

                </span>


                <h2>
                    El camino
                    <em>siempre tiene</em>
                    algo nuevo
                    que mostrarte.
                </h2>

                <p>
                    Conecta con la naturaleza,
                    descubre nuevas experiencias
                    y continúa tu historia
                    con Mil Cumbres.
                </p>

                <div class="mc-login__visual-cita">

                    <span aria-hidden="true">“</span>

                    <blockquote>
                        La montaña no se conquista;
                        se cuida.
                    </blockquote>

                </div>

            </div>


            {{-- PIE DE FOTOGRAFÍA --}}

            <div class="mc-login__visual-pie">

                <span>
                    VALLE DE BRAVO · MÉXICO
                </span>

                <span>
                    NATURALEZA / BIENESTAR / CULTURA
                </span>

            </div>

        </section>


        {{-- ======================================= --}}
        {{-- 02 · ÁREA DEL FORMULARIO --}}
        {{-- ======================================= --}}

        <section class="mc-login__formulario">

            <div class="mc-login__formulario-inner">

                {{-- IDENTIDAD DE LA SECCIÓN --}}

                <div class="mc-login__identidad">


                    <span>
                        MIL CUMBRES
                        <small>
                            TURISMO REGENERATIVO
                        </small>
                    </span>

                </div>


                {{-- INTRODUCCIÓN --}}

                <div class="mc-login__encabezado">

                    <span class="mc-login__eyebrow">

                        <span class="mc-login__linea"></span>

                        TU ESPACIO PERSONAL

                    </span>

                    <h1>
                        Qué gusto
                        <em>tenerte de vuelta.</em>
                    </h1>

                    <p>
                        Inicia sesión para solicitar
                        nuevas experiencias y consultar
                        el seguimiento de tus reservaciones
                        y consultorías.
                    </p>

                </div>


                {{-- AVISOS EXISTENTES DEL SISTEMA --}}

                <div class="mc-login__mensajes">
                    <x-avisos />
                </div>


                {{-- ================================= --}}
                {{-- FORMULARIO ORIGINAL --}}
                {{-- ================================= --}}

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="mc-login__form"
                    novalidate
                >

                    @csrf


                    {{-- CORREO ELECTRÓNICO --}}

                    <div class="mc-login__campo">

                        <label for="email">
                            Correo electrónico
                            <span aria-hidden="true">*</span>
                        </label>

                        <div class="mc-login__input-contenedor">

                            <input
                                @class([
                                    'mc-login__input',
                                    'control',
                                    'es-invalido' => $errors->has('email')
                                ])

                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                placeholder="ejemplo@correo.com"
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                @if($errors->has('email'))
                                    aria-describedby="mc-login-error-email"
                                @endif
                                required
                                autofocus
                            >

                            <span class="mc-login__input-icono"
                                  aria-hidden="true">
                                @
                            </span>

                        </div>

                        @error('email')

                            <span
                                class="mc-login__error error"
                                id="mc-login-error-email"
                                role="alert"
                            >
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- CONTRASEÑA --}}

                    <div class="mc-login__campo">

                        <label for="contrasena">
                            Contraseña
                            <span aria-hidden="true">*</span>
                        </label>

                        <div class="mc-login__password
                                    control-con-boton">

                            <input
                                @class([
                                    'mc-login__input',
                                    'control',
                                    'es-invalido' =>
                                        $errors->has('contrasena')
                                ])

                                type="password"
                                id="contrasena"
                                name="contrasena"
                                autocomplete="current-password"
                                placeholder="Escribe tu contraseña"
                                aria-invalid="{{ $errors->has('contrasena') ? 'true' : 'false' }}"
                                @if($errors->has('contrasena'))
                                    aria-describedby="mc-login-error-contrasena"
                                @endif
                                required
                            >

                            {{-- Se conserva el JS existente --}}
                            <button
                                type="button"
                                class="ver-contrasena mc-login__mostrar"
                                data-ver="contrasena"
                                aria-pressed="false"
                                aria-label="Mostrar contraseña"
                            >
                                Mostrar
                            </button>

                        </div>

                        @error('contrasena')

                            <span
                                class="mc-login__error error"
                                id="mc-login-error-contrasena"
                                role="alert"
                            >
                                {{ $message }}
                            </span>

                        @enderror

                        <a class="mc-login__olvide" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>

                    </div>


                    {{-- ACCIÓN PRINCIPAL --}}

                    <button
                        class="mc-login__submit"
                        type="submit"
                    >

                        <span>
                            Iniciar sesión
                        </span>

                        <span class="mc-login__submit-flecha"
                              aria-hidden="true">
                            ↗
                        </span>

                    </button>

                    <p class="mc-login__form-nota">
                        Accede a tu cuenta para continuar
                        con tus solicitudes de Mil Cumbres.
                    </p>

                </form>


                {{-- ================================= --}}
                {{-- REGISTRO DE USUARIOS --}}
                {{-- ================================= --}}

                <div class="mc-login__registro">


                    <div>

                        <span class="mc-login__registro-etiqueta">
                            ¿ES TU PRIMERA VISITA?
                        </span>

                        <h2>
                            Tu próxima aventura
                            comienza aquí.
                        </h2>

                        <p>
                            Crea una cuenta para solicitar
                            experiencias y dar seguimiento
                            a tus actividades.
                        </p>

                        <a href="{{ route('registro') }}"
                           class="mc-login__registro-enlace">

                            Crear una cuenta

                            <span aria-hidden="true">↗</span>

                        </a>

                    </div>

                </div>


                {{-- ENLACE PARA EXPLORAR SIN CUENTA --}}

                <div class="mc-login__explorar">

                    <span>
                        ¿Solo quieres descubrir nuestras actividades?
                    </span>

                    <a href="{{ route('experiencias.index') }}">

                        Explorar experiencias

                        <span aria-hidden="true">→</span>

                    </a>

                </div>


                {{-- PIE INTERNO --}}

                <div class="mc-login__pie">

                    <span>
                        © {{ date('Y') }} MIL CUMBRES
                    </span>

                    <a href="{{ route('privacidad') }}">
                        Aviso de privacidad
                    </a>

                </div>

            </div>

        </section>

    </div>

</div>

@endsection


{{-- ======================================= --}}
{{-- SCRIPT EXISTENTE PARA CONTRASEÑA --}}
{{-- ======================================= --}}

@push('scripts')

    <script
        src="{{ asset('js/ver-contrasena.js') }}"
        defer
    ></script>

@endpush
