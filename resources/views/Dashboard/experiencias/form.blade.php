{{-- A07 · Editor de experiencia (alta y edición)
     Tablas: experiencias, zonas, catalogo_experiencias, disponibilidad, asigna_disponibilidad --}}
@extends('layouts.admin')

@php $editando = $experiencia->exists; @endphp

@section('titulo', $editando ? 'Editar experiencia' : 'Nueva experiencia')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.experiencias.index') }}">← Experiencias</a></p>
        <h1>{{ $editando ? $experiencia->nombre : 'Nueva experiencia' }}</h1>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" novalidate
      action="{{ $editando ? route('admin.experiencias.update', $experiencia) : route('admin.experiencias.store') }}">
    @csrf
    @if ($editando) @method('PUT') @endif

    <div class="detalle-admin">
        <div>
            <section class="panel form-grid">
                <h2 class="completo mb-0">Datos generales</h2>

                <div class="campo completo">
                    <label for="nombre">Nombre</label>
                    <input @class(['control', 'es-invalido' => $errors->has('nombre')]) id="nombre" name="nombre" maxlength="100"
                           value="{{ old('nombre', $experiencia->nombre) }}" required>
                    @error('nombre') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="id_catalogo_exp">Tipo</label>
                    <select @class(['control', 'es-invalido' => $errors->has('id_catalogo_exp')]) id="id_catalogo_exp" name="id_catalogo_exp" required>
                        <option value="">Elige…</option>
                        @foreach ($tipos as $t)
                            <option value="{{ $t->id_catalogo_exp }}" @selected(old('id_catalogo_exp', $experiencia->id_catalogo_exp) == $t->id_catalogo_exp)>{{ $t->tipo }}</option>
                        @endforeach
                    </select>
                    @error('id_catalogo_exp') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="id_zona">Zona</label>
                    <select @class(['control', 'es-invalido' => $errors->has('id_zona')]) id="id_zona" name="id_zona" required>
                        <option value="">Elige…</option>
                        @foreach ($zonas as $z)
                            <option value="{{ $z->id_zona }}" @selected(old('id_zona', $experiencia->id_zona) == $z->id_zona)>{{ $z->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_zona') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo completo">
                    <label for="resumen">Resumen</label>
                    <textarea class="control" id="resumen" name="resumen" maxlength="2000">{{ old('resumen', $experiencia->resumen) }}</textarea>
                </div>

                <div class="campo">
                    <label for="duracion">Duración (minutos)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('duracion')]) type="number" id="duracion" name="duracion"
                           min="1" value="{{ old('duracion', $experiencia->duracion) }}" required>
                    @error('duracion') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="precio">Precio por persona (MXN)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('precio')]) type="number" id="precio" name="precio"
                           min="0" step="0.01" value="{{ old('precio', $experiencia->precio) }}" required>
                    @error('precio') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="temporada">Temporada</label>
                    <input class="control" id="temporada" name="temporada" maxlength="50"
                           value="{{ old('temporada', $experiencia->temporada) }}" placeholder="Octubre a marzo">
                </div>

                <div class="campo completo">
                    <label for="requisitos">Requisitos</label>
                    <textarea class="control" id="requisitos" name="requisitos" maxlength="2000">{{ old('requisitos', $experiencia->requisitos) }}</textarea>
                </div>
            </section>

            <section class="panel">
                <div class="fila fila--entre">
                    <h2 class="mb-0">Horarios y cupo</h2>
                    <button class="btn btn--secundario btn--chico" type="button" id="agregar-horario">+ Agregar horario</button>
                </div>
                <p class="ayuda">Un horario por día. El cupo es el máximo de personas por fecha.</p>
                @error('horarios.*') <span class="error">{{ $message }}</span> @enderror

                <div id="horarios">
                    @foreach ($horarios as $i => $h)
                        @include('Dashboard.experiencias._horario', ['i' => $i, 'h' => $h])
                    @endforeach
                </div>

                <template id="plantilla-horario">
                    @include('Dashboard.experiencias._horario', ['i' => '__I__', 'h' => []])
                </template>
            </section>
        </div>

        <div>
            <section class="panel pila">
                <h2 class="mb-0">Imagen</h2>
                <img class="vista-imagen" id="vista-imagen" src="{{ $experiencia->imagen_url }}" alt="Vista previa">

                <div class="campo">
                    <label for="imagen_archivo">Subir archivo</label>
                    <input class="control" type="file" id="imagen_archivo" name="imagen_archivo" accept="image/*">
                    @error('imagen_archivo') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="imagen_url">…o pegar URL</label>
                    <input class="control" type="url" id="imagen_url" name="imagen_url"
                           value="{{ old('imagen_url', str_starts_with((string) $experiencia->imagen, 'http') ? $experiencia->imagen : '') }}"
                           placeholder="https://images.unsplash.com/…">
                    @error('imagen_url') <span class="error">{{ $message }}</span> @enderror
                </div>
            </section>

            <section class="panel">
                <button class="btn btn--primario btn--bloque" type="submit">{{ $editando ? 'Guardar cambios' : 'Crear experiencia' }}</button>
                <a class="btn btn--texto btn--bloque" href="{{ route('admin.experiencias.index') }}">Cancelar</a>
            </section>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/editor-experiencia.js') }}" defer></script>
@endpush
