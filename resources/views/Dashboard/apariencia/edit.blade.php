{{-- A12 · Apariencia: paleta de colores del sitio
     Tabla: configuracion_sitio (columna tema) --}}
@extends('layouts.admin')

@section('titulo', 'Apariencia')

@push('estilos')
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('admin-apariencia-milcumbres.css') }}">
@endpush

@section('contenido')
<div class="mc-apa">

    <header class="mc-apa__cabecera">
        <span class="mc-apa__eyebrow">CONFIGURACIÓN · APARIENCIA</span>
        <h1>Colores del <em>sitio.</em></h1>
        <p>La paleta que elijas cambia el panel, la vista del cliente y la página pública al mismo tiempo.</p>
    </header>

    <form method="POST" action="{{ route('admin.apariencia.update') }}" class="mc-apa__form">
        @csrf
        @method('PUT')

        <fieldset class="mc-apa__opciones">
            <legend class="mc-apa__legend">Elige una paleta</legend>

            <div class="mc-apa__lista">
                @foreach ($temas as $clave => $tema)
                    @php
                        $c = $tema['colores'];
                        $estilo = collect([
                            'oscuro' => $c['oscuro'],
                            'principal' => $c['principal'],
                            'secundario' => $c['secundario'],
                            'acento' => $c['acento'],
                            'fondo' => $c['fondo'],
                            'fondo-alt' => $c['fondo-alt'],
                        ])->map(fn ($valor, $nombre) => "--p-{$nombre}: {$valor}")->implode('; ');
                    @endphp

                    <label class="mc-apa__opcion">
                        <input type="radio" name="tema" value="{{ $clave }}" @checked($activo === $clave)>

                        <span class="mc-apa__tarjeta">
                            {{-- Mini vista del sitio con los colores de esta paleta --}}
                            <span class="mc-apa__mini" style="{{ $estilo }}" aria-hidden="true">
                                <span class="mc-apa__mini-cabecera">
                                    <span class="mc-apa__mini-logo"></span>
                                    <span class="mc-apa__mini-acento"></span>
                                </span>
                                <span class="mc-apa__mini-cuerpo">
                                    <span class="mc-apa__mini-titulo">Experiencias</span>
                                    <span class="mc-apa__mini-linea"></span>
                                    <span class="mc-apa__mini-linea mc-apa__mini-linea--corta"></span>
                                    <span class="mc-apa__mini-boton">Reservar</span>
                                </span>
                            </span>

                            <span class="mc-apa__texto">
                                <span class="mc-apa__nombre">
                                    {{ $tema['nombre'] }}
                                    @if (! empty($tema['recomendada']))
                                        <small class="mc-apa__etiqueta">Recomendada</small>
                                    @endif
                                    @if ($activo === $clave)
                                        <small class="mc-apa__etiqueta mc-apa__etiqueta--activa">En uso</small>
                                    @endif
                                </span>
                                <span class="mc-apa__descripcion">{{ $tema['descripcion'] }}</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        @error('tema')
            <p class="mc-apa__error" role="alert">{{ $message }}</p>
        @enderror

        <div class="mc-apa__acciones">
            <p>Los cambios se aplican al guardar. Si algo no se ve como esperas, vuelve a elegir “Actual” para regresar al tema original.</p>
            <button type="submit" class="mc-apa__boton">Guardar paleta</button>
        </div>
    </form>
</div>
@endsection
