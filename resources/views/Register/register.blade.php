{{-- C05 · Registro
     Tablas: personas, roles --}}
@extends('layouts.app')

@section('titulo', 'Crear cuenta')

@section('contenido')
<div class="acceso">
    <div class="acceso__imagen" style="background-image: url('{{ config('milcumbres.imagenes.acceso') }}')">
        <blockquote>Una cuenta para solicitar, pagar y seguir tus experiencias.</blockquote>
    </div>

    <div class="acceso__form acceso__form--ancho">
        <div>
            <h1 class="titulo-seccion">Crea tu cuenta</h1>
            <p class="texto-suave">Solo te pediremos lo necesario para confirmar tus reservaciones.</p>

            <form method="POST" action="{{ route('registro.store') }}" class="form-grid mt-3" novalidate>
                @csrf
                <div class="campo completo">
                    <label for="nombre">Nombre(s)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('nombre')]) id="nombre" name="nombre"
                           value="{{ old('nombre') }}" maxlength="60" autocomplete="given-name" required>
                    @error('nombre') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="ap">Apellido paterno</label>
                    <input @class(['control', 'es-invalido' => $errors->has('ap')]) id="ap" name="ap"
                           value="{{ old('ap') }}" maxlength="60" autocomplete="family-name" required>
                    @error('ap') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="am">Apellido materno <span class="ayuda">(opcional)</span></label>
                    <input class="control" id="am" name="am" value="{{ old('am') }}" maxlength="60">
                </div>

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input @class(['control', 'es-invalido' => $errors->has('email')]) type="email" id="email" name="email"
                           value="{{ old('email') }}" maxlength="120" autocomplete="email" required>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="telefono">Teléfono <span class="ayuda">(10 dígitos)</span></label>
                    <input @class(['control', 'es-invalido' => $errors->has('telefono')]) type="tel" id="telefono" name="telefono"
                           value="{{ old('telefono') }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" autocomplete="tel-national">
                    @error('telefono') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="contrasena">Contraseña</label>
                    <input @class(['control', 'es-invalido' => $errors->has('contrasena')]) type="password" id="contrasena"
                           name="contrasena" minlength="8" autocomplete="new-password" required>
                    <span class="ayuda">Mínimo 8 caracteres.</span>
                    @error('contrasena') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="contrasena_confirmation">Confirmar contraseña</label>
                    <input class="control" type="password" id="contrasena_confirmation" name="contrasena_confirmation"
                           autocomplete="new-password" required>
                </div>

                <div class="completo">
                    <label class="check">
                        <input type="checkbox" name="aviso" value="1" @checked(old('aviso'))>
                        <span>He leído y acepto el <a href="{{ route('privacidad') }}" target="_blank">aviso de privacidad</a>.</span>
                    </label>
                    @error('aviso') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="completo">
                    <button class="btn btn--primario btn--bloque" type="submit">Crear cuenta</button>
                    <p class="texto-suave centrado mt-2">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
