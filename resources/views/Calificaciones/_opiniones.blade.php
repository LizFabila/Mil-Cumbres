{{-- C03 · Opiniones verificadas de una experiencia (se incluye en Experiencias/show).
     Recibe: $experiencia.
     Tablas: calificaciones, calificacion_aspectos, aspectos_calificacion, personas --}}

@php
    $opiniones = $experiencia->resumenOpiniones();
    $m = $opiniones['metricas'];
@endphp

@if ($m['total'] > 0)

    @once
        @push('estilos')
            <link rel="stylesheet" href="{{ asset('css/calificaciones-milcumbres.css') }}">
        @endpush
    @endonce

    <article class="mc-detalle-seccion mc-cal-opiniones" id="opiniones">

        <span class="mc-detalle-etiqueta">
            <span class="mc-detalle-linea"></span>
            OPINIONES VERIFICADAS
        </span>

        <h2>
            Lo que dicen
            <em>quienes ya fueron.</em>
        </h2>

        <div class="mc-cal-opiniones__grid">

            {{-- PROMEDIO Y DISTRIBUCIÓN --}}
            @if ($opiniones['mostrarPromedio'])
                <div class="mc-cal-promedio">
                    <div class="mc-cal-promedio__cabeza">
                        <span class="mc-cal-promedio__valor">{{ number_format($m['promedio'], 1) }}</span>
                        <span>
                            <span class="mc-cal-promedio__estrellas" aria-label="{{ number_format($m['promedio'], 1) }} de 5 estrellas">
                                @for ($i = 1; $i <= 5; $i++)<span @class(['vacia' => $i > round($m['promedio'])])>★</span>@endfor
                            </span>
                            <small>{{ $m['total'] }} {{ $m['total'] === 1 ? 'calificación' : 'calificaciones' }}</small>
                        </span>
                    </div>

                    <div class="mc-cal-distribucion">
                        @foreach ($m['distribucion'] as $estrellas => $cantidad)
                            <div>
                                <span>{{ $estrellas }}★</span>
                                <span class="mc-cal-distribucion__pista">
                                    <span style="width: {{ $m['total'] ? round($cantidad / $m['total'] * 100) : 0 }}%"></span>
                                </span>
                                <span>{{ $cantidad }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if ($opiniones['aspectos']->isNotEmpty())
                        <span class="mc-cal-subtitulo">Por aspecto</span>
                        <div class="mc-cal-aspectos-promedio">
                            @foreach ($opiniones['aspectos'] as $aspecto)
                                <div>
                                    <span>{{ $aspecto['nombre'] }}</span>
                                    <strong>{{ number_format($aspecto['promedio'], 1) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="mc-cal-promedio mc-cal-promedio--pocas">
                    <p>
                        Esta experiencia tiene {{ $m['total'] }}
                        {{ $m['total'] === 1 ? 'calificación' : 'calificaciones' }}.
                        Mostraremos el promedio a partir de
                        {{ config('milcumbres.calificaciones.minimo_para_promedio', 3) }}.
                    </p>
                </div>
            @endif

            {{-- TESTIMONIOS --}}
            <div class="mc-cal-testimonios">
                @forelse ($opiniones['testimonios'] as $t)
                    <blockquote class="mc-cal-testimonio">
                        <header>
                            <span>
                                <strong>{{ $t->nombre_publico }}</strong>
                                @if ($fechaVisita = $t->asignacion?->fecha_deseada)
                                    · visitó en {{ $fechaVisita->translatedFormat('M Y') }}
                                @endif
                            </span>
                            <span class="mc-cal-promedio__estrellas" aria-label="{{ $t->estrellas }} de 5 estrellas">
                                @for ($i = 1; $i <= 5; $i++)<span @class(['vacia' => $i > $t->estrellas])>★</span>@endfor
                            </span>
                        </header>
                        <p>{{ $t->comentario }}</p>
                        @if ($t->respuesta)
                            <footer class="mc-cal-respuesta">
                                <strong>Respuesta de Mil Cumbres:</strong> {{ $t->respuesta }}
                            </footer>
                        @endif
                    </blockquote>
                @empty
                    <p class="mc-cal-sin-testimonios">
                        Aún no hay comentarios publicados para esta experiencia.
                    </p>
                @endforelse
            </div>

        </div>

    </article>

@endif
