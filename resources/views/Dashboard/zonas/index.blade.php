{{-- Zonas (catálogo para las experiencias)
     Tablas: zonas, experiencias --}}
@extends('layouts.admin')

@section('titulo', 'Zonas')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Zonas</h1>
        <p>Lugares donde se realizan las experiencias.</p>
    </div>
    <a class="btn btn--primario" href="{{ route('admin.zonas.create') }}">Nueva zona</a>
</div>

@error('eliminar') <p class="error">{{ $message }}</p> @enderror

<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr><th>Nombre</th><th class="num">Experiencias</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($zonas as $zona)
            <tr>
                <td><strong>{{ $zona->nombre }}</strong></td>
                <td class="num">{{ $zona->experiencias_count }}</td>
                <td>
                    <div class="acciones-tabla">
                        <a class="btn btn--secundario btn--chico" href="{{ route('admin.zonas.edit', $zona) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.zonas.destroy', $zona) }}"
                              onsubmit="return confirm('¿Quitar la zona “{{ $zona->nombre }}”?')">
                            @csrf @method('DELETE')
                            <button class="btn btn--peligro btn--chico" type="submit">Quitar</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="vacio">Aún no hay zonas. <a href="{{ route('admin.zonas.create') }}">Crea la primera</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
