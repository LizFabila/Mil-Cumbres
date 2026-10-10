{{-- A06 · Experiencias
     Tablas: experiencias, zonas, catalogo_experiencias, asigna_disponibilidad --}}
@extends('layouts.admin')

@section('titulo', 'Experiencias')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Experiencias</h1>
        <p>Catálogo publicado en el sitio.</p>
    </div>
    <a class="btn btn--primario" href="{{ route('admin.experiencias.create') }}">Nueva experiencia</a>
</div>

<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr><th></th><th>Nombre</th><th>Tipo</th><th>Zona</th><th>Duración</th><th class="num">Precio</th><th class="num">Horarios</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($experiencias as $exp)
            <tr>
                <td><img class="miniatura" src="{{ $exp->imagen_url }}" alt=""></td>
                <td>
                    <strong>{{ $exp->nombre }}</strong>
                    <div class="texto-chico"><a href="{{ route('experiencias.show', [$exp, $exp->slug]) }}" target="_blank">Ver en sitio ↗</a></div>
                </td>
                <td>{{ $exp->catalogo?->tipo }}</td>
                <td>{{ $exp->zona?->nombre }}</td>
                <td>{{ $exp->duracion_texto }}</td>
                <td class="num">${{ number_format($exp->precio, 2) }}</td>
                <td class="num">
                    @if ($exp->disponibilidades_count) {{ $exp->disponibilidades_count }}
                    @else <span class="error">0</span> @endif
                </td>
                <td>
                    <div class="acciones-tabla">
                        <a class="btn btn--secundario btn--chico" href="{{ route('admin.experiencias.edit', $exp) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.experiencias.destroy', $exp) }}"
                              onsubmit="return confirm('¿Dar de baja “{{ $exp->nombre }}”? Dejará de mostrarse en el sitio.')">
                            @csrf @method('DELETE')
                            <button class="btn btn--peligro btn--chico" type="submit">Baja</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="vacio">Aún no hay experiencias. <a href="{{ route('admin.experiencias.create') }}">Crea la primera</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $experiencias->links() }}
@endsection
