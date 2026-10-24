{{-- C16 · Calificar una experiencia vivida
     Tablas: calificaciones, calificacion_aspectos, aspectos_calificacion,
     asigna_experiencia, experiencias, solicitudes --}}

@extends('layouts.app')

@section('titulo', 'Califica tu experiencia')

@section('descripcion', 'Cuéntanos cómo te fue en tu experiencia con Mil Cumbres.')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/calificaciones-milcumbres.css') }}">
@endpush

@php
    $editando = (bool) $calificacion;
    $fecha = $asignacion->fechaExperiencia();
    $estrellasMarcadas = (int) old('estrellas', $estrellasIniciales);
    $recomendacion = old('recomendacion', $calificacion?->recomendacion);
    $etiquetas = \App\Models\Calificacion::ETIQUETAS_ESTRELLAS;
@endphp

@section('contenido')
<div class="mc-cal">

    <section class="mc-cal-encabezado">
        <div class="mc-cal-contenedor">
            <nav class="mc-cal-migas" aria-label="Migas de pan">
                <a href="{{ route('solicitudes.index') }}">Mis solicitudes</a>
                <span aria-hidden="true">/</span>
                <span>Calificar experiencia</span>
            </nav>

            <span class="mc-cal-eyebrow">TU OPINIÓN</span>
            <h1>
                ¿Cómo estuvo tu
                <em>experiencia?</em>
            </h1>
            <p>
                {{ $editando
                    ? 'Puedes cambiar tu calificación hasta el '.$calificacion->created_at->copy()->addDays((int) config('milcumbres.calificaciones.dias_para_editar', 7))->translatedFormat('j \d\e F').'.'
                    : 'Solo las estrellas son obligatorias; lo demás nos ayuda a mejorar.' }}
            </p>
        </div>
    </section>

    <section class="mc-cal-cuerpo">
        <div class="mc-cal-contenedor mc-cal-grid">

            <form method="{{ ($vistaPrevia ?? false) ? 'GET' : 'POST' }}"
                  action="{{ ($vistaPrevia ?? false) ? route('calificaciones.vista-previa') : route('calificaciones.store', $asignacion->id_asigna_exp) }}"
                  class="mc-cal-form"
                  id="mc-cal-form"
                  @if ($vistaPrevia ?? false) data-vista-previa @endif
                  novalidate>
                @csrf

                @if ($vistaPrevia ?? false)
                    <div class="aviso aviso--info mc-cal-vista-previa" role="status">
                        <strong>Vista previa:</strong> así se ve el formulario. Puedes probarlo, pero no se guarda nada.
                    </div>
                @endif

                <x-avisos />

                {{-- SATISFACCIÓN GENERAL (CSAT) --}}
                <fieldset class="mc-cal-bloque">
                    <legend class="mc-cal-pregunta">
                        En general, ¿qué tan satisfecho quedaste?
                        <span class="mc-cal-obligatorio">Obligatoria</span>
                    </legend>

                    <div class="mc-cal-estrellas">
                        <div class="mc-cal-estrellas__opciones">
                            @for ($n = 5; $n >= 1; $n--)
                                <input type="radio"
                                       id="estrellas-{{ $n }}"
                                       name="estrellas"
                                       value="{{ $n }}"
                                       data-etiqueta="{{ $etiquetas[$n] }}"
                                       @checked($estrellasMarcadas === $n)
                                       required>
                                <label for="estrellas-{{ $n }}" title="{{ $etiquetas[$n] }}">
                                    <span class="mc-cal-oculto">{{ $n }} {{ $n === 1 ? 'estrella' : 'estrellas' }}: {{ $etiquetas[$n] }}</span>
                                    <span aria-hidden="true">★</span>
                                </label>
                            @endfor
                        </div>
                        <span class="mc-cal-estrellas__texto" id="mc-cal-texto-estrellas" aria-live="polite">
                            {{ $estrellasMarcadas ? $etiquetas[$estrellasMarcadas] : 'Elige de 1 a 5' }}
                        </span>
                    </div>
                    @error('estrellas') <span class="mc-cal-error">{{ $message }}</span> @enderror
                </fieldset>

                {{-- RECOMENDACIÓN (NPS) --}}
                <fieldset class="mc-cal-bloque">
                    <legend class="mc-cal-pregunta">
                        ¿Qué tan probable es que nos recomiendes con un amigo o familiar?
                    </legend>

                    <div class="mc-cal-nps">
                        @for ($n = 0; $n <= 10; $n++)
                            <input type="radio"
                                   id="recomendacion-{{ $n }}"
                                   name="recomendacion"
                                   value="{{ $n }}"
                                   @checked($recomendacion !== null && (int) $recomendacion === $n)>
                            <label for="recomendacion-{{ $n }}">
                                <span class="mc-cal-oculto">{{ $n }} de 10</span>
                                <span aria-hidden="true">{{ $n }}</span>
                            </label>
                        @endfor
                    </div>
                    <div class="mc-cal-nps__extremos">
                        <span>0 · Nada probable</span>
                        <span>10 · Muy probable</span>
                    </div>
                    @error('recomendacion') <span class="mc-cal-error">{{ $message }}</span> @enderror
                </fieldset>

                {{-- ASPECTOS DEL SERVICIO --}}
                <fieldset class="mc-cal-bloque">
                    <legend class="mc-cal-pregunta">
                        Califica cada aspecto
                        <span class="mc-cal-opcional">Opcional</span>
                    </legend>

                    <div class="mc-cal-aspectos">
                        @foreach ($aspectos as $aspecto)
                            @php $valorAspecto = (int) old('aspectos.'.$aspecto->id_aspecto, $puntuaciones[$aspecto->id_aspecto] ?? 0); @endphp
                            <div class="mc-cal-aspecto">
                                <div>
                                    <span class="mc-cal-aspecto__nombre" id="aspecto-{{ $aspecto->id_aspecto }}">{{ $aspecto->nombre }}</span>
                                    @if ($aspecto->descripcion)
                                        <small>{{ $aspecto->descripcion }}</small>
                                    @endif
                                </div>
                                <div class="mc-cal-estrellas__opciones mc-cal-estrellas__opciones--chica"
                                     role="radiogroup"
                                     aria-labelledby="aspecto-{{ $aspecto->id_aspecto }}">
                                    @for ($n = 5; $n >= 1; $n--)
                                        <input type="radio"
                                               id="aspecto-{{ $aspecto->id_aspecto }}-{{ $n }}"
                                               name="aspectos[{{ $aspecto->id_aspecto }}]"
                                               value="{{ $n }}"
                                               @checked($valorAspecto === $n)>
                                        <label for="aspecto-{{ $aspecto->id_aspecto }}-{{ $n }}" title="{{ $n }} de 5">
                                            <span class="mc-cal-oculto">{{ $n }} de 5</span>
                                            <span aria-hidden="true">★</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </fieldset>

                {{-- COMENTARIO Y PERMISO --}}
                <div class="mc-cal-bloque">
                    <label class="mc-cal-pregunta" for="comentario">
                        ¿Qué fue lo mejor y qué mejorarías?
                        <span class="mc-cal-opcional">Opcional</span>
                    </label>
                    <textarea id="comentario"
                              name="comentario"
                              class="mc-cal-area"
                              maxlength="1500"
                              rows="5"
                              placeholder="Ej. El guía conocía cada ave por su canto. Mejoraría la señalización del punto de encuentro.">{{ old('comentario', $calificacion?->comentario) }}</textarea>
                    <span class="mc-cal-contador" id="mc-cal-contador" aria-live="polite"></span>
                    @error('comentario') <span class="mc-cal-error">{{ $message }}</span> @enderror

                    <label class="mc-cal-check">
                        <input type="checkbox"
                               name="permite_publicar"
                               value="1"
                               @checked(old('permite_publicar', $calificacion?->permite_publicar))>
                        <span>
                            Acepto que mi opinión se publique en el sitio con mi nombre y la inicial
                            de mi apellido. Mil Cumbres la revisa antes de publicarla.
                        </span>
                    </label>
                </div>

                <div class="mc-cal-bloque mc-cal-enviar">
                    <button type="submit" class="mc-cal-boton">
                        {{ $editando ? 'Guardar cambios' : 'Enviar calificación' }}
                    </button>
                    <a href="{{ route('solicitudes.index') }}" class="mc-cal-boton mc-cal-boton--borde">Volver</a>
                </div>
            </form>

            {{-- EXPERIENCIA QUE SE CALIFICA --}}
            <aside class="mc-cal-lateral">
                <img src="{{ $experiencia->imagen_url }}" alt="" loading="lazy">
                <div class="mc-cal-lateral__cuerpo">
                    <span class="mc-cal-eyebrow">EXPERIENCIA QUE CALIFICAS</span>
                    <h2>{{ $experiencia->nombre }}</h2>
                    <dl>
                        <div>
                            <dt>Fecha</dt>
                            <dd>{{ $fecha ? $fecha->translatedFormat('j \d\e F \d\e Y') : 'Sin fecha' }}</dd>
                        </div>
                        @if ($experiencia->zona)
                            <div>
                                <dt>Zona</dt>
                                <dd>{{ $experiencia->zona->nombre }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt>Folio</dt>
                            <dd>{{ $solicitud->folio }}</dd>
                        </div>
                    </dl>
                    <p>Solo quien vivió la experiencia puede calificarla, y una sola vez.</p>
                </div>
            </aside>

        </div>
    </section>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/calificacion.js') }}" defer></script>
@endpush
