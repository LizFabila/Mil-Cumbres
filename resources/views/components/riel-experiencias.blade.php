{{-- Riel lateral "Experiencias": aparece en las páginas del cliente.
     La portada tiene su propia versión dentro del banner (inicio.blade.php). --}}
@props(['grupos'])

@if ($grupos->isNotEmpty())
    <nav class="hero-menu hero-menu--fijo" data-riel-experiencias aria-label="Experiencias disponibles">
        <button type="button" class="hero-menu__boton" data-riel-boton
                aria-expanded="false" aria-controls="riel-experiencias-cuerpo">
            <span class="hero-menu__hamburguesa" aria-hidden="true"><i></i><i></i><i></i></span>
            <span class="hero-menu__flechita" aria-hidden="true"></span>
            <span class="hero-menu__rotulo">Experiencias</span>
        </button>

        <div class="hero-menu__cuerpo" id="riel-experiencias-cuerpo">
            <p class="hero-menu__intro">Elige tu próxima experiencia</p>

            @foreach ($grupos as $tipo => $grupo)
                <div class="hero-menu__grupo">
                    <p class="hero-menu__tipo"><span>{{ $tipo }}</span></p>
                    <ul>
                        @foreach ($grupo as $experiencia)
                            <li>
                                <a class="hero-menu__item"
                                   href="{{ route('experiencias.show', [$experiencia->id_experiencia, $experiencia->slug]) }}">
                                    <span class="hero-menu__datos">
                                        <strong>{{ $experiencia->nombre }}</strong>
                                        <small>
                                            {{ $experiencia->zonas_texto ?: config('milcumbres.contacto.lugar') }}
                                            · {{ $experiencia->precio_texto }} · {{ preg_replace('/\s*\(.*\)$/', '', $experiencia->duracion_texto) }}
                                        </small>
                                    </span>
                                    <span class="hero-menu__flecha" aria-hidden="true">→</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <a class="hero-menu__todas" href="{{ route('experiencias.index') }}">
                Ver catálogo completo <span aria-hidden="true">→</span>
            </a>
        </div>
    </nav>
@endif
