
{{-- C05 · Registro de usuarios
     Tablas: personas, roles
--}}

@extends('layouts.app')

@section('titulo', 'Crear cuenta')

@section('descripcion',
    'Crea tu cuenta en Mil Cumbres para solicitar experiencias turísticas y consultar tus reservaciones.'
)

@push('estilos')
<link rel="stylesheet"
      href="{{ asset('css/registro-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-registro">

    <div class="mc-registro__layout">

        {{-- ======================================= --}}
        {{-- 01 · PANEL FOTOGRÁFICO --}}
        {{-- ======================================= --}}

        <section class="mc-registro__visual"
                 aria-label="Descubre Mil Cumbres">

            <img
                class="mc-registro__imagen"
                src="{{ config('milcumbres.imagenes.acceso') }}"
                alt="Paisaje natural de Valle de Bravo"
                fetchpriority="high"
            >

            <div class="mc-registro__overlay"></div>

            {{-- PARTE SUPERIOR --}}

            <div class="mc-registro__visual-top">

                <a href="{{ route('inicio') }}"
                   class="mc-registro__regresar">

                    <span aria-hidden="true">←</span>
                    Volver al inicio

                </a>

                <span class="mc-registro__visual-indice">
                    MIL CUMBRES
                </span>

            </div>


            {{-- MENSAJE EDITORIAL --}}

            <div class="mc-registro__visual-centro">

                <span class="mc-registro__eyebrow
                             mc-registro__eyebrow--claro">
                    <span class="mc-registro__linea"></span>
                    BIENVENIDO A UNA NUEVA EXPERIENCIA
                </span>


                <h2>
                    Tu próxima
                    <em>historia comienza</em>
                    aquí.
                </h2>

                <p>
                    Hay lugares que se convierten
                    en recuerdos. Descubre experiencias
                    de naturaleza, bienestar y cultura
                    en Valle de Bravo.
                </p>

                <div class="mc-registro__visual-frase">


                    <blockquote>
                        Descubre. Conecta.
                        Vive el lugar.
                    </blockquote>

                </div>

            </div>


            {{-- PARTE INFERIOR --}}

            <div class="mc-registro__visual-footer">

                <span>
                    VALLE DE BRAVO · MÉXICO
                </span>

                <span>
                    NATURALEZA / BIENESTAR / CULTURA
                </span>

            </div>

        </section>


        {{-- ======================================= --}}
        {{-- 02 · FORMULARIO --}}
        {{-- ======================================= --}}

        <section class="mc-registro__contenido">

            <div class="mc-registro__contenedor">

                {{-- IDENTIDAD --}}

                <div class="mc-registro__marca">


                    <div>
                        <strong>MIL CUMBRES</strong>

                        <small>
                            TURISMO REGENERATIVO
                        </small>
                    </div>

                </div>


                {{-- INTRODUCCIÓN --}}

                <div class="mc-registro__encabezado">

                    <span class="mc-registro__eyebrow">
                        <span class="mc-registro__linea"></span>
                        ÚNETE A MIL CUMBRES
                    </span>

                    <h1>
                        Comienza algo
                        <em>extraordinario.</em>
                    </h1>

                    <p>
                        Crea tu cuenta para solicitar
                        experiencias, registrar tus pagos
                        y consultar el seguimiento
                        de tus reservaciones.
                    </p>

                </div>


                {{-- INDICADOR VISUAL --}}

                <div class="mc-registro__etapas"
                     aria-label="Apartados del registro">

                    <span>
                        Tus datos
                    </span>

                    <span>
                        Contacto
                    </span>

                    <span>
                        Seguridad
                    </span>

                </div>


                {{-- MENSAJES EXISTENTES --}}

                <x-avisos />


                {{-- ================================= --}}
                {{-- FORMULARIO DE REGISTRO --}}
                {{-- ================================= --}}

                <form
                    method="POST"
                    action="{{ route('registro.store') }}"
                    class="mc-registro__form"
                    novalidate
                >

                    @csrf


                    {{-- ================================= --}}
                    {{-- APARTADO 01 · DATOS PERSONALES --}}
                    {{-- ================================= --}}

                    <fieldset class="mc-registro__grupo">

                        <legend>
                            Información personal
                        </legend>

                        <p class="mc-registro__grupo-descripcion">
                            Cuéntanos cómo podemos identificarte
                            en tus solicitudes.
                        </p>

                        <div class="mc-registro__grid">

                            {{-- NOMBRE --}}

                            <div class="mc-registro__campo
                                        mc-registro__campo--completo">

                                <label for="nombre">
                                    Nombre(s)
                                    <span class="mc-registro__requerido">*</span>
                                </label>

                                <input
                                    @class([
                                        'mc-registro__input',
                                        'es-invalido' => $errors->has('nombre')
                                    ])
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre') }}"
                                    maxlength="60"
                                    autocomplete="given-name"
                                    placeholder="Escribe tu nombre"
                                    required
                                >

                                @error('nombre')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- APELLIDO PATERNO --}}

                            <div class="mc-registro__campo">

                                <label for="ap">
                                    Apellido paterno
                                    <span class="mc-registro__requerido">*</span>
                                </label>

                                <input
                                    @class([
                                        'mc-registro__input',
                                        'es-invalido' => $errors->has('ap')
                                    ])
                                    type="text"
                                    id="ap"
                                    name="ap"
                                    value="{{ old('ap') }}"
                                    maxlength="60"
                                    autocomplete="family-name"
                                    placeholder="Apellido paterno"
                                    required
                                >

                                @error('ap')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- APELLIDO MATERNO --}}

                            <div class="mc-registro__campo">

                                <label for="am">
                                    Apellido materno

                                    <small>(opcional)</small>
                                </label>

                                <input
                                    @class([
                                        'mc-registro__input',
                                        'es-invalido' => $errors->has('am')
                                    ])
                                    type="text"
                                    id="am"
                                    name="am"
                                    value="{{ old('am') }}"
                                    maxlength="60"
                                    placeholder="Apellido materno"
                                >

                                @error('am')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </fieldset>


                    {{-- ================================= --}}
                    {{-- APARTADO 02 · CONTACTO --}}
                    {{-- ================================= --}}

                    <fieldset class="mc-registro__grupo">

                        <legend>
                            Información de contacto
                        </legend>

                        <p class="mc-registro__grupo-descripcion">
                            Utilizaremos estos datos
                            para comunicarnos contigo
                            sobre tus solicitudes.
                        </p>

                        <div class="mc-registro__grid">

                            {{-- EMAIL --}}

                            <div class="mc-registro__campo">

                                <label for="email">
                                    Correo electrónico
                                    <span class="mc-registro__requerido">*</span>
                                </label>

                                <input
                                    @class([
                                        'mc-registro__input',
                                        'es-invalido' => $errors->has('email')
                                    ])
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    maxlength="120"
                                    autocomplete="email"
                                    placeholder="ejemplo@correo.com"
                                    required
                                >

                                @error('email')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- TELÉFONO --}}

                            <div class="mc-registro__campo">

                                <label for="telefono">
                                    Teléfono

                                    <small>(10 dígitos)</small>
                                </label>

                                <input
                                    @class([
                                        'mc-registro__input',
                                        'es-invalido' => $errors->has('telefono')
                                    ])
                                    type="tel"
                                    id="telefono"
                                    name="telefono"
                                    value="{{ old('telefono') }}"
                                    inputmode="numeric"
                                    pattern="[0-9]{10}"
                                    maxlength="10"
                                    autocomplete="tel-national"
                                    placeholder="5512345678"
                                >

                                @error('telefono')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </fieldset>


                    {{-- ================================= --}}
                    {{-- APARTADO 03 · SEGURIDAD --}}
                    {{-- ================================= --}}

                    <fieldset class="mc-registro__grupo">

                        <legend>
                            Protege tu cuenta
                        </legend>

                        <p class="mc-registro__grupo-descripcion">
                            Crea una contraseña para
                            acceder a tu espacio personal.
                        </p>

                        <div class="mc-registro__grid">

                            {{-- CONTRASEÑA --}}

                            <div class="mc-registro__campo">

                                <label for="contrasena">
                                    Contraseña
                                    <span class="mc-registro__requerido">*</span>
                                </label>

                                <div class="mc-registro__password">

                                    <input
                                        @class([
                                            'mc-registro__input',
                                            'es-invalido' =>
                                                $errors->has('contrasena')
                                        ])
                                        type="password"
                                        id="contrasena"
                                        name="contrasena"
                                        minlength="8"
                                        autocomplete="new-password"
                                        placeholder="Mínimo 8 caracteres"
                                        aria-describedby="requisitos-contrasena"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="mc-registro__ver"
                                        data-registro-ver="contrasena"
                                        aria-controls="contrasena"
                                        aria-pressed="false"
                                    >
                                        Mostrar
                                    </button>

                                </div>

                                {{-- Requisitos de contraseña segura (se marcan en vivo con validar-contrasena.js) --}}
                                <x-requisitos-contrasena para="contrasena" />

                                @error('contrasena')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>


                            {{-- CONFIRMACIÓN --}}

                            <div class="mc-registro__campo">

                                <label for="contrasena_confirmation">
                                    Confirmar contraseña
                                    <span class="mc-registro__requerido">*</span>
                                </label>

                                <div class="mc-registro__password">

                                    <input
                                        @class([
                                            'mc-registro__input',
                                            'es-invalido' =>
                                                $errors->has('contrasena_confirmation')
                                        ])
                                        type="password"
                                        id="contrasena_confirmation"
                                        name="contrasena_confirmation"
                                        autocomplete="new-password"
                                        placeholder="Repite tu contraseña"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="mc-registro__ver"
                                        data-registro-ver="contrasena_confirmation"
                                        aria-controls="contrasena_confirmation"
                                        aria-pressed="false"
                                    >
                                        Mostrar
                                    </button>

                                </div>

                                <span class="mc-registro__error" id="coincide-contrasena" hidden>
                                    Las contraseñas no coinciden.
                                </span>

                                @error('contrasena_confirmation')
                                    <span class="mc-registro__error"
                                          role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </fieldset>


                    {{-- ================================= --}}
                    {{-- AVISO DE PRIVACIDAD --}}
                    {{-- ================================= --}}

                    <div class="mc-registro__privacidad">

                        <label class="mc-registro__checkbox">

                            <input
                                type="checkbox"
                                name="aviso"
                                value="1"
                                @checked(old('aviso'))
                            >

                            <span>
                                He leído y acepto el

                                <a
                                    href="{{ route('privacidad') }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    aviso de privacidad
                                </a>.
                            </span>

                        </label>

                        @error('aviso')
                            <span class="mc-registro__error"
                                  role="alert">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- ================================= --}}
                    {{-- CREAR CUENTA --}}
                    {{-- ================================= --}}

                    <div class="mc-registro__acciones">

                        <button
                            class="mc-registro__submit"
                            type="submit"
                        >

                            <span>
                                Crear mi cuenta
                            </span>

                            <span aria-hidden="true">↗</span>

                        </button>

                        <p>
                            Los campos marcados con
                            <strong>*</strong>
                            son obligatorios.
                        </p>

                    </div>

                </form>


                {{-- ================================= --}}
                {{-- ACCESO PARA USUARIOS EXISTENTES --}}
                {{-- ================================= --}}

                <div class="mc-registro__ingresar">


                    <div>

                        <small>
                            ¿YA FORMAS PARTE DE MIL CUMBRES?
                        </small>

                        <h2>
                            Nos alegra verte de nuevo.
                        </h2>

                        <a href="{{ route('login') }}">
                            Iniciar sesión
                            <span aria-hidden="true">↗</span>
                        </a>

                    </div>

                </div>


                {{-- PIE INTERNO --}}

                <div class="mc-registro__pie">

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
{{-- MOSTRAR / OCULTAR CONTRASEÑAS --}}
{{-- ======================================= --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll(
        '[data-registro-ver]'
    ).forEach(function (boton) {

        const input = document.getElementById(
            boton.dataset.registroVer
        );

        if (!input) return;

        boton.addEventListener('click', function () {

            const mostrar = input.type === 'password';

            input.type = mostrar ? 'text' : 'password';

            boton.textContent = mostrar
                ? 'Ocultar'
                : 'Mostrar';

            boton.setAttribute(
                'aria-pressed',
                String(mostrar)
            );

        });

    });

});
</script>
<script src="{{ asset('js/validar-contrasena.js') }}" defer></script>
@endpush
