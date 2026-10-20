{{-- A10 · Identidad del sitio (nombre, lema y logotipo)
     Tabla: configuracion_sitio --}}
@extends('layouts.admin')

@section('titulo', 'Identidad del sitio')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.resumen') }}">← Panel general</a></p>
        <h1>Identidad del sitio</h1>
        <p>El nombre y el logotipo se muestran en el panel y en la vista del cliente.</p>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" novalidate action="{{ route('admin.marca.update') }}">
    @csrf
    @method('PUT')

    <div class="detalle-admin">
        <div>
            <section class="panel form-grid">
                <h2 class="completo mb-0">Nombre</h2>

                <div class="campo completo">
                    <label for="nombre">Nombre del sitio</label>
                    <input @class(['control', 'es-invalido' => $errors->has('nombre')]) id="nombre" name="nombre" maxlength="60"
                           value="{{ old('nombre', $config->nombre) }}" required>
                    @error('nombre') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo completo">
                    <label for="lema">Lema (debajo del nombre)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('lema')]) id="lema" name="lema" maxlength="80"
                           value="{{ old('lema', $config->lema) }}" placeholder="Turismo Regenerativo">
                    @error('lema') <span class="error">{{ $message }}</span> @enderror
                </div>
            </section>
        </div>

        <div>
            <section class="panel pila">
                <h2 class="mb-0">Logotipo</h2>
                <img class="vista-imagen vista-imagen--logo" id="vista-logo" src="{{ $config->logo_url }}" alt="Logotipo actual">

                <div class="campo">
                    <label for="logo_archivo">Subir nuevo logotipo</label>
                    <input @class(['control', 'es-invalido' => $errors->has('logo_archivo')]) type="file" id="logo_archivo" name="logo_archivo"
                           accept="image/png,image/jpeg,image/webp">
                    <small>PNG, JPG o WEBP, máximo 2 MB.</small>
                    @error('logo_archivo') <span class="error">{{ $message }}</span> @enderror
                </div>

                @if ($config->logo)
                    <label class="casilla">
                        <input type="checkbox" name="quitar_logo" value="1">
                        Volver al logotipo original
                    </label>
                @endif
            </section>

            <section class="panel">
                <button class="btn btn--primario btn--bloque" type="submit">Guardar cambios</button>
                <a class="btn btn--texto btn--bloque" href="{{ route('admin.resumen') }}">Cancelar</a>
            </section>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Vista previa del logotipo antes de guardar.
    document.getElementById('logo_archivo')?.addEventListener('change', (e) => {
        const archivo = e.target.files[0];
        if (archivo) document.getElementById('vista-logo').src = URL.createObjectURL(archivo);
    });
</script>
@endpush
