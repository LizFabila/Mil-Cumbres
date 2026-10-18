
{{-- A01 · Solicitudes
     Tablas: solicitudes, personas, asigna_experiencia, experiencias --}}

@extends('layouts.admin')

@section('titulo', 'Solicitudes')

@section('contenido')

<div class="admin-cabecera">
    <div>
        <h1>Solicitudes</h1>
        <p>Revisa disponibilidad y confirma o cancela cada solicitud.</p>
    </div>
</div>

<nav class="pestanas" aria-label="Filtrar por estado">
    <a href="{{ route('admin.solicitudes.index', request()->except('estado', 'page')) }}"
       @class(['activo' => empty($filtros['estado'])])>
        Todas <small>{{ $conteos->sum() }}</small>
    </a>

    @foreach (\App\Models\Solicitud::ESTADOS as $estado)
        <a href="{{ route('admin.solicitudes.index', [...request()->except('page'), 'estado' => $estado]) }}"
           @class(['activo' => ($filtros['estado'] ?? null) === $estado])>
            {{ ucfirst($estado) }}
            <small>{{ $conteos[$estado] ?? 0 }}</small>
        </a>
    @endforeach
</nav>

<form class="barra-filtros" method="GET">

    @if (! empty($filtros['estado']))
        <input type="hidden"
               name="estado"
               value="{{ $filtros['estado'] }}">
    @endif

    <div class="campo campo--crece">
        <label for="q">Buscar</label>
        <input class="control"
               id="q"
               name="q"
               value="{{ $filtros['q'] ?? '' }}"
               placeholder="Folio, nombre o correo">
    </div>

    <div class="campo">
        <label for="desde">Fecha desde</label>
        <input class="control"
               type="date"
               id="desde"
               name="desde"
               value="{{ $filtros['desde'] ?? '' }}">
    </div>

    <div class="campo">
        <label for="hasta">Hasta</label>
        <input class="control"
               type="date"
               id="hasta"
               name="hasta"
               value="{{ $filtros['hasta'] ?? '' }}">
    </div>

    <button class="btn btn--primario" type="submit">
        Filtrar
    </button>

</form>

{{-- TABLA DE SOLICITUDES --}}

<div class="tabla-envoltura">

    <table class="tabla">

        <thead>
            <tr>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Experiencias</th>
                <th>Fecha deseada</th>
                <th class="num">Pers.</th>
                <th class="num">Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($solicitudes as $s)

                <tr>

                    {{-- FOLIO --}}
                    <td class="mono">
                        {{ $s->folio }}
                    </td>

                    {{-- CLIENTE --}}
                    <td>
                        {{ $s->persona?->nombre_completo }}

                        <div class="texto-chico texto-suave">
                            {{ $s->persona?->email }}
                        </div>
                    </td>

                    {{-- EXPERIENCIAS --}}
                    <td>
                        {{ $s->experiencias->pluck('nombre')->implode(', ') }}
                    </td>

                    {{-- FECHA --}}
                    <td>
                        {{ $s->fecha_deseada->format('d/m/Y') }}
                    </td>

                    {{-- PERSONAS --}}
                    <td class="num">
                        {{ $s->cant_persona }}
                    </td>

                    {{-- TOTAL --}}
                    <td class="num">
                        ${{ number_format($s->total, 2) }}
                    </td>

                    {{-- ESTADO --}}
                    <td>
                        <x-estado :valor="$s->estado" />
                    </td>

                    {{-- ACCIONES --}}
                    <td>

                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            flex-wrap: wrap;
                        ">

                            {{-- BOTÓN ABRIR --}}
                            <a class="btn btn--secundario btn--chico"
                               href="{{ route('admin.solicitudes.show', $s) }}">
                                Abrir
                            </a>

                            {{-- BOTÓN ELIMINAR --}}
                            {{-- Solo para solicitudes canceladas --}}

                            @if ($s->estado === 'cancelada')

                                <form
                                    action="{{ route('admin.solicitudes.destroy', $s) }}"
                                    method="POST"
                                    style="margin: 0;"
                                    onsubmit="return confirm('¿Estás seguro de que deseas retirar la solicitud {{ $s->folio }}? Esta acción la ocultará del panel administrativo.');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn--chico"
                                        style="
                                            background-color: #b42318;
                                            color: #ffffff;
                                            border: 1px solid #b42318;
                                            cursor: pointer;
                                        "
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="vacio">
                        No hay solicitudes con esos filtros.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- PAGINACIÓN --}}
{{ $solicitudes->links() }}

@endsection
