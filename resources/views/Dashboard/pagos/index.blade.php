{{-- A03 · Pagos
     Tablas: pagos, solicitudes, personas --}}
@extends('layouts.admin')

@section('titulo', 'Pagos')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Pagos</h1>
        <p>Valida los comprobantes contra el estado de cuenta antes de aprobarlos.</p>
    </div>
</div>

<nav class="pestanas" aria-label="Filtrar por estado">
    @foreach (\App\Models\Pago::ESTADOS as $e)
        <a href="{{ route('admin.pagos.index', ['estado' => $e]) }}" @class(['activo' => $estado === $e])>
            {{ ucfirst($e) }} <small>{{ $conteos[$e] ?? 0 }}</small>
        </a>
    @endforeach
</nav>

<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Folio</th>
            <th>Cliente</th>
            <th>Referencia</th>
            <th class="num">Monto esperado</th>
            <th>Comprobante</th>
            <th>Estado</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($pagos as $pago)
            <tr>
                <td>{{ $pago->fecha->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($pago->solicitud)
                        <a class="mono" href="{{ route('admin.solicitudes.show', $pago->solicitud) }}">{{ $pago->solicitud->folio }}</a>
                    @endif
                </td>
                <td>{{ $pago->solicitud?->persona?->nombre_completo }}</td>
                <td class="mono">{{ $pago->referencia }}</td>
                <td class="num">${{ number_format($pago->solicitud?->total ?? 0, 2) }}</td>
                <td>
                    @if ($pago->comprobante_url)
                        <a href="{{ $pago->comprobante_url }}" target="_blank" rel="noopener">Abrir</a>
                    @else
                        <span class="texto-suave">—</span>
                    @endif
                </td>
                <td><x-estado :valor="$pago->estado" /></td>
                <td>
                    @if ($pago->estado === 'pendiente')
                        <div class="acciones-tabla">
                            <form method="POST" action="{{ route('admin.pagos.update', $pago) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn--primario btn--chico" name="estado" value="aprobado">Aprobar</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pagos.update', $pago) }}"
                                  onsubmit="return confirm('¿Rechazar el pago {{ $pago->referencia }}?')">
                                @csrf @method('PATCH')
                                <button class="btn btn--peligro btn--chico" name="estado" value="rechazado">Rechazar</button>
                            </form>
                        </div>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="vacio">No hay pagos {{ $estado === 'pendiente' ? 'por revisar' : $estado.'s' }}.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $pagos->links() }}
@endsection
