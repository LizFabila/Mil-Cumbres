{{-- A10 · Identidad del sitio (nombre, lema y logotipo)
     Tabla: configuracion_sitio --}}
@extends('layouts.admin')

@section('titulo', 'Identidad del sitio')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-identidad-milcumbres.css') }}">
@endpush

@section('contenido')
<div class="mc-a10">

    {{-- 01 · ENCABEZADO: compacto, con el estado actual de la marca --}}
    <header class="mc-a10__cabecera">
        <div class="mc-a10__cabecera-texto">
            <span class="mc-a10__eyebrow">CONFIGURACIÓN · MARCA</span>
            <h1>Identidad del <em>sitio.</em></h1>
            <p>Lo que guardes aquí cambia en el panel y en la vista del cliente al mismo tiempo.</p>
        </div>

        <dl class="mc-a10__datos">
            <div>
                <dt>Nombre actual</dt>
                <dd>{{ $config->nombre }}</dd>
            </div>
            <div>
                <dt>Logotipo</dt>
                <dd>{{ $config->logo ? 'Personalizado' : 'Original' }}</dd>
            </div>
            <div>
                <dt>Última actualización</dt>
                <dd>{{ $config->updated_at?->format('d/m/Y · H:i') ?? 'Sin cambios' }}</dd>
            </div>
        </dl>
    </header>

    {{-- 02 · ASÍ SE VE AHORA (lo más importante: se actualiza mientras editas) --}}
    <section class="mc-a10__vista" aria-labelledby="mc-a10-vista-titulo">
        <div class="mc-a10__vista-encabezado">
            <div>
                <span class="mc-a10__seccion-eyebrow">VISTA PREVIA</span>
                <h2 id="mc-a10-vista-titulo">Así se verá tu marca</h2>
            </div>
            <span class="mc-a10__insignia" id="mc-a10-estado">Sin cambios</span>
        </div>

        <div class="mc-a10__vista-tarjetas">
            <figure class="mc-a10__muestra mc-a10__muestra--cliente">
                <div class="mc-a10__muestra-marca">
                    <img data-vista-logo src="{{ $config->logo_url }}" alt="">
                    <span>
                        <strong data-vista-nombre>{{ $config->nombre }}</strong>
                        <small data-vista-lema>{{ $config->lema }}</small>
                    </span>
                </div>
                <figcaption>Encabezado de la vista cliente</figcaption>
            </figure>

            <figure class="mc-a10__muestra mc-a10__muestra--panel">
                <div class="mc-a10__muestra-marca">
                    <img data-vista-logo src="{{ $config->logo_url }}" alt="">
                    <span>
                        <strong data-vista-nombre>{{ $config->nombre }}</strong>
                        <small data-vista-lema>{{ $config->lema }}</small>
                    </span>
                </div>
                <figcaption>Menú lateral del panel</figcaption>
            </figure>
        </div>
    </section>

    {{-- 03 · FORMULARIO --}}
    <form method="POST" enctype="multipart/form-data" novalidate
          action="{{ route('admin.marca.update') }}" class="mc-a10__form" id="mc-a10-form">
        @csrf
        @method('PUT')

        <section class="mc-a10__tarjeta">
            <span class="mc-a10__paso">01</span>
            <h2>Nombre y lema</h2>

            <div class="mc-a10__campo">
                <label for="nombre">Nombre del sitio</label>
                <input @class(['mc-a10__control', 'es-invalido' => $errors->has('nombre')]) id="nombre" name="nombre" maxlength="60"
                       value="{{ old('nombre', $config->nombre) }}" required data-origen="{{ $config->nombre }}">
                @error('nombre') <span class="mc-a10__error">{{ $message }}</span> @enderror
            </div>

            <div class="mc-a10__campo">
                <label for="lema">Lema <small>(aparece debajo del nombre)</small></label>
                <input @class(['mc-a10__control', 'es-invalido' => $errors->has('lema')]) id="lema" name="lema" maxlength="80"
                       value="{{ old('lema', $config->lema) }}" placeholder="Turismo Regenerativo" data-origen="{{ $config->lema }}">
                @error('lema') <span class="mc-a10__error">{{ $message }}</span> @enderror
            </div>
        </section>

        <section class="mc-a10__tarjeta">
            <span class="mc-a10__paso">02</span>
            <h2>Logotipo</h2>

            <input class="mc-a10__archivo" type="file" id="logo_archivo" name="logo_archivo"
                   accept="image/png,image/jpeg,image/webp">
            <label class="mc-a10__subir" for="logo_archivo">
                <span class="mc-a10__subir-icono" aria-hidden="true">↑</span>
                <strong>Elegir nuevo logotipo</strong>
                <small id="mc-a10-archivo">PNG, JPG o WEBP · máximo 2 MB</small>
            </label>
            @error('logo_archivo') <span class="mc-a10__error">{{ $message }}</span> @enderror

            {{-- Regresar al logotipo original (usa el formulario de abajo) --}}
            <div class="mc-a10__original">
                <img src="{{ asset('img/logo-mil-cumbres.png') }}" alt="Logotipo original de Mil Cumbres">
                <div>
                    <strong>Logotipo original</strong>
                    <small>{{ $config->logo ? 'Ahora usas un logotipo personalizado.' : 'Es el que se muestra ahora.' }}</small>
                </div>
                <button class="mc-a10__boton mc-a10__boton--restaurar" type="submit" form="mc-a10-restaurar"
                        @disabled(! $config->logo)>
                    ↺ {{ $config->logo ? 'Restaurar logo original' : 'En uso' }}
                </button>
            </div>
        </section>

        <div class="mc-a10__acciones">
            <a class="mc-a10__boton mc-a10__boton--texto" href="{{ route('admin.resumen') }}">Cancelar</a>
            <button class="mc-a10__boton mc-a10__boton--principal" type="submit">
                Guardar cambios <span aria-hidden="true">→</span>
            </button>
        </div>
    </form>

    {{-- Formulario aparte para restaurar el logotipo sin tocar el nombre ni el lema --}}
    <form method="POST" action="{{ route('admin.marca.logo.restaurar') }}" id="mc-a10-restaurar"
          onsubmit="return confirm('¿Regresar al logotipo original de Mil Cumbres? El logotipo personalizado se borrará.');">
        @csrf
        @method('DELETE')
    </form>

