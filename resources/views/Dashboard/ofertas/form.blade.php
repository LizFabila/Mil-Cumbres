{{-- Ofertas: alta y edición
     Tablas: ofertas, experiencias --}}
@extends('layouts.admin')

@php
    $editando = $oferta->exists;
    $modo = old('modo', $oferta->modo ?? 'porcentaje');
    $idSel = (int) old('id_experiencia', $oferta->id_experiencia);
    $expSel = $experiencias->firstWhere('id_experiencia', $idSel) ?? $experiencias->first();
@endphp

@section('titulo', $editando ? 'Editar oferta' : 'Nueva oferta')

@push('estilos')
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('ofertas-milcumbres.css') }}">
@endpush

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.ofertas.index') }}">← Ofertas</a></p>
        <h1>{{ $editando ? 'Editar oferta' : 'Nueva oferta' }}</h1>
    </div>
</div>

@if ($experiencias->isEmpty())
    <section class="panel">
        <p class="mb-0">Primero da de alta una experiencia. <a href="{{ route('admin.experiencias.create') }}">Nueva experiencia</a></p>
    </section>
@else
<form method="POST" novalidate data-of-form
      action="{{ $editando ? route('admin.ofertas.update', $oferta) : route('admin.ofertas.store') }}">
    @csrf
    @if ($editando) @method('PUT') @endif

    <div class="detalle-admin">
        <div>
            <section class="panel form-grid">
                <h2 class="completo mb-0">Datos de la oferta</h2>

                <div class="campo completo">
                    <label for="id_experiencia">Experiencia</label>
                    <select @class(['control', 'es-invalido' => $errors->has('id_experiencia')]) id="id_experiencia" name="id_experiencia" required>
                        @foreach ($experiencias as $e)
                            <option value="{{ $e->id_experiencia }}" @selected($e->id_experiencia === $expSel?->id_experiencia)
                                    data-precio="{{ (float) $e->precio }}"
                                    data-imagen="{{ $e->imagen_url }}"
                                    data-meta="{{ collect([$e->catalogo?->tipo, $e->zonas_texto])->filter()->implode(' · ') }}">
                                {{ $e->nombre }} — ${{ number_format((float) $e->precio, 2) }} por persona
                            </option>
                        @endforeach
                    </select>
                    @error('id_experiencia') <span class="error">{{ $message }}</span> @enderror
                </div>

                <fieldset class="campo completo">
                    <legend>Tipo de descuento</legend>
                    <div class="of-adm-opciones">
                        @foreach (\App\Models\Oferta::MODOS as $clave => $nombre)
                            <label @class(['of-adm-opcion', 'is-elegida' => $modo === $clave])>
                                <input type="radio" name="modo" value="{{ $clave }}" @checked($modo === $clave)>
                                <span>
                                    <strong>{{ $nombre }}</strong>
                                    <small>{{ $clave === 'porcentaje' ? 'Ej. 20 % menos' : 'Ej. $520 por persona' }}</small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('modo') <span class="error">{{ $message }}</span> @enderror
                </fieldset>

                <div class="campo">
                    <label for="valor" data-of-valor-label>{{ $modo === 'precio' ? 'Precio de oferta por persona (MXN)' : 'Descuento (%)' }}</label>
                    <input @class(['control', 'es-invalido' => $errors->has('valor')]) type="number" id="valor" name="valor"
                           min="0" step="0.01" value="{{ old('valor', $oferta->valor !== null ? (float) $oferta->valor : '') }}" required>
                    <span class="ayuda" data-of-resultado></span>
                    @error('valor') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="etiqueta">Texto corto (opcional)</label>
                    <input @class(['control', 'es-invalido' => $errors->has('etiqueta')]) id="etiqueta" name="etiqueta" maxlength="40"
                           placeholder="Ej. Temporada de mariposas" value="{{ old('etiqueta', $oferta->etiqueta) }}">
                    @error('etiqueta') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="inicia">Empieza</label>
                    <input @class(['control', 'es-invalido' => $errors->has('inicia')]) type="date" id="inicia" name="inicia"
                           value="{{ old('inicia', $oferta->inicia?->toDateString()) }}" required>
                    @error('inicia') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="termina">Termina</label>
                    <input @class(['control', 'es-invalido' => $errors->has('termina')]) type="date" id="termina" name="termina"
                           value="{{ old('termina', $oferta->termina?->toDateString()) }}" required>
                    <span class="ayuda">Ese día todavía cuenta. Al día siguiente la oferta se oculta sola.</span>
                    @error('termina') <span class="error">{{ $message }}</span> @enderror
                </div>
            </section>

            <section class="panel acciones-form">
                <div class="fila">
                    <button class="btn btn--primario" type="submit">{{ $editando ? 'Guardar cambios' : 'Publicar oferta' }}</button>
                    <a class="btn btn--texto" href="{{ route('admin.ofertas.index') }}">Cancelar</a>
                </div>
            </section>
        </div>

        <aside class="panel pila of-adm-vista">
            <h2 class="mb-0">Así la verá el cliente</h2>
            <p class="ayuda">Se actualiza mientras llenas el formulario.</p>
            <article class="mc-oferta mc-oferta--vertical">
                <div class="mc-oferta__foto">
                    <img src="{{ $expSel?->imagen_url }}" alt="" data-of-v-imagen>
                    <span class="mc-oferta__sello" data-of-v-sello>−0%</span>
                </div>
                <div class="mc-oferta__cuerpo">
                    <span class="mc-oferta__meta" data-of-v-meta></span>
                    <h3 data-of-v-nombre></h3>
                    <p class="mc-oferta__etiqueta" data-of-v-etiqueta></p>
                    <div class="mc-oferta__precios">
                        <del data-of-v-antes></del>
                        <strong><span data-of-v-precio></span> <small>MXN por persona</small></strong>
                    </div>
                    <p class="mc-oferta__vigencia" data-of-v-vigencia></p>
                    <span class="mc-oferta__cta">Reservar con descuento <span aria-hidden="true">↗</span></span>
                </div>
            </article>
        </aside>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script>
