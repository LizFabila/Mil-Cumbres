{{-- :href="false" muestra el logotipo sin enlace (no hace nada al dar clic) --}}
@props(['href' => null, 'etiqueta' => null])

@php
    $marca = \App\Models\ConfiguracionSitio::actual();
    $conEnlace = $href !== false;
@endphp

@if ($conEnlace)
<a href="{{ $href ?? route('inicio') }}" class="marca" aria-label="{{ $etiqueta ?? $marca->nombre . ', ir al inicio' }}">
@else
<div class="marca">
@endif
    @if ($marca->logo)
        <img src="{{ $marca->logo_url }}" alt="">
    @else
        <img src="{{ $marca->logo_url }}" alt="" width="1203" height="905">
    @endif
    <span>
        <span class="marca__nombre">{{ $marca->nombre }}</span>
        @if ($marca->lema)
            <span class="marca__lema">{{ $marca->lema }}</span>
        @endif
    </span>
@if ($conEnlace)
</a>
@else
</div>
@endif
