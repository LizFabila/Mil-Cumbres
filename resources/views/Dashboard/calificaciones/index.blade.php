{{-- A11 · Opiniones de clientes
     Tablas: calificaciones, calificacion_aspectos, aspectos_calificacion,
     personas, experiencias, asigna_experiencia, solicitudes --}}
@extends('layouts.admin')

@section('titulo', 'Opiniones')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-calificaciones-milcumbres.css') }}">
@endpush

@php
    $estadoActivo = $filtros['estado'] ?? null;
    $textoEstado = ['pendiente' => 'Pendiente', 'publicada' => 'Publicada', 'oculta' => 'Oculta'];
    $estrellas = fn (int $n) => str_repeat('★', $n).'<span class="vacia">'.str_repeat('★', 5 - $n).'</span>';
@endphp

@section('contenido')
<div class="mc-a11">

    <div class="admin-cabecera">
        <div>
            <h1>Opiniones de clientes</h1>
            <p>Calificaciones de las experiencias vividas. Publica, oculta o responde cada opinión.</p>
        </div>
    </div>

    {{-- INDICADORES --}}
    <div class="mc-a11__kpis">
        <div class="mc-a11__kpi">
            <span>Promedio</span>
            <strong>{{ $metricas['promedio'] !== null ? number_format($metricas['promedio'], 1).' ★' : '—' }}</strong>
            <small>{{ $metricas['total'] }} {{ $metricas['total'] === 1 ? 'calificación' : 'calificaciones' }}</small>
        </div>
        <div class="mc-a11__kpi">
            <span>CSAT</span>
            <strong>{{ $metricas['csat'] !== null ? $metricas['csat'].'%' : '—' }}</strong>
            <small>Calificaciones de 4 y 5 estrellas</small>
        </div>
        <div class="mc-a11__kpi">
            <span>NPS</span>
            <strong>{{ $metricas['nps'] !== null ? ($metricas['nps'] > 0 ? '+' : '').$metricas['nps'] : '—' }}</strong>
            <small>
                @if ($metricas['nps'] !== null)
                    {{ $metricas['promotores'] }}% promotores · {{ $metricas['detractores'] }}% detractores
                @else
                    Sin respuestas de recomendación
                @endif
            </small>
        </div>
        <a @class(['mc-a11__kpi', 'mc-a11__kpi--alerta' => $porAtender > 0])
           href="{{ route('admin.calificaciones.index', ['estado' => 'atencion']) }}">
            <span>Por atender</span>
            <strong>{{ $porAtender }}</strong>
            <small>2 estrellas o menos sin respuesta</small>
        </a>
    </div>

    {{-- PROMEDIO POR ASPECTO --}}
    @if ($aspectos->whereNotNull('promedio')->isNotEmpty())
        <section class="panel mc-a11__aspectos" aria-label="Promedio por aspecto">
            <h2>Promedio por aspecto</h2>
            @foreach ($aspectos as $aspecto)
                <div class="mc-a11__aspecto">
                    <span>{{ $aspecto['nombre'] }}</span>
                    <span class="mc-a11__pista" aria-hidden="true">
                        <span style="width: {{ $aspecto['promedio'] ? round($aspecto['promedio'] / 5 * 100) : 0 }}%"></span>
                    </span>
                    <strong>{{ $aspecto['promedio'] ? number_format($aspecto['promedio'], 1) : '—' }}</strong>
                </div>
            @endforeach
        </section>
    @endif

    {{-- FILTROS --}}
    <nav class="pestanas" aria-label="Filtrar por estado">
        <a href="{{ route('admin.calificaciones.index', request()->except('estado', 'page')) }}" @class(['activo' => ! $estadoActivo])>
            Todas <small>{{ $conteos->sum() }}</small>
        </a>
        @foreach ($textoEstado as $valor => $texto)
            <a href="{{ route('admin.calificaciones.index', array_merge(request()->except('page'), ['estado' => $valor])) }}"
               @class(['activo' => $estadoActivo === $valor])>
                {{ $texto }} <small>{{ $conteos[$valor] ?? 0 }}</small>
            </a>
        @endforeach
        <a href="{{ route('admin.calificaciones.index', array_merge(request()->except('page'), ['estado' => 'atencion'])) }}"
           @class(['activo' => $estadoActivo === 'atencion'])>
            Por atender <small>{{ $porAtender }}</small>
        </a>
    </nav>

    <form class="barra-filtros" method="GET">
        @if ($estadoActivo) <input type="hidden" name="estado" value="{{ $estadoActivo }}"> @endif
        <div class="campo">
            <label for="experiencia">Experiencia</label>
            <select class="control" id="experiencia" name="experiencia">
                <option value="">Todas</option>
                @foreach ($experiencias as $exp)
                    <option value="{{ $exp->id_experiencia }}" @selected(($filtros['experiencia'] ?? null) == $exp->id_experiencia)>{{ $exp->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="campo">
            <label for="estrellas">Estrellas</label>
            <select class="control" id="estrellas" name="estrellas">
                <option value="">Todas</option>
                @for ($n = 5; $n >= 1; $n--)
                    <option value="{{ $n }}" @selected(($filtros['estrellas'] ?? null) == $n)>{{ $n }} {{ $n === 1 ? 'estrella' : 'estrellas' }}</option>
                @endfor
            </select>
        </div>
        <button class="btn btn--primario" type="submit">Filtrar</button>
    </form>

    {{-- LISTA DE OPINIONES --}}
    <div class="mc-a11__lista">
        @forelse ($calificaciones as $c)
            @php $bajo = $c->estrellas <= 2 && ! $c->respuesta && $c->estado !== 'oculta'; @endphp
            <article @class(['mc-a11__opinion', 'mc-a11__opinion--bajo' => $bajo])>

                <header class="mc-a11__opinion-cabeza">
                    <div>
                        <span class="mc-a11__estrellas" aria-label="{{ $c->estrellas }} de 5 estrellas">{!! $estrellas($c->estrellas) !!}</span>
                        <strong>{{ $c->experiencia?->nombre ?? 'Experiencia eliminada' }}</strong>
                        <small>
                            {{ $c->persona?->nombre_completo ?? 'Cliente' }}
                            @if ($c->asignacion?->solicitud)
                                · <a href="{{ route('admin.solicitudes.show', $c->asignacion->solicitud) }}">{{ $c->asignacion->solicitud->folio }}</a>
                            @endif
                            · {{ $c->created_at->translatedFormat('j M Y') }}
                        </small>
                    </div>
                    <div class="mc-a11__etiquetas">
                        @if ($bajo)
                            <span class="mc-a11__chip mc-a11__chip--bajo">Requiere atención</span>
                        @endif
                        <span class="mc-a11__chip mc-a11__chip--{{ $c->estado }}">{{ $textoEstado[$c->estado] }}</span>
                        @unless ($c->permite_publicar)
                            <span class="mc-a11__chip">Sin permiso para publicar</span>
                        @endunless
                    </div>
                </header>

                @if ($c->comentario)
                    <p class="mc-a11__comentario">{{ $c->comentario }}</p>
                @else
                    <p class="mc-a11__comentario mc-a11__comentario--vacio">Sin comentario.</p>
                @endif

                <dl class="mc-a11__datos">
                    <div>
                        <dt>Recomendación</dt>
                        <dd>{{ $c->recomendacion !== null ? $c->recomendacion.' / 10' : '—' }}</dd>
                    </div>
                    @foreach ($c->aspectos as $pa)
                        <div>
                            <dt>{{ $pa->aspecto?->nombre }}</dt>
                            <dd>{{ $pa->puntuacion }} ★</dd>
                        </div>
                    @endforeach
                </dl>

                <div class="mc-a11__acciones">
                    <form method="POST" action="{{ route('admin.calificaciones.estado', $c) }}" class="mc-a11__moderar">
                        @csrf @method('PATCH')
                        @if ($c->estado !== 'publicada' && $c->permite_publicar)
                            <button class="btn btn--primario btn--chico" name="estado" value="publicada">Publicar</button>
                        @endif
                        @if ($c->estado !== 'oculta')
                            <button class="btn btn--secundario btn--chico" name="estado" value="oculta">Ocultar</button>
                        @endif
                        @if ($c->estado !== 'pendiente')
                            <button class="btn btn--secundario btn--chico" name="estado" value="pendiente">Regresar a revisión</button>
                        @endif
                    </form>

                    <details class="mc-a11__responder" @if ($bajo) open @endif>
                        <summary>{{ $c->respuesta ? 'Editar respuesta' : 'Responder' }}</summary>
                        <form method="POST" action="{{ route('admin.calificaciones.respuesta', $c) }}">
                            @csrf @method('PATCH')
                            <label class="mc-a11__oculto" for="respuesta-{{ $c->id_calificacion }}">Respuesta pública</label>
                            <textarea class="control" id="respuesta-{{ $c->id_calificacion }}" name="respuesta" rows="3" maxlength="1000"
                                      placeholder="Esta respuesta se muestra junto a la opinión cuando está publicada.">{{ $c->respuesta }}</textarea>
                            <button class="btn btn--primario btn--chico" type="submit">Guardar respuesta</button>
                        </form>
                    </details>
                </div>

                @if ($c->respuesta)
                    <p class="mc-a11__respuesta">
                        <strong>Tu respuesta</strong>
                        ({{ $c->fecha_respuesta?->translatedFormat('j M Y') }}): {{ $c->respuesta }}
                    </p>
                @endif

            </article>
        @empty
            <div class="panel mc-a11__vacio">
                <strong>No hay opiniones {{ $estadoActivo ? 'con este filtro' : 'todavía' }}.</strong>
                <p>Las opiniones llegan cuando una solicitud se marca como completada y el cliente califica su experiencia.</p>
            </div>
        @endforelse
    </div>

    {{ $calificaciones->links() }}

</div>
@endsection
