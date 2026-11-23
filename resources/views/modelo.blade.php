
{{-- C13 · Nuestro modelo
     Página informativa sin consultas a tablas
--}}

@extends('layouts.app')

@section('titulo', 'Nuestro modelo')

@push('estilos')
<link rel="stylesheet"
      href="{{ \App\Support\Temas::hoja('modelo-milcumbres.css') }}">
@endpush

@push('estilos')
<link rel="stylesheet"
      href="{{ \App\Support\Temas::hoja('modelo-nuevo-milcumbres.css') }}">
@endpush

@section('contenido')

<div class="mc-modelo mn-modelo">

    <script>document.documentElement.classList.add('js');</script>

    {{-- ========================================= --}}
    {{-- 1 · NUESTRA ESENCIA (PDF, página 2) --}}
    {{-- ========================================= --}}
    <section class="mn-seccion mn-esencia" aria-labelledby="mn-esencia-titulo">
        <div class="contenedor mn-fila">
            <figure class="mn-foto mn-revela" data-mn-revela>
                <img src="{{ asset('img/modelo/modelo-esencia.jpg') }}" alt="Personas caminando y conversando en el bosque" loading="lazy" decoding="async">
            </figure>

            <div class="mn-texto mn-revela" data-mn-revela style="--retardo: 150ms">
                <span class="mn-etiqueta">Quiénes somos</span>
                <h2 id="mn-esencia-titulo">Nuestra <em>esencia</em></h2>
                <p class="mn-lead">
                    Somos un movimiento de turismo regenerativo, naturaleza y conservación que busca reconectar a las personas con ellas mismas, con los demás y con la naturaleza.
                </p>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- 2 · POTENCIANDO TU SER (PDF, página 3) --}}
    {{-- ========================================= --}}
    <section class="mn-seccion mn-potenciando" aria-labelledby="mn-potenciando-titulo">
        <div class="contenedor mn-fila mn-fila--invertida">
            <div class="mn-texto mn-revela" data-mn-revela>
                <span class="mn-etiqueta">Nuestro enfoque</span>
                <h2 id="mn-potenciando-titulo">Potenciando <em>tu ser</em></h2>
                <p>Todo espacio natural es perfecto, al igual que cada individuo. Diseñamos experiencias de impacto, programas educativos y vivencias en el bosque, que reconectan a las personas con su esencia, con los demás y con la naturaleza.</p>
                <p>Acompañamos a empresas, escuelas y viajeros a fortalecer su conexión, bienestar y liderazgo consciente.</p>
                <p>Nuestra metodología única de liderazgo regenerativo combina inmersiones sensoriales, charlas inspiradoras y herramientas transformadoras, para generar conexiones profundas y trascendentales promoviendo la conservación a través del turismo.</p>
            </div>

            <figure class="mn-foto mn-revela" data-mn-revela style="--retardo: 150ms">
                <img src="{{ asset('img/modelo/modelo-potenciando.jpg') }}" alt="Grupo caminando por un sendero del bosque" loading="lazy" decoding="async">
            </figure>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- 3 · EXPERIENCIAS QUE REGENERAN (PDF, página 4) --}}
    {{-- ========================================= --}}
    <section class="mn-seccion mn-regeneran" aria-labelledby="mn-regeneran-titulo"
             style="--foto: url('{{ asset('img/modelo/modelo-experiencias.jpg') }}')">
        <div class="contenedor">
            <header class="mn-encabezado mn-revela" data-mn-revela>
                <span class="mn-etiqueta">Lo que nos mueve</span>
                <h2 id="mn-regeneran-titulo">Experiencias que <em>regeneran</em></h2>
            </header>

            <div class="mn-tarjetas">
                <article class="mn-tarjeta mn-revela" data-mn-revela style="--retardo: 0ms">
                    <h3>Lo qué hacemos</h3>
                    <p>Creamos y facilitamos experiencias, programas y proyectos que entrelazan bienestar, aprendizaje y conservación.</p>
                    <p>A través de una red de aliados locales, organizaciones, empresas, comunidades, diseñamos iniciativas que promueven un impacto positivo en las personas, los territorios y los ecosistemas.</p>
                </article>

                <article class="mn-tarjeta mn-revela" data-mn-revela style="--retardo: 150ms">
                    <h3>Cómo lo hacemos</h3>
                    <p>Combinamos ciencia, pedagogía y recreación con un enfoque regenerativo.</p>
                    <p>Trabajamos desde la escucha del territorio y la colaboración genuina, co-creando soluciones con comunidades, instituciones y empresas.</p>
                    <p>Cada experiencia se diseña para activar la relación entre el ser, los otros y la naturaleza, generando bienestar compartido.</p>
                </article>

                <article class="mn-tarjeta mn-revela" data-mn-revela style="--retardo: 300ms">
                    <h3>Por qué lo hacemos</h3>
                    <p>Porque estamos convencidos de que la regeneración comienza en el interior. Cuando las personas se reconectan con la vida, transforman su forma de habitar el mundo.</p>
                    <p>Mil Cumbres existe para crear las condiciones fértiles donde la vida humana y no humana pueda prosperar.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ========================================= --}}
    {{-- 4 · CIERRE (PDF, página 22, sin datos de contacto) --}}
    {{-- ========================================= --}}
    <section class="mn-cierre" aria-labelledby="mn-cierre-titulo"
             style="--foto: url('{{ asset('img/modelo/modelo-contacto.jpg') }}')">
        <div class="contenedor mn-cierre__contenido mn-revela" data-mn-revela>
            <h2 id="mn-cierre-titulo">¿Quieres vivir una experiencia <em>con propósito?</em></h2>
            <a href="{{ route('experiencias.index') }}" class="inicio-btn inicio-btn--claro">
                Ver experiencias
                <span aria-hidden="true">↗</span>
            </a>
        </div>
    </section>

    <script>
        (function () {
            const elementos = document.querySelectorAll('[data-mn-revela]');
            if (!('IntersectionObserver' in window)) {
                elementos.forEach(function (el) { el.classList.add('is-visible'); });
                return;
            }
            const observador = new IntersectionObserver(function (entradas) {
                entradas.forEach(function (entrada) {
                    entrada.target.classList.toggle('is-visible', entrada.isIntersecting);
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
            elementos.forEach(function (el) { observador.observe(el); });
        })();
    </script>

</div>

@endsection
