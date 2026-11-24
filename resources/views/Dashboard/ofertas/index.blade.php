{{-- Ofertas (descuentos por tiempo limitado)
     Tablas: ofertas, experiencias --}}
@extends('layouts.admin')

@section('titulo', 'Ofertas')

@push('estilos')
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('ofertas-milcumbres.css') }}">
@endpush

@section('contenido')
@php
    $conteo = $ofertas->countBy('estado');
@endphp
<div class="admin-cabecera">
    <div>
        <h1>Ofertas</h1>
        <p>Descuentos por tiempo limitado. Las ofertas activas aparecen en el botón «Ofertas» del sitio.</p>
    </div>
    <a class="btn btn--primario" href="{{ route('admin.ofertas.create') }}">Nueva oferta</a>
</div>

<div class="of-adm-resumen">
    <div class="of-adm-resumen__item"><strong>{{ $conteo['activa'] ?? 0 }}</strong><span>Activas · se ven en el sitio</span></div>
    <div class="of-adm-resumen__item"><strong>{{ $conteo['programada'] ?? 0 }}</strong><span>Programadas · aún no empiezan</span></div>
    <div class="of-adm-resumen__item"><strong>{{ $conteo['vencida'] ?? 0 }}</strong><span>Vencidas · ya no se muestran</span></div>
</div>

<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr><th>Experiencia</th><th>Descuento</th><th>Precio por persona</th><th>Vigencia</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($ofertas as $o)
            <tr>
                <td>
                    <strong>{{ $o->experiencia?->nombre ?? 'Experiencia eliminada' }}</strong>
                    @if ($o->etiqueta)<br><small class="texto-suave">{{ $o->etiqueta }}</small>@endif
                </td>
                <td>
                    <span class="of-adm-desc">−{{ $o->porcentaje }}%</span><br>
                    <small class="texto-suave">{{ $o->modo === 'precio' ? 'precio fijo' : 'porcentaje' }}</small>
                </td>
                <td>
                    @if ($o->experiencia)
                        <del class="texto-suave">${{ number_format((float) $o->experiencia->precio, 2) }}</del>
                        → <strong>${{ number_format($o->precio_oferta, 2) }}</strong>
                    @endif
                </td>
                <td>{{ $o->inicia->translatedFormat('j M') }} – {{ $o->termina->translatedFormat('j M Y') }}</td>
                <td><span class="of-adm-estado of-adm-estado--{{ $o->estado }}">{{ ucfirst($o->estado) }}</span></td>
                <td>
                    <div class="acciones-tabla">
                        <a class="btn btn--secundario btn--chico" href="{{ route('admin.ofertas.edit', $o) }}">Editar</a>
                        @if ($o->estado !== 'vencida')
                            <form method="POST" action="{{ route('admin.ofertas.destroy', $o) }}"
                                  onsubmit="return confirm('{{ $o->estado === 'programada' ? '¿Quitar esta oferta programada?' : '¿Terminar la oferta hoy? Dejará de verse en el sitio.' }}')">
                                @csrf @method('DELETE')
                                <button class="btn btn--peligro btn--chico" type="submit">{{ $o->estado === 'programada' ? 'Quitar' : 'Terminar' }}</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="vacio">Aún no hay ofertas. <a href="{{ route('admin.ofertas.create') }}">Crea la primera</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
