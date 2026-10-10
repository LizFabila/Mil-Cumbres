@props(['experiencia'])
<a href="{{ route('experiencias.show', [$experiencia, $experiencia->slug]) }}" class="exp-tarjeta">
    <img class="exp-tarjeta__img" src="{{ $experiencia->imagen_url }}" alt="{{ $experiencia->nombre }}" loading="lazy">
    <div class="exp-tarjeta__cuerpo">
        <span class="tipo">{{ $experiencia->catalogo?->tipo }}</span>
        <h3>{{ $experiencia->nombre }}</h3>
        <p class="texto-suave texto-chico mb-0">{{ \Illuminate\Support\Str::limit($experiencia->resumen, 110) }}</p>
        <div class="exp-tarjeta__meta">
            <span>{{ $experiencia->zona?->nombre }} · {{ $experiencia->duracion_texto }}</span>
            <strong>${{ number_format($experiencia->precio, 0) }}</strong>
        </div>
    </div>
</a>
