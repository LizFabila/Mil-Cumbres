{{-- Zonas: alta y edición
     Tablas: zonas --}}
@extends('layouts.admin')

@php $editando = $zona->exists; @endphp

@section('titulo', $editando ? 'Editar zona' : 'Nueva zona')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.zonas.index') }}">← Zonas</a></p>
        <h1>{{ $editando ? $zona->nombre : 'Nueva zona' }}</h1>
    </div>
</div>

<form method="POST" novalidate
      action="{{ $editando ? route('admin.zonas.update', $zona) : route('admin.zonas.store') }}">
    @csrf
    @if ($editando) @method('PUT') @endif

    <section class="panel form-grid">
        <div class="campo completo">
            <label for="nombre">Nombre de la zona</label>
            <input @class(['control', 'es-invalido' => $errors->has('nombre')]) id="nombre" name="nombre" maxlength="100"
                   value="{{ old('nombre', $zona->nombre) }}" required>
            @error('nombre') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="completo fila">
            <button class="btn btn--primario" type="submit">{{ $editando ? 'Guardar cambios' : 'Crear zona' }}</button>
            <a class="btn btn--texto" href="{{ route('admin.zonas.index') }}">Cancelar</a>
        </div>
    </section>
</form>
@endsection
