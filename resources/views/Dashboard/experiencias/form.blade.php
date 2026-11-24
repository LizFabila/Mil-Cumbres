{{-- A07 · Editor de experiencia (alta y edición)
     Tablas: experiencias, zonas, catalogo_experiencias, disponibilidad, asigna_disponibilidad --}}
@extends('layouts.admin')

@php
    $editando = $experiencia->exists;
    $galeria = $editando ? $experiencia->imagenes : collect();
@endphp

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

                @php
                    // Zonas marcadas: las enviadas, las que ya tiene o, en experiencias anteriores, su zona principal.
                    $zonasMarcadas = array_map('intval', old('zonas', $experiencia->exists
                        ? ($experiencia->zonas->isNotEmpty() ? $experiencia->zonas->pluck('id_zona')->all() : array_filter([$experiencia->id_zona]))
                        : []));
                @endphp
                <fieldset class="campo completo zonas-campo">
                    <legend>Zonas <small class="texto-suave">· marca todas en las que se realiza</small></legend>
                    <input type="hidden" name="zona_principal" value="{{ old('zona_principal', $experiencia->id_zona) }}">
                    <div @class(['zonas-opciones', 'es-invalido' => $errors->has('zonas') || $errors->has('zonas.*')])>
                        @forelse ($zonas as $z)
                            <label class="zona-opcion">
                                <input type="checkbox" name="zonas[]" value="{{ $z->id_zona }}" @checked(in_array($z->id_zona, $zonasMarcadas, true))>
                                <span>{{ $z->nombre }}</span>
                            </label>
                        @empty
                            <p class="ayuda">Aún no hay zonas. <a href="{{ route('admin.zonas.create') }}">Agrega una zona</a>.</p>
                        @endforelse
                    </div>
                    @error('zonas') <span class="error">{{ $message }}</span> @enderror
                    @error('zonas.*') <span class="error">{{ $message }}</span> @enderror
                </fieldset>

                <div class="campo completo">
                    <label for="resumen">Resumen</label>
                    <textarea class="control" id="resumen" name="resumen" maxlength="2000">{{ old('resumen', $experiencia->resumen) }}</textarea>
                </div>

                <div class="campo">
                    <label for="duracion">Duración (horas)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('duracion')]) type="number" id="duracion" name="duracion"
                           min="0.25" step="0.25" value="{{ old('duracion', $experiencia->duracion !== null ? (float) $experiencia->duracion : '') }}" required>
                    <span class="ayuda">Puedes usar decimales: 1.5 = 1 h 30 min.</span>
                    @error('duracion') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="precio">Precio por persona (MXN)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('precio')]) type="number" id="precio" name="precio"
                           min="0" step="0.01" value="{{ old('precio', $experiencia->precio) }}" required>
                    <span class="ayuda">Se cobra cuando el número de personas no coincide con un grupo.</span>
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
                <p class="ayuda">Puedes agregar varios horarios, incluso el mismo día (por ejemplo, 9:00–14:00 y 16:00–18:00). El cupo es el máximo de personas por fecha.</p>
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

            <section class="panel">
                <div class="fila fila--entre">
                    <h2 class="mb-0">Precios por grupo</h2>
                    <button class="btn btn--secundario btn--chico" type="button" id="agregar-grupo">+ Agregar grupo</button>
                </div>
                <p class="ayuda">Cada grupo tiene un número de personas y un <strong>precio total</strong> para ese grupo, no por persona. Si la reservación es de exactamente ese número de personas, se cobra el precio del grupo; en otro caso, el precio por persona.</p>
                @error('grupos.*') <span class="error">{{ $message }}</span> @enderror

                <div id="grupos">
                    @foreach ($grupos as $i => $g)
                        @include('Dashboard.experiencias._grupo', ['i' => $i, 'g' => $g])
                    @endforeach
                </div>

                <template id="plantilla-grupo">
                    @include('Dashboard.experiencias._grupo', ['i' => '__I__', 'g' => []])
                </template>
            </section>

            {{-- Botones al final del formulario, después de los horarios --}}
            <section class="panel acciones-form">
                <p class="ayuda mb-0">Revisa los datos, las zonas y los horarios antes de guardar.</p>
                <div class="acciones-form__botones">
                    <a class="btn btn--texto" href="{{ route('admin.experiencias.index') }}">Cancelar</a>
                    <button class="btn btn--primario" type="submit">{{ $editando ? 'Guardar cambios' : 'Crear experiencia' }}</button>
                </div>
            </section>
        </div>

        {{-- La imagen acompaña al desplazarse para no dejar espacio vacío --}}
        <div class="columna-fija">
            <section class="panel pila">
                <h2 class="mb-0">Imagen</h2>
                <img class="vista-imagen" id="vista-imagen" src="{{ $experiencia->imagen_url }}" alt="Vista previa">

                <div class="campo">
                    <label for="imagen_archivo">Subir archivo</label>
                    <input class="control" type="file" id="imagen_archivo" name="imagen_archivo" accept="image/*" @if ($editando) form="form-portada" @endif>
                    @error('imagen_archivo') <span class="error">{{ $message }}</span> @enderror
                </div>

                {{-- Recorte de la portada: el cuadro muestra lo que verá el cliente --}}
                <div class="galeria-editor" id="portada-editor" data-maximo="1" hidden>
                    <div class="fila fila--entre">
                        <strong>Recorta la portada</strong>
                        <span class="ayuda">16:9 · lo que verá el cliente</span>
                    </div>
                    <p class="ayuda mb-0">Arrastra la foto y usa los controles o la rueda del mouse. Lo que está dentro del cuadro es lo que verá el cliente.</p>
                    <div class="galeria-editor__lista" id="portada-editor-lista"></div>
                    <p class="error" id="portada-editor-aviso" role="alert" hidden></p>
                </div>

                <div class="campo">
                    <label for="imagen_url">…o pegar URL</label>
                    <input class="control" type="url" id="imagen_url" name="imagen_url" @if ($editando) form="form-portada" @endif
                           value="{{ old('imagen_url', str_starts_with((string) $experiencia->imagen, 'http') ? $experiencia->imagen : '') }}"
                           placeholder="https://images.unsplash.com/…">
                    @error('imagen_url') <span class="error">{{ $message }}</span> @enderror
                </div>

                {{-- Cargar: solo la portada (formulario propio) y regresa a esta misma página --}}
                @if ($editando)
                    <div class="acciones-form__botones">
                        <button class="btn btn--primario" type="submit" form="form-portada" id="boton-cargar-portada">Cargar</button>
                    </div>
                @endif
            </section>

            <section class="panel pila">
                <div class="fila fila--entre">
                    <h2 class="mb-0">Galería</h2>
                    <span class="ayuda">{{ $galeria->count() }} de {{ \App\Models\Experiencia::MAX_IMAGENES }}</span>
                </div>
                <p class="ayuda mb-0">Máximo {{ \App\Models\Experiencia::MAX_IMAGENES }} imágenes por experiencia. La portada no cuenta dentro de este límite.</p>

                @if ($galeria->isNotEmpty())
                    <div class="galeria-admin">
                        @foreach ($galeria as $img)
                            <label class="galeria-admin__item">
                                <img src="{{ $img->url }}" alt="">
                                <span><input type="checkbox" name="imagenes_eliminar[]" value="{{ $img->id_experiencia_imagen }}" form="form-galeria"> Quitar</span>
                            </label>
                        @endforeach
                    </div>
                @endif

                @if ($editando)
                <div class="campo">
                    <label for="imagenes">Agregar imágenes</label>
                    <input @class(['control', 'es-invalido' => $errors->has('imagenes') || $errors->has('imagenes.*')])
                           type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple form="form-galeria">
                    @error('imagenes') <span class="error">{{ $message }}</span> @enderror
                    @error('imagenes.*') <span class="error">{{ $message }}</span> @enderror
                </div>

                {{-- Recorte antes de guardar: el cuadro muestra lo que verá el cliente --}}
                <div class="galeria-editor" id="galeria-editor" data-maximo="{{ \App\Models\Experiencia::MAX_IMAGENES }}" hidden>
                    <div class="fila fila--entre">
                        <strong>Recorta cada imagen</strong>
                        <span class="ayuda" id="galeria-editor-conteo"></span>
                    </div>
                    <p class="ayuda mb-0">Arrastra la foto y usa el control o la rueda del mouse para acercar. Lo que está dentro del cuadro es lo que verá el cliente.</p>
                    <div class="galeria-editor__lista" id="galeria-editor-lista"></div>
                    <p class="error" id="galeria-editor-aviso" role="alert" hidden></p>
                </div>

                {{-- Cargar: solo sube la galería (formulario separado) y regresa a esta misma página --}}
                <div class="acciones-form__botones">
                    <button class="btn btn--primario" type="submit" form="form-galeria" id="boton-cargar-galeria">Cargar</button>
                </div>
                @else
                    <p class="ayuda mb-0">Guarda la experiencia para poder subir su galería.</p>
                @endif
            </section>
        </div>
    </div>
</form>

@if ($editando)
    {{-- Formulario de la galería: solo sube imágenes y regresa a esta misma página --}}
    <form id="form-galeria" method="POST" enctype="multipart/form-data" novalidate
          action="{{ route('admin.experiencias.galeria', $experiencia) }}">
        @csrf
    </form>

    {{-- Formulario de la portada: solo sube la foto (o la URL) y regresa a esta misma página --}}
    <form id="form-portada" method="POST" enctype="multipart/form-data" novalidate
          action="{{ route('admin.experiencias.portada', $experiencia) }}">
        @csrf
    </form>
@endif
@endsection

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/galeria-editor.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/editor-experiencia.js') }}" defer></script>
    <script src="{{ asset('js/galeria-editor.js') }}" defer></script>
@endpush
