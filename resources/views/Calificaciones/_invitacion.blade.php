{{-- C16 · Invitación a calificar dentro de una reservación (se incluye en Mis solicitudes).
     Recibe: $solicitud. Usa $calificacionesMias (keyBy id_asigna_exp) del controlador.
     Tablas: asigna_experiencia, calificaciones --}}

@php
    $calificacionesMias = $calificacionesMias ?? collect();

    $porCalificar = $solicitud->estado !== 'completada'
        ? collect()
        : $solicitud->experiencias
            ->map(function ($exp) use ($solicitud, $calificacionesMias) {
                $asignacion = $exp->pivot;
                $asignacion->setRelation('solicitud', $solicitud);

                return [
                    'experiencia' => $exp,
                    'asignacion' => $asignacion,
                    'calificacion' => $calificacionesMias->get($asignacion->id_asigna_exp),
                ];
            })
            // Se muestran: las ya calificadas, las que se pueden calificar hoy
            // y las que aún no llegan a su fecha (con aviso de cuándo se podrá).
            ->filter(fn ($item) => $item['calificacion']
                || $item['asignacion']->puedeCalificarse()
                || $item['asignacion']->fechaExperiencia()?->isFuture());
@endphp

@if ($porCalificar->isNotEmpty())

    @once
        @push('estilos')
            <link rel="stylesheet" href="{{ asset('css/calificaciones-milcumbres.css') }}">
        @endpush
    @endonce

    <div class="mc-cal-invita-lista">

        @foreach ($porCalificar as $item)

            @php
                $exp = $item['experiencia'];
                $asignacion = $item['asignacion'];
                $cal = $item['calificacion'];
            @endphp

            @if ($cal)

                <div class="mc-cal-hecha">
                    <span class="mc-cal-hecha__estrellas" aria-label="{{ $cal->estrellas }} de 5 estrellas">
                        @for ($i = 1; $i <= 5; $i++)<span @class(['vacia' => $i > $cal->estrellas])>★</span>@endfor
                    </span>
                    <span>
                        Calificaste <strong>{{ $exp->nombre }}</strong>
                        el {{ $cal->created_at->translatedFormat('j \d\e F') }}. ¡Gracias!
                    </span>
                    @if ($cal->esEditable())
                        <a href="{{ route('calificaciones.create', $asignacion->id_asigna_exp) }}">Editar</a>
                    @endif
                </div>

            @elseif ($asignacion->fechaExperiencia()?->isFuture())

                <div class="mc-cal-hecha mc-cal-hecha--proxima">
                    <span class="mc-cal-hecha__estrellas" aria-hidden="true">
                        @for ($i = 1; $i <= 5; $i++)<span class="vacia">★</span>@endfor
                    </span>
                    <span>
                        Podrás calificar <strong>{{ $exp->nombre }}</strong>
                        a partir del {{ $asignacion->fechaExperiencia()->translatedFormat('j \d\e F') }}, después de vivirla.
                    </span>
                </div>

            @else

                <div class="mc-cal-invita">
                    <div>
                        <strong>¿Cómo te fue en {{ $exp->nombre }}?</strong>
                        <p>
                            Tu opinión nos ayuda a cuidar la experiencia y la montaña. Toma un minuto.
                            Puedes calificarla hasta el {{ $asignacion->limiteParaCalificar()->translatedFormat('j \d\e F') }}.
                        </p>
                    </div>
                    <a class="mc-cal-invita__boton"
                       href="{{ route('calificaciones.create', $asignacion->id_asigna_exp) }}">
                        Calificar experiencia
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>

            @endif

        @endforeach

    </div>

@endif