</div>
@endsection

@push('scripts')
<script>
    // Vista previa en vivo: nombre, lema y logotipo cambian antes de guardar.
    (() => {
        const form = document.getElementById('mc-a10-form');
        if (!form) return;

        const nombre = document.getElementById('nombre');
        const lema = document.getElementById('lema');
        const archivo = document.getElementById('logo_archivo');
        const quitar = document.getElementById('quitar_logo');
        const estado = document.getElementById('mc-a10-estado');
        const textoArchivo = document.getElementById('mc-a10-archivo');
        const logoActual = @json($config->logo_url);
        const logoOriginal = @json(asset('img/logo-mil-cumbres.png'));

        const pintar = (selector, valor) =>
            document.querySelectorAll(selector).forEach((el) => { el.textContent = valor; });

        const ponerLogo = (src) =>
            document.querySelectorAll('[data-vista-logo]').forEach((img) => { img.src = src; });

        const revisar = () => {
            const cambio = nombre.value !== nombre.dataset.origen
                || lema.value !== (lema.dataset.origen ?? '')
                || archivo.files.length > 0
                || (quitar && quitar.checked);
            estado.textContent = cambio ? 'Cambios sin guardar' : 'Sin cambios';
            estado.classList.toggle('is-pendiente', cambio);
        };

        nombre.addEventListener('input', () => { pintar('[data-vista-nombre]', nombre.value || 'Sin nombre'); revisar(); });
        lema.addEventListener('input', () => { pintar('[data-vista-lema]', lema.value); revisar(); });

        archivo.addEventListener('change', () => {
            const elegido = archivo.files[0];
            if (elegido) {
                ponerLogo(URL.createObjectURL(elegido));
                textoArchivo.textContent = elegido.name;
                if (quitar) quitar.checked = false;
            }
            revisar();
        });

        quitar?.addEventListener('change', () => {
            ponerLogo(quitar.checked ? logoOriginal : logoActual);
            revisar();
        });
    })();
</script>
@endpush
