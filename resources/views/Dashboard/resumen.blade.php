{{-- A08 · Resumen
     Tablas: solicitudes, pagos, solicitud_consultorias, asigna_experiencia, experiencias --}}
@extends('layouts.admin')

@section('titulo', 'Resumen')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Resumen</h1>
        <p>{{ now()->translatedFormat('l j \d\e F \d\e Y') }}</p>
    </div>
</div>

<div class="kpis">
    <div class="kpi">
        <span>Solicitudes pendientes</span>
        <strong>{{ $kpis['pendientes'] }}</strong>
        <a href="{{ route('admin.solicitudes.index', ['estado' => 'pendiente']) }}">Revisar →</a>
    </div>
    <div class="kpi">
        <span>Pagos por revisar</span>
        <strong>{{ $kpis['pagos_por_revisar'] }}</strong>
        <a href="{{ route('admin.pagos.index') }}">Revisar →</a>
    </div>
    <div class="kpi">
        <span>Consultorías abiertas</span>
        <strong>{{ $kpis['consultorias_abiertas'] }}</strong>
        <a href="{{ route('admin.consultorias.index') }}">Ver →</a>
    </div>
    <div class="kpi">
        <span>Confirmadas este mes</span>
        <strong>{{ $kpis['confirmadas_mes'] }}</strong>
    </div>
    <div class="kpi">
        <span>Ingresos aprobados del mes</span>
        <strong>${{ number_format($kpis['ingresos_mes'], 0) }}</strong>
    </div>
    <div class="kpi">
        <span>Visitantes próximos 30 días</span>
        <strong>{{ $kpis['personas_proximas'] }}</strong>
        <a href="{{ route('admin.calendario') }}">Calendario →</a>
    </div>
</div>

<div class="grid-2">
    <section class="panel">
        <h2>Próximas visitas confirmadas</h2>
        @forelse ($proximas as $s)
            <div class="fila fila--entre mt-2">
                <div>
                    <strong>{{ $s->fecha_deseada->translatedFormat('D j M') }}</strong> ·
                    {{ $s->experiencias->pluck('nombre')->implode(' + ') }}
                    <div class="texto-chico texto-suave">{{ $s->persona?->nombre_completo }} · {{ $s->cant_persona }} pers.</div>
                </div>
                <a class="btn btn--secundario btn--chico" href="{{ route('admin.solicitudes.show', $s) }}">Ver</a>
            </div>
        @empty
            <p class="texto-suave">Sin visitas confirmadas por ahora.</p>
        @endforelse
    </section>

    <section class="panel">
        <h2>Experiencias más solicitadas</h2>
        @php $max = max(1, (int) $top->max('solicitudes')); @endphp
        @forelse ($top as $exp)
            <div class="barra-dato">
                <span>{{ $exp->nombre }}</span>
                <div class="barra-dato__pista"><span style="width: {{ round($exp->solicitudes / $max * 100) }}%"></span></div>
                <strong class="num">{{ $exp->solicitudes }}</strong>
            </div>
        @empty
            <p class="texto-suave">Aún no hay solicitudes.</p>
        @endforelse

        <h2 class="mt-4">Solicitudes por estado</h2>
        <div class="fila">
            @foreach (\App\Models\Solicitud::ESTADOS as $estado)
                <span class="fila"><x-estado :valor="$estado" /> <strong>{{ $porEstado[$estado] ?? 0 }}</strong></span>
            @endforeach
        </div>
    </section>
</div>
@endsection