// Vista previa en vivo de la tarjeta de oferta.
(() => {
    const form = document.querySelector('[data-of-form]');
    if (!form) return;

    const $ = (s) => form.querySelector(s);
    const dinero = (n) => '$' + Math.round(n).toLocaleString('es-MX');
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

    function pintar() {
        const opcion = $('#id_experiencia').selectedOptions[0];
        const precio = Number(opcion.dataset.precio) || 0;
        const modo = form.querySelector('input[name="modo"]:checked')?.value || 'porcentaje';
        const valor = Number($('#valor').value) || 0;

        const final = modo === 'precio' ? valor : precio * (100 - valor) / 100;
        const pct = modo === 'precio' ? (precio > 0 ? Math.round(100 - final * 100 / precio) : 0) : Math.round(valor);

        form.querySelectorAll('.of-adm-opcion').forEach((l) => l.classList.toggle('is-elegida', l.querySelector('input').checked));
        $('[data-of-valor-label]').textContent = modo === 'precio' ? 'Precio de oferta por persona (MXN)' : 'Descuento (%)';
        $('[data-of-resultado]').innerHTML = valor > 0 && final > 0
            ? `Precio con oferta: <strong>${dinero(final)}</strong> (antes ${dinero(precio)})`
            : `Precio normal: ${dinero(precio)} por persona`;

        $('[data-of-v-imagen]').src = opcion.dataset.imagen;
        $('[data-of-v-meta]').textContent = opcion.dataset.meta;
        $('[data-of-v-nombre]').textContent = opcion.textContent.split(' — ')[0].trim();
        $('[data-of-v-sello]').textContent = '−' + Math.max(0, pct) + '%';
        $('[data-of-v-antes]').textContent = dinero(precio);
        $('[data-of-v-precio]').textContent = dinero(Math.max(0, final));

        const etiqueta = $('#etiqueta').value.trim();
        $('[data-of-v-etiqueta]').textContent = etiqueta;
        $('[data-of-v-etiqueta]').hidden = !etiqueta;

        const fin = $('#termina').value;
        if (fin) {
            const [a, m, d] = fin.split('-').map(Number);
            $('[data-of-v-vigencia]').textContent = `Válida hasta el ${d} de ${meses[m - 1]}`;
        }
    }

    form.addEventListener('input', pintar);
    form.addEventListener('change', pintar);
    pintar();
})();
</script>
@endpush
