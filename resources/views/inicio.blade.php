
{{-- C01 · Inicio rediseñado
     Tablas: catalogo_experiencias, experiencias
--}}

@extends('layouts.app')

@section('titulo', 'Inicio')
@section('sin-riel', '1')

@section('contenido')

@php
    $mensajesTipo = [
        'Bienestar' => 'Un momento para respirar, descansar y reconectar.',
        'Natural' => 'Explora paisajes, senderos y la vida del bosque.',
        'Cultural' => 'Descubre historias, tradiciones y saberes locales.',
    ];
@endphp

<div class="inicio">

    {{-- ======================================= --}}
    {{-- HERO PRINCIPAL --}}
    {{-- Frases fijas en todo el carrusel; cada diapositiva cambia solo la imagen y el título de la sección.
         Textos e imágenes: config/hero_experiencias.php --}}
    {{-- ======================================= --}}

    @php
        $carruselPdf = collect(config('hero_experiencias'))->map(fn ($e) => (object) $e);
        $hayCarrusel = $carruselPdf->isNotEmpty();
    @endphp

    <section class="inicio-hero"
             data-hero-carrusel
             aria-roledescription="carrusel"
             aria-label="Bienvenida y experiencias disponibles">

        {{-- Fotos: una por sección --}}
        @foreach ($carruselPdf as $experiencia)
            <div class="inicio-hero__foto {{ $loop->first ? 'is-activa' : '' }}" data-diapositiva="{{ $loop->index }}" {!! ! empty($experiencia->sombra ?? null) ? 'data-sombra="'.e($experiencia->sombra ?? '').'"' : '' !!} {!! ! empty($experiencia->mariposas ?? null) ? 'data-mariposas' : '' !!} aria-hidden="true">
                <img src="{{ asset($experiencia->imagen) }}" alt="" {{ $loop->first ? 'fetchpriority=high' : 'loading=lazy' }} decoding="async">
            </div>
        @endforeach

        <div class="inicio-hero__sombra"></div>

        {{-- Menú lateral: lista de las diapositivas; el clic lleva al catálogo. --}}
        @if ($hayCarrusel)
            <nav class="hero-menu" data-hero-menu aria-label="Experiencias disponibles">
                <button type="button" class="hero-menu__boton" data-hero-menu-boton
                        aria-expanded="false" aria-controls="hero-menu-cuerpo">
                    <span class="hero-menu__hamburguesa" aria-hidden="true"><i></i><i></i><i></i></span>
                    <span class="hero-menu__flechita" aria-hidden="true"></span>
                    <span class="hero-menu__rotulo">Experiencias</span>
                </button>

                <div class="hero-menu__cuerpo" id="hero-menu-cuerpo">
                    <p class="hero-menu__intro">Elige tu próxima experiencia</p>

                    <div class="hero-menu__grupo">
                        <ul>
                            @foreach ($carruselPdf as $experiencia)
                                <li>
                                    <a class="hero-menu__item"
                                       href="{{ route('experiencias.index') }}"
                                       data-hero-ver="{{ $loop->index }}"
                                       data-nombre="{{ $experiencia->nombre }}">
                                        <span class="hero-menu__datos">
                                            <strong>{{ $experiencia->nombre }}</strong>
                                        </span>
                                        <span class="hero-menu__flecha" aria-hidden="true">→</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <a class="hero-menu__todas" href="{{ route('experiencias.index') }}">
                        Ver catálogo completo <span aria-hidden="true">→</span>
                    </a>
                </div>
            </nav>
        @endif

        <div class="contenedor inicio-hero__contenido">

          {{-- Frases fijas arriba: no cambian ni se animan al rotar el carrusel --}}
          <div class="inicio-hero__fijo">

                <h1 class="inicio-hero__bienvenida">
                    Regeneramos la naturaleza y las organizaciones
                    <em>a través del turismo</em>
                </h1>

                <p class="inicio-hero__descripcion inicio-hero__descripcion--bienvenida">
                    Conecta con tu ser, tu comunidad y la naturaleza.
                </p>

                <p class="inicio-hero__lema">Vive el viaje que transforma</p>

          </div>

          <div class="inicio-hero__paneles">

          {{-- Diapositivas: título de cada sección y botón al catálogo --}}
          @foreach ($carruselPdf as $experiencia)
            <div class="inicio-hero__panel {{ $loop->first ? 'is-activo' : '' }}"
                 data-diapositiva="{{ $loop->index }}"
                 role="group"
                 aria-roledescription="diapositiva"
                 aria-label="{{ $experiencia->nombre }}"
                 hidden>

                <div class="inicio-hero__texto">

                    <h2 class="inicio-hero__titulo">{{ $experiencia->nombre }}</h2>

                </div>

            </div>
          @endforeach

          </div>

          {{-- Parte inferior fija: lema y botones --}}
          <div class="inicio-hero__pie">


                <div class="inicio-hero__acciones">

                    <a href="{{ route('experiencias.index') }}#catalogo-resultados"
                       class="inicio-btn inicio-btn--claro">
                        Reservar
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a href="{{ route('modelo') }}"
                       class="inicio-hero__enlace">
                        Conoce nuestra filosofía
                        <span aria-hidden="true">→</span>
                    </a>

                </div>

          </div>

          @if ($hayCarrusel)
            {{-- Controles del carrusel --}}
            <div class="inicio-hero__controles">
                <button type="button" class="inicio-hero__control" data-hero-anterior aria-label="Diapositiva anterior">
                    <span aria-hidden="true">←</span>
                </button>

                <div class="inicio-hero__indicadores" role="tablist" aria-label="Elegir diapositiva">
                    @foreach ($carruselPdf as $experiencia)
                        <button type="button" class="inicio-hero__indicador {{ $loop->first ? 'is-activo' : '' }}" data-hero-ir="{{ $loop->index }}" role="tab"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="{{ $experiencia->nombre }}"><span></span></button>
                    @endforeach
                </div>

                <button type="button" class="inicio-hero__control" data-hero-siguiente aria-label="Diapositiva siguiente">
                    <span aria-hidden="true">→</span>
                </button>

                <button type="button" class="inicio-hero__control inicio-hero__control--pausa" data-hero-pausa
                        aria-label="Pausar carrusel" aria-pressed="false">
                    <span class="inicio-hero__icono-pausa" aria-hidden="true"></span>
                </button>
            </div>
          @endif

        </div>

        <a class="inicio-hero__scroll"
           href="#explorar"
           aria-label="Ir a las experiencias">
            <span>DESCUBRE MÁS</span>
            <span aria-hidden="true">↓</span>
        </a>

    </section>


    {{-- ======================================= --}}
    {{-- ======================================= --}}
    {{-- SERVICIOS (según el PDF «Mil Cumbres Turismo Regenerativo», página 5) --}}
    {{-- ======================================= --}}

    @php
        $coloresServicios = [
            'var(--naranja-monarca, #E8771E)',
            'var(--azul, var(--tema-secundario, #2F6690))',
            'var(--verde, var(--tema-principal, #176954))',
            'var(--naranja-monarca, #E8771E)',
            'var(--azul, var(--tema-secundario, #2F6690))',
        ];
        $iconosServicios = ['ico-experiencias.png', 'ico-transformacion.png', 'ico-pedagogia.png', 'ico-terapia.png', 'ico-proyectos.png'];
        $servicios = [
            ['Experiencias', 'Vivencias que trascienden en la vida. Diseñamos experiencias que reconectan con la esencia de ser, la comunidad y la naturaleza, despertando conciencia y dejando impacto en los viajeros y en el territorio.', 'Senderismo, avistamiento de luciérnagas, mariposa monarca, observación de aves, recolección de hongos silvestres, baño de bosque, meditación y campamentos.'],
            ['Transformación organizacional', 'Programas empresariales en entornos naturales que fortalecen equipos, inspiran propósito y promueven el liderazgo regenerativo.', 'La naturaleza se convierte en espacio de creatividad, innovación y alta conexión. A través de dinámicas y herramientas de impacto, cultivamos bienestar, cohesión y sentido compartido para potenciar la alineación de equipos, la colaboración y la productividad.'],
            ['Pedagogía de naturaleza', 'Metodologías vivenciales para escuelas, que integran ciencia, arte y juego en el aprendizaje al aire libre.', 'Fomentamos la educación ambiental conectando experiencias recreativas con proyectos de regeneración, inspirando una nueva forma de aprender y convivir con la naturaleza.'],
            ['Terapia de naturaleza', 'Espacios seguros donde las personas pueden conectarse de manera sana y recíproca con la naturaleza a través de los sentidos.', 'Ideal para personas y organizaciones que buscan bienestar consciente y armonía. La naturaleza nos guía en procesos de reconexión y transformación personal y colectiva.'],
            ['Proyectos', 'Consultoría y acciones colectivas que regeneran ecosistemas y fortalecen comunidades.', 'Contribuimos a la naturaleza a través de todas nuestras experiencias, impulsando un modelo integral que crea las condiciones fértiles para que la vida prospere.'],
        ];
    @endphp

    <section class="ini2-servicios" id="servicios" aria-labelledby="ini2-servicios-titulo">
        <div class="contenedor">

            <div class="ini2-cabecera" data-ini2>
                <h2 id="ini2-servicios-titulo">Servicios</h2>
            </div>

            <div class="ini2-servicios__lista">
                @foreach ($servicios as $servicio)
                    <article class="ini2-servicio ini2-elevable" data-ini2
                             style="--c: {{ $coloresServicios[$loop->index % count($coloresServicios)] }}; --retardo: {{ $loop->index * 120 }}ms">
                        <div class="ini2-servicio__cabecera">
                            <img class="ini2-servicio__icono" src="{{ asset('img/servicios/'.$iconosServicios[$loop->index]) }}" alt="" aria-hidden="true" loading="lazy" decoding="async">
                            <h3 class="ini2-servicio__nombre">{{ $servicio[0] }}</h3>
                        </div>
                        <p class="ini2-servicio__texto">{{ $servicio[1] }}</p>
                        <p class="ini2-servicio__detalle">{{ $servicio[2] }}</p>
                    </article>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ¿QUIÉNES SOMOS? --}}
    {{-- ======================================= --}}

    <section class="ini2-quienes" id="quienes-somos">
        <div class="contenedor">

            <div class="ini2-cabecera" data-ini2>
            </div>

            <div class="ini2-pasos-tarjetas">
                <article class="ini2-paso-card ini2-paso-card--mision ini2-elevable" data-ini2 style="--retardo: 0ms">
                    <div class="ini2-paso-card__cabecera" aria-hidden="true"></div>
                    <span class="ini2-paso-card__icono" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9.5"/><polygon points="15.5 8.5 13.5 13.5 8.5 15.5 10.5 10.5 15.5 8.5"/></svg>
                    </span>
                    <h3>Misión</h3>
                    <p>Conectar a las personas con el territorio a través de experiencias auténticas que cuidan la naturaleza y benefician a la comunidad.</p>
                </article>

                <article class="ini2-paso-card ini2-paso-card--vision ini2-elevable" data-ini2 style="--retardo: 450ms">
                    <div class="ini2-paso-card__cabecera" aria-hidden="true"></div>
                    <span class="ini2-paso-card__icono" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                    <h3>Visión</h3>
                    <p>Ser un referente de turismo regenerativo, donde viajar ayude a restaurar el entorno y fortalezca a quienes lo habitan.</p>
                </article>

                <article class="ini2-paso-card ini2-paso-card--valores ini2-elevable" data-ini2 style="--retardo: 900ms">
                    <div class="ini2-paso-card__cabecera" aria-hidden="true"></div>
                    <span class="ini2-paso-card__icono" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.5 19 2c1 2 2 4.2 2 8 0 5.5-4.8 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.5 12 13 13 12"/></svg>
                    </span>
                    <h3>Valores</h3>
                    <ul class="ini2-paso-card__lista">
                        <li>Respeto a la naturaleza</li>
                        <li>Comunidad local</li>
                        <li>Experiencias auténticas</li>
                        <li>Atención en cada detalle</li>
                    </ul>
                </article>
            </div>

            <div class="ini2-historia">
                <h3 class="ini2-historia__titulo" data-ini2>Nuestra historia</h3>

                <div class="ini2-linea-tiempo">
                    <span class="ini2-linea-tiempo__barra" aria-hidden="true"></span>

                    {{-- Etapa 1 (arriba) --}}
                    <div class="ini2-hito ini2-hito--arriba" style="--c: var(--verde, var(--tema-principal, #176954))">
                        <div class="ini2-hito__contenido" data-ini2 style="--retardo: 0ms">
                            <span class="ini2-hito__numero">01</span>
                            <h4>El origen</h4>
                            <p>Una idea sencilla: mostrar Valle de Bravo a través de sus paisajes, su gente y su cuidado.</p>
                        </div>
                        <div class="ini2-hito__eje" data-ini2 style="--retardo: 0ms" aria-hidden="true">
                            <span class="ini2-hito__tallo"></span>
                            <span class="ini2-hito__nodo"></span>
                        </div>
                    </div>

                    {{-- Etapa 2 (abajo) --}}
                    <div class="ini2-hito ini2-hito--abajo" style="--c: var(--azul, var(--tema-secundario, #2F6690))">
                        <div class="ini2-hito__contenido" data-ini2 style="--retardo: 350ms">
                            <span class="ini2-hito__numero">02</span>
                            <h4>Primeras experiencias</h4>
                            <p>Recorridos guiados de naturaleza, bienestar y cultura.</p>
                        </div>
                        <div class="ini2-hito__eje" data-ini2 style="--retardo: 350ms" aria-hidden="true">
                            <span class="ini2-hito__tallo"></span>
                            <span class="ini2-hito__nodo"></span>
                        </div>
                    </div>

                    {{-- Etapa 3 (arriba) --}}
                    <div class="ini2-hito ini2-hito--arriba" style="--c: var(--verde-bosque, var(--tema-oscuro, #143D36))">
                        <div class="ini2-hito__contenido" data-ini2 style="--retardo: 700ms">
                            <span class="ini2-hito__numero">03</span>
                            <h4>Crecer con la comunidad</h4>
                            <p>Trabajo conjunto con guías y personas de la región.</p>
                        </div>
                        <div class="ini2-hito__eje" data-ini2 style="--retardo: 700ms" aria-hidden="true">
                            <span class="ini2-hito__tallo"></span>
                            <span class="ini2-hito__nodo"></span>
                        </div>
                    </div>

                    {{-- Etapa 4 (abajo) --}}
                    <div class="ini2-hito ini2-hito--abajo" style="--c: var(--verde, var(--tema-principal, #176954))">
                        <div class="ini2-hito__contenido" data-ini2 style="--retardo: 1050ms">
                            <span class="ini2-hito__numero">04</span>
                            <h4>Hoy</h4>
                            <p>Un catálogo de experiencias que se reserva en línea.</p>
                        </div>
                        <div class="ini2-hito__eje" data-ini2 style="--retardo: 1050ms" aria-hidden="true">
                            <span class="ini2-hito__tallo"></span>
                            <span class="ini2-hito__nodo"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- ======================================= --}}
    {{-- GALERÍA --}}
    {{-- ======================================= --}}

    <section class="ini2-galeria" aria-labelledby="ini2-galeria-titulo">
        <div class="contenedor ini2-cabecera ini2-cabecera--fila" data-ini2>
            <div>
                <span class="inicio-kicker">
                    <span class="inicio-kicker__linea"></span>
                    Galería
                </span>
                <h2 id="ini2-galeria-titulo">Momentos en el <em>territorio</em></h2>
            </div>
            <div class="ini2-controles">
                <button type="button" data-ini2-anterior="galeria" aria-label="Foto anterior">‹</button>
                <button type="button" data-ini2-siguiente="galeria" aria-label="Foto siguiente">›</button>
            </div>
        </div>

        <div class="ini2-carrusel" data-ini2-carrusel="galeria" tabindex="0" aria-label="Galería de fotos">
            @foreach ([
                ['g1', 'Observación de aves en el bosque'],
                ['g2', 'Una pausa entre senderos'],
                ['g3', 'Viajero, con la camiseta de Mil Cumbres'],
                ['g4', 'Explicación del paisaje en campo'],
                ['g5', 'Un día de río'],
                ['g6', 'Frutos del bosque'],
                ['g7', 'Flores silvestres del bosque'],
                ['g8', 'Registro de lo que encontramos'],
                ['g9', 'Observación de cerca'],
            ] as [$archivo, $texto])
                <figure class="ini2-carrusel__foto">
                    <img src="{{ asset('img/galeria/'.$archivo.'.jpg') }}" alt="{{ $texto }}" loading="lazy" decoding="async">
                </figure>
            @endforeach
        </div>
    </section>


    {{-- ======================================= --}}
    {{-- GUÍAS TURÍSTICAS --}}
    {{-- Mismas tarjetas que el equipo, con el cuadro de foto vacío. Cambia nombres, resúmenes y fotos cuando estén los datos reales. --}}
    {{-- ======================================= --}}

    <section class="ini2-equipo ini2-guias" id="guias" aria-labelledby="ini2-guias-titulo">
        <div class="contenedor">

            <div class="ini2-cabecera" data-ini2>
                <h2 id="ini2-guias-titulo">Guías turísticas</h2>
            </div>

            <div class="ini2-equipo__grid">
                @foreach ([
                    ['Guía 1', 'Resumen del guía: experiencia, recorridos que acompaña y lo que más disfruta compartir del territorio.'],
                    ['Guía 2', 'Resumen del guía: experiencia, recorridos que acompaña y lo que más disfruta compartir del territorio.'],
                    ['Guía 3', 'Resumen del guía: experiencia, recorridos que acompaña y lo que más disfruta compartir del territorio.'],
                    ['Guía 4', 'Resumen del guía: experiencia, recorridos que acompaña y lo que más disfruta compartir del territorio.'],
                ] as [$nombre, $resumen])
                    <article class="ini2-miembro ini2-elevable" data-ini2 style="--retardo: {{ $loop->index * 110 }}ms">
                        {{-- Espacio para la foto del guía; agrega aquí un <img> cuando la tengas. --}}
                        <div class="ini2-miembro__foto ini2-miembro__foto--vacia" aria-hidden="true">
                            {{-- Símbolo de imagen: indica que aquí va la foto. Quítalo al poner el <img>. --}}
                            <svg class="ini2-miembro__icono-foto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="16" rx="2.5"/>
                                <circle cx="8.5" cy="9.5" r="1.8"/>
                                <path d="M21 16l-5.2-5.2a1 1 0 0 0-1.4 0L5 20"/>
                            </svg>
                        </div>
                        <h3>{{ $nombre }}</h3>
                        <p>{{ $resumen }}</p>
                    </article>
                @endforeach
            </div>

        </div>
    </section>


    {{-- ======================================= --}}
    {{-- EQUIPO Y COLABORADORES --}}
    {{-- ======================================= --}}

    <section class="ini2-equipo" id="equipo">
        <div class="contenedor">

            <div class="ini2-cabecera" data-ini2>
                <h2>Equipo</h2>
            </div>

            <div class="ini2-equipo__grid">
                @foreach ([
                    ['Guías naturalistas', 'Acompañan los recorridos, enseñan a observar la fauna y la flora, y cuidan al grupo en el sendero.', 'guia'],
                    ['Anfitriones', 'Reciben a cada visitante y procuran que su experiencia sea cómoda y memorable.', 'anfitrion'],
                    ['Coordinación', 'Organizan horarios, cupos y detalles para que cada reservación salga como se planeó.', 'coordinacion'],
                    ['Consultoría regenerativa', 'Acompañan a organizaciones y comunidades que quieren crecer con respeto al territorio.', 'consultoria'],
                ] as [$rol, $descripcion, $foto])
                    <article class="ini2-miembro ini2-elevable" data-ini2 style="--retardo: {{ $loop->index * 110 }}ms">
                        {{-- Foto de referencia; se puede sustituir por la foto real del integrante. --}}
                        <div class="ini2-miembro__foto">
                            <img src="{{ asset('img/equipo/'.$foto.'.jpg') }}" alt="{{ $rol }} de Mil Cumbres" loading="lazy" decoding="async">
                        </div>
                        <h3>{{ $rol }}</h3>
                        <p>{{ $descripcion }}</p>
                    </article>
                @endforeach
            </div>

            <div class="ini2-colaboradores" data-ini2>
                <h3>Colaboradores</h3>
                <p>Trabajamos con guías locales, comunidades de la región y especialistas en conservación para que cada experiencia tenga respaldo.</p>
                {{-- Logos de colaboradores: reemplaza cada bloque por <img src="..." alt="Nombre del colaborador"> --}}
                <div class="ini2-logos">
                    @foreach ([
                    ['FOCEN', 'focen.png'], ['National Geographic', 'nationalgeographic.png'], ['Holcim', 'holcim.png'], ['Takeda', 'takeda.png'],
                    ['FMCN', 'fmcn.png'], ['Common', 'common.png'], ['VTEX', 'vtex.png'], ['Traxion', 'traxion.png'],
                    ['Mayer Brown', 'mayer-brown.png'], ['Colaborador', 'emblema.png'], ['Heartland', 'heartland.png'], ['Paynet by Openpay', 'paynet.png'],
                    ['Global Water Watch México', 'global-water-watch.png'], ['Fundación Azteca', 'fundacion-azteca.png'], ['Lincoln', 'lincoln.png'], ['Essity', 'essity.png'],
                ] as $colaborador)
                    <div class="ini2-logo">
                        <img src="{{ asset('img/colaboradores/'.$colaborador[1]) }}" alt="{{ $colaborador[0] }}" loading="lazy" decoding="async">
                    </div>
                @endforeach
                </div>
            </div>

        </div>
    </section>


    {{-- ======================================= --}}
    {{-- RESEÑAS (solo opiniones reales y publicadas) --}}
    {{-- ======================================= --}}

    <section class="ini2-resenas" aria-labelledby="ini2-resenas-titulo">
        <div class="contenedor">

            <div class="ini2-cabecera" data-ini2>
                <span class="inicio-kicker">
                    <span class="inicio-kicker__linea"></span>
                    Opiniones
                </span>
                <h2 id="ini2-resenas-titulo">Lo que dicen <em>quienes nos visitan</em></h2>
            </div>

            @if ($resenas->isNotEmpty())
                <div class="ini2-resenas__grid">
                    @foreach ($resenas as $resena)
                        <blockquote class="ini2-resena ini2-elevable" data-ini2 style="--retardo: {{ $loop->index * 100 }}ms">
                            <div class="ini2-resena__estrellas" aria-label="{{ $resena->estrellas }} de 5 estrellas">
                                {{ str_repeat('★', (int) $resena->estrellas) }}{{ str_repeat('☆', max(0, 5 - (int) $resena->estrellas)) }}
                            </div>
                            <p>“{{ \Illuminate\Support\Str::limit($resena->comentario, 220) }}”</p>
                            <footer>
                                @if ($esEjemplo)
                                    <em class="ini2-resena__ejemplo">Ejemplo</em>
                                @endif
                                <strong>
                                    {{ $resena->persona?->nombre }}
                                    {{ $resena->persona?->ap ? mb_substr($resena->persona->ap, 0, 1).'.' : '' }}
                                </strong>
                                @if ($resena->asignacion?->experiencia)
                                    <span>{{ $resena->asignacion->experiencia->nombre }}</span>
                                @endif
                            </footer>
                        </blockquote>
                    @endforeach
                </div>
            @else
                <p class="ini2-resenas__vacio" data-ini2>
                    Cuando nuestros visitantes compartan su opinión, aparecerá aquí.
                </p>
            @endif

        </div>
    </section>


    {{-- ======================================= --}}
    {{-- CÓMO RESERVAR --}}
    {{-- ======================================= --}}

    <section class="inicio-pasos">

        <div class="contenedor">

            <div class="inicio-pasos__encabezado" data-reveal>

                <span class="inicio-kicker">
                    <span class="inicio-kicker__linea"></span>
                    PLANEA TU EXPERIENCIA
                </span>

                <h2>
                    Tu próxima aventura
                    <em>está más cerca.</em>
                </h2>

                <p>
                    Reservar una experiencia en Mil Cumbres
                    es sencillo. Solo sigue estos pasos.
                </p>

            </div>

            <div class="inicio-pasos__grid">

                <div class="inicio-paso" data-reveal style="--retardo: 0ms">

                    <figure class="inicio-paso__foto">
                        <img src="{{ config('milcumbres.imagenes.pasos.explora') }}"
                             alt="Paisaje de Valle de Bravo para elegir experiencia" loading="lazy">
                    </figure>


                    <div class="inicio-paso__icono"
                         aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M15.5 8.5l-2 5-5 2 2-5z"/></svg></div>

                    <h3>Explora y elige</h3>

                    <p>
                        Conoce nuestras experiencias
                        y selecciona las actividades
                        que más te interesen.
                    </p>

                </div>

                <div class="inicio-paso" data-reveal style="--retardo: 140ms">

                    <figure class="inicio-paso__foto">
                        <img src="{{ config('milcumbres.imagenes.pasos.solicita') }}"
                             alt="Paisaje de Valle de Bravo para solicitar fecha" loading="lazy">
                    </figure>


                    <div class="inicio-paso__icono"
                         aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/><path d="M8 14h2M12 14h2M8 17h2"/></svg></div>

                    <h3>Solicita tu fecha</h3>

                    <p>
                        Indica cuándo deseas visitarnos
                        y cuántas personas participarán.
                        Confirmaremos la disponibilidad.
                    </p>

                </div>

                <div class="inicio-paso" data-reveal style="--retardo: 280ms">

                    <figure class="inicio-paso__foto">
                        <img src="{{ config('milcumbres.imagenes.pasos.confirma') }}"
                             alt="Personas disfrutando una actividad al aire libre" loading="lazy">
                    </figure>


                    <div class="inicio-paso__icono"
                         aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg></div>

                    <h3>Confirma y disfruta</h3>

                    <p>
                        Una vez confirmada tu solicitud,
                        registra tu pago y prepárate
                        para vivir la experiencia.
                    </p>

                </div>

            </div>


        </div>

    </section>



</div>

@endsection

@push('estilos')
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('inicio-carrusel-milcumbres.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('inicio-servicios-milcumbres.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('inicio-menu-hero.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('inicio-secciones-milcumbres.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/inicio-carrusel.js') }}" defer></script>
    <script src="{{ asset('js/inicio-secciones.js') }}" defer></script>
@endpush
