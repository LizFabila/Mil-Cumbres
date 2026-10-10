{{-- C11 · Solicitar consultoría
     Tablas: solicitud_consultorias, organizaciones, tipo_organizaciones, personas --}}
@extends('layouts.app')

@section('titulo', 'Solicitar consultoría')

@section('contenido')
<div class="contenedor contenedor--angosto seccion">
    <nav class="migas mt-0" aria-label="Migas de pan">
        <a href="{{ route('consultoria.index') }}">Consultoría</a> / Solicitar
    </nav>

    <h1 class="titulo-seccion">Solicitar consultoría</h1>
    <p class="texto-suave">Cuéntanos sobre tu organización. Te contactaremos a <strong>{{ auth()->user()->email }}</strong>.</p>

    <x-avisos />

    <form method="POST" action="{{ route('consultoria.store') }}" class="tarjeta form-grid mt-3" novalidate>
        @csrf

        <fieldset class="completo">
            <legend class="etiqueta">Tipo de organización</legend>
            <div class="fila mt-2">
                @foreach ($tipos as $tipo)
                    <label class="check">
                        <input type="radio" name="id_tipo_organizacion" value="{{ $tipo->id_tipo_organizacion }}"
                               @checked(old('id_tipo_organizacion', $tipoElegido) == $tipo->id_tipo_organizacion) required>
                        <span>{{ $tipo->nombre }}</span>
                    </label>
                @endforeach
            </div>
            @error('id_tipo_organizacion') <span class="error">{{ $message }}</span> @enderror
        </fieldset>

        <div class="campo">
            <label for="organizacion">Nombre de la organización</label>
            <input @class(['control', 'es-invalido' => $errors->has('organizacion')]) id="organizacion" name="organizacion"
                   value="{{ old('organizacion') }}" maxlength="120" required>
            @error('organizacion') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
            <label for="ubicacion">Ubicación</label>
            <input @class(['control', 'es-invalido' => $errors->has('ubicacion')]) id="ubicacion" name="ubicacion"
                   value="{{ old('ubicacion') }}" maxlength="200" placeholder="Municipio, estado" required>
            <span class="ayuda">Lugar donde se brindará la consultoría.</span>
            @error('ubicacion') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="campo completo">
            <label for="necesidad">¿Qué necesitas?</label>
            <textarea @class(['control', 'es-invalido' => $errors->has('necesidad')]) id="necesidad" name="necesidad"
                      maxlength="3000" required placeholder="Describe tu proyecto, qué te gustaría lograr y en qué plazo.">{{ old('necesidad') }}</textarea>
            @error('necesidad') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="completo">
            <button class="btn btn--primario btn--bloque" type="submit">Enviar solicitud de consultoría</button>
        </div>
    </form>
</div>
@endsection
