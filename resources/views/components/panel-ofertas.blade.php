{{-- Panel lateral de ofertas (sitio público).
     Se abre con cualquier botón [data-mc-ofertas-abrir] o con el enlace #ofertas.
     Muestra solo las ofertas vigentes que el administrador dio de alta en Admin › Ofertas.
     Tablas: ofertas, experiencias --}}
@props(['ofertas' => collect()])

@if ($ofertas->isNotEmpty())
<div class="mc-ofertas" id="ofertas" data-mc-ofertas hidden>
    <button type="button" class="mc-ofertas__fondo" aria-label="Cerrar ofertas" data-mc-ofertas-cerrar></button>

    <aside class="mc-ofertas__panel" role="dialog" aria-modal="true" aria-labelledby="mc-ofertas-titulo">
        <header class="mc-ofertas__cabecera">
            <div>
                <span class="mc-ofertas__eyebrow">POR TIEMPO LIMITADO</span>
                <h2 id="mc-ofertas-titulo">Ofertas <em>de temporada</em></h2>
                <p>{{ $ofertas->count() }} {{ $ofertas->count() === 1 ? 'experiencia' : 'experiencias' }} con precio especial.</p>
            </div>
            <button type="button" class="mc-ofertas__cerrar" aria-label="Cerrar" data-mc-ofertas-cerrar>×</button>
        </header>

        <div class="mc-ofertas__lista">
            @foreach ($ofertas as $oferta)
                @php $exp = $oferta->experiencia; @endphp
                <article class="mc-oferta">
                    <div class="mc-oferta__foto">
                        <img src="{{ $exp->imagen_url }}" alt="" loading="lazy">
                        <span class="mc-oferta__sello">−{{ $oferta->porcentaje }}%</span>
                    </div>

                    <div class="mc-oferta__cuerpo">
                        <span class="mc-oferta__meta">
                            {{ collect([$exp->catalogo?->tipo, $exp->zonas_texto])->filter()->implode(' · ') }}
                        </span>
                        <h3>{{ $exp->nombre }}</h3>
                        @if ($oferta->etiqueta)
                            <p class="mc-oferta__etiqueta">{{ $oferta->etiqueta }}</p>
                        @endif

                        <div class="mc-oferta__precios">
                            <del>${{ number_format((float) $exp->precio) }}</del>
                            <strong>${{ number_format($oferta->precio_oferta) }} <small>MXN por persona</small></strong>
                        </div>

                        <p @class(['mc-oferta__vigencia', 'is-urgente' => $oferta->dias_restantes <= 3])>
                            @if ($oferta->dias_restantes === 0)
                                ⏳ Termina hoy
                            @elseif ($oferta->dias_restantes <= 3)
                                ⏳ Termina en {{ $oferta->dias_restantes }} {{ $oferta->dias_restantes === 1 ? 'día' : 'días' }}
                            @else
                                Válida hasta el {{ $oferta->termina->translatedFormat('j \d\e F') }}
                            @endif
                        </p>

                        <a class="mc-oferta__cta" href="{{ route('experiencias.show', [$exp, $exp->slug]) }}">
                            Reservar con descuento <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <footer class="mc-ofertas__pie">
            El precio de oferta se respeta si envías tu solicitud antes de que termine la vigencia.
        </footer>
    </aside>
</div>
@endif
