{{-- Ofertas: alta y edición
     Tablas: ofertas, experiencias, experiencia_grupos --}}
@extends('layouts.admin')

@php
    $editando = $oferta->exists;
    $aplica = old('aplica_a', $oferta->aplica_a ?? 'persona');
    $modo = old('modo', $oferta->modo ?? 'porcentaje');
    $idSel = (int) old('id_experiencia', $oferta->id_experiencia);
    $grupoSel = (int) old('grupo_personas', $oferta->grupo_personas);
    $expSel = $experiencias->firstWhere('id_experiencia', $idSel) ?? $experiencias->first();
@endphp

@section('titulo', $editando ? 'Editar oferta' : 'Nueva oferta')

@push('estilos')
    <link rel="stylesheet" href="{{ \App\Support\Temas::hoja('ofertas-milcumbres.css') }}">
    <style>[hidden] { display: none !important; }</style>
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
                    <legend>¿A quién aplica?</legend>
                    <div class="of-adm-opciones">
                        @foreach (\App\Models\Oferta::APLICA as $clave => $nombre)
                            <label @class(['of-adm-opcion', 'is-elegida' => $aplica === $clave])>
                                <input type="radio" name="aplica_a" value="{{ $clave }}" @checked($aplica === $clave)>
                                <span>
                                    <strong>{{ $clave === 'persona' ? 'Persona individual' : 'Un grupo' }}</strong>
                                    <small>{{ $clave === 'persona' ? 'Baja el precio por persona.' : 'Baja el precio total de un grupo.' }}</small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('aplica_a') <span class="error">{{ $message }}</span> @enderror
                </fieldset>

                <div class="campo completo" data-of-grupo-campo @if ($aplica !== 'grupo') hidden @endif>
                    <label for="grupo_personas">Grupo</label>
                    <select @class(['control', 'es-invalido' => $errors->has('grupo_personas')]) id="grupo_personas" name="grupo_personas">
                        @foreach ($experiencias as $e)
                            @foreach ($e->grupos as $g)
                                <option value="{{ $g->personas }}" data-exp="{{ $e->id_experiencia }}" data-precio="{{ (float) $g->precio }}"
                                        @selected($grupoSel === (int) $g->personas && $idSel === (int) $e->id_experiencia)>
                                    Grupo {{ $g->rangoTexto() }} — ${{ number_format((float) $g->precio, 2) }} total
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <span class="ayuda" data-of-grupo-aviso @if ($experiencias->flatMap->grupos->isNotEmpty()) hidden @endif>
                        Esta experiencia no tiene grupos. <a href="{{ $expSel ? route('admin.experiencias.edit', $expSel) : route('admin.experiencias.index') }}">Agrega grupos en la experiencia</a>.
                    </span>
                    @error('grupo_personas') <span class="error">{{ $message }}</span> @enderror
                </div>

                <fieldset class="campo completo">
                    <legend>Tipo de descuento</legend>
                    <div class="of-adm-opciones">
                        @foreach (\App\Models\Oferta::MODOS as $clave => $nombre)
                            @php
                                $permitidoPara = collect(\App\Models\Oferta::MODOS_POR_APLICA)
                                    ->filter(fn ($modos) => in_array($clave, $modos, true))
                                    ->keys()
                                    ->implode(',');
                            @endphp
                            <label @class(['of-adm-opcion', 'is-elegida' => $modo === $clave]) data-of-modo="{{ $permitidoPara }}"
                                   @if (! str_contains($permitidoPara, $aplica)) hidden @endif>
                                <input type="radio" name="modo" value="{{ $clave }}" @checked($modo === $clave)>
                                <span>
                                    <strong>{{ $nombre }}</strong>
                                    <small>
                                        @switch($clave)
                                            @case('porcentaje') Ej. 20 % menos @break
                                            @case('precio') Ej. $520 por persona @break
                                            @case('cantidad') Ej. $300 menos al total del grupo @break
                                        @endswitch
                                    </small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('modo') <span class="error">{{ $message }}</span> @enderror
                </fieldset>

                <div class="campo">
                    <label for="valor" data-of-valor-label>Descuento (%)</label>
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
                        <strong><span data-of-v-precio></span> <small data-of-v-unidad>MXN por persona</small></strong>
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
// Vista previa en vivo de la oferta: persona individual o grupo.
(() => {
    const form = document.querySelector('[data-of-form]');
    if (!form) return;

    const $ = (s) => form.querySelector(s);
    const dinero = (n) => '$' + Math.round(n).toLocaleString('es-MX');
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const etiquetasValor = {
        'persona:porcentaje': 'Descuento (%)',
        'persona:precio': 'Precio de oferta por persona (MXN)',
        'grupo:porcentaje': 'Descuento (%) sobre el total del grupo',
        'grupo:cantidad': 'Monto a descontar del total del grupo (MXN)',
    };

    function aplicaActual() {
        return form.querySelector('input[name="aplica_a"]:checked')?.value || 'persona';
    }

    // Muestra solo los grupos de la experiencia elegida y los tipos de descuento que aplican.
    function filtrarOpciones() {
        const aplica = aplicaActual();
        const idExp = $('#id_experiencia').value;
        const select = $('#grupo_personas');
        let visibles = 0;

        select.querySelectorAll('option').forEach((o) => {
            const ok = o.dataset.exp === idExp;
            o.hidden = !ok;
            o.disabled = !ok;
            if (ok) visibles++;
        });

        if (select.selectedOptions[0]?.disabled) {
            const primera = [...select.options].find((o) => !o.disabled);
            if (primera) primera.selected = true;
        }

        $('[data-of-grupo-campo]').hidden = aplica !== 'grupo';
        $('[data-of-grupo-aviso]').hidden = aplica !== 'grupo' || visibles > 0;
        select.disabled = aplica !== 'grupo';

        form.querySelectorAll('[data-of-modo]').forEach((l) => {
            const permitido = l.dataset.ofModo.split(',').includes(aplica);
            l.hidden = !permitido;
            if (!permitido && l.querySelector('input').checked) {
                l.querySelector('input').checked = false;
                const primero = form.querySelector(`[data-of-modo*="${aplica}"] input`);
                if (primero) primero.checked = true;
            }
        });
    }

    function pintar() {
        filtrarOpciones();

        const aplica = aplicaActual();
        const modo = form.querySelector('input[name="modo"]:checked')?.value || 'porcentaje';
        const valor = Number($('#valor').value) || 0;
        const opcionExp = $('#id_experiencia').selectedOptions[0];
        const opcionGrupo = $('#grupo_personas').selectedOptions[0];

        const esGrupo = aplica === 'grupo' && opcionGrupo && !opcionGrupo.disabled;
        const base = esGrupo ? Number(opcionGrupo.dataset.precio) || 0 : Number(opcionExp.dataset.precio) || 0;

        let final = base;
        if (modo === 'porcentaje') final = base * (100 - valor) / 100;
        if (modo === 'precio') final = valor;
        if (modo === 'cantidad') final = base - valor;
        final = Math.max(0, final);

        const pct = base > 0 ? Math.round(100 - final * 100 / base) : 0;

        $('[data-of-valor-label]').textContent = etiquetasValor[aplica + ':' + modo] || 'Descuento';
        const unidad = esGrupo ? 'MXN el grupo' : 'MXN por persona';
        $('[data-of-v-unidad]').textContent = unidad;

        $('[data-of-resultado]').innerHTML = valor > 0 && final > 0 && final < base
            ? `Precio con oferta: <strong>${dinero(final)}</strong> (antes ${dinero(base)})`
            : `Precio normal: ${dinero(base)}${esGrupo ? ' el grupo' : ' por persona'}`;

        $('[data-of-v-imagen]').src = opcionExp.dataset.imagen;
        $('[data-of-v-meta]').textContent = opcionExp.dataset.meta;
        $('[data-of-v-nombre]').textContent = opcionExp.textContent.split(' — ')[0].trim();
        $('[data-of-v-sello]').textContent = '−' + Math.max(0, pct) + '%';
        $('[data-of-v-antes]').textContent = dinero(base);
        $('[data-of-v-precio]').textContent = dinero(final);

        const etiqueta = $('#etiqueta').value.trim();
        $('[data-of-v-etiqueta]').textContent = etiqueta;
        $('[data-of-v-etiqueta]').hidden = !etiqueta;

        const fin = $('#termina').value;
        if (fin) {
            const [a, m, d] = fin.split('-').map(Number);
            $('[data-of-v-vigencia]').textContent = `Válida hasta el ${d} de ${meses[m - 1]}`;
        }

        form.querySelectorAll('.of-adm-opcion').forEach((l) => l.classList.toggle('is-elegida', l.querySelector('input').checked));
    }

    form.addEventListener('input', pintar);
    form.addEventListener('change', pintar);
    pintar();
})();
</script>
@endpush
