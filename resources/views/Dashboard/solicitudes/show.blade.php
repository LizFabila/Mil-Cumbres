{{-- A02 · Detalle de solicitud
     Tablas: solicitudes, personas, asigna_experiencia, experiencias, asigna_disponibilidad, disponibilidad, pagos --}}
@extends('layouts.admin')

@section('titulo', $solicitud->folio)

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.solicitudes.index') }}">← Solicitudes</a></p>
        <h1 class="mono">{{ $solicitud->folio }}</h1>
        <p>Recibida {{ $solicitud->created_at->diffForHumans() }} · <x-estado :valor="$solicitud->estado" /></p>
    </div>

    <form method="POST" action="{{ route('admin.solicitudes.estado', $solicitud) }}" class="fila">
        @csrf @method('PATCH')
        @if ($solicitud->estado === 'pendiente')
            <button class="btn btn--primario" name="estado" value="confirmada">Confirmar</button>
            <button class="btn btn--peligro" name="estado" value="cancelada"
                    onclick="return confirm('¿Cancelar esta solicitud?')">Cancelar</button>
        @elseif ($solicitud->estado === 'confirmada')
            <button class="btn btn--primario" name="estado" value="completada">Marcar completada</button>
            <button class="btn btn--peligro" name="estado" value="cancelada"
                    onclick="return confirm('¿Cancelar esta solicitud?')">Cancelar</button>
        @endif
    </form>
</div>

<div class="detalle-admin">
    <div>
        <section class="panel">
            <h2>Solicitud</h2>
            <dl class="datos">
                <div><dt>Fecha deseada</dt><dd>{{ $solicitud->fecha_deseada->translatedFormat('l j \d\e F Y') }}</dd></div>
                <div><dt>Personas</dt><dd>{{ $solicitud->cant_persona }}</dd></div>
                <div><dt>Total</dt><dd>${{ number_format($solicitud->total, 2) }} MXN</dd></div>
                <div><dt>Día de la semana</dt><dd>{{ $solicitud->fecha_deseada->translatedFormat('l') }}</dd></div>
            </dl>
            @if ($solicitud->nota)
                <h2 class="mt-3">Nota del cliente</h2>
                <p class="texto-suave mb-0">{{ $solicitud->nota }}</p>
            @endif
        </section>

        <section class="panel">
            <h2>Experiencias y cupo para esa fecha</h2>
            <div class="tabla-envoltura">
                <table class="tabla">
                    <thead><tr><th>Experiencia</th><th class="num">Precio</th><th>Cupo ocupado (sin esta)</th></tr></thead>
                    <tbody>
                    @foreach ($solicitud->experiencias as $exp)
                        @php
                            $c = $cupos[$exp->id_experiencia];
                            $tras = $c['ocupado'] + $solicitud->cant_persona;
                            $pct = $c['cupo'] ? min(100, round($tras / $c['cupo'] * 100)) : 100;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $exp->nombre }}</strong>
                                <div class="texto-chico texto-suave">{{ $exp->zona?->nombre }} · {{ $exp->duracion_texto }}</div>
                            </td>
                            <td class="num">${{ number_format($exp->precio, 2) }}</td>
                            <td>
                                @if ($c['cupo'] === 0)
                                    <span class="error">Sin horario ese día</span>
                                @else
                                    {{ $c['ocupado'] }} + {{ $solicitud->cant_persona }} de {{ $c['cupo'] }}
                                    <div @class(['cupo-barra', 'lleno' => $tras > $c['cupo']])><span style="width: {{ $pct }}%"></span></div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div>
        <section class="panel">
            <h2>Cliente</h2>
            <p class="mb-0"><strong>{{ $solicitud->persona?->nombre_completo }}</strong></p>
            <p class="mb-0"><a href="mailto:{{ $solicitud->persona?->email }}">{{ $solicitud->persona?->email }}</a></p>
            @if ($solicitud->persona?->telefono)
                <p class="mb-0"><a href="tel:{{ $solicitud->persona->telefono }}">{{ $solicitud->persona->telefono }}</a></p>
            @endif
        </section>

        <section class="panel">
            <h2>Pagos</h2>
            @forelse ($solicitud->pagos as $pago)
                <div class="fila fila--entre mt-2">
                    <div>
                        <span class="mono">{{ $pago->referencia }}</span>
                        <div class="texto-chico texto-suave">{{ $pago->fecha->format('d/m/Y H:i') }}</div>
                    </div>
                    <x-estado :valor="$pago->estado" />
                </div>
                @if ($pago->comprobante_url)
                    <a class="texto-chico" href="{{ $pago->comprobante_url }}" target="_blank" rel="noopener">Ver comprobante</a>
                @endif
            @empty
                <p class="texto-suave mb-0">Sin pagos registrados.</p>
            @endforelse
            @if ($solicitud->pagos->where('estado', 'pendiente')->isNotEmpty())
                <a class="btn btn--secundario btn--chico mt-2" href="{{ route('admin.pagos.index') }}">Revisar en Pagos</a>
            @endif
        </section>
    </div>
</div>
@endsection
