{{-- C03 · Detalle de una experiencia
     Tablas: experiencias, zonas, catalogo_experiencias, asigna_disponibilidad, disponibilidad --}}
@extends('layouts.app')

@section('titulo', $experiencia->nombre)
@section('descripcion', \Illuminate\Support\Str::limit($experiencia->resumen, 150))

@section('contenido')
<div class="contenedor detalle">
    <nav class="migas" aria-label="Migas de pan">
        <a href="{{ route('experiencias.index') }}">Experiencias</a> / {{ $experiencia->nombre }}
    </nav>

    <div class="detalle__portada" style="background-image: url('{{ $experiencia->imagen_url }}')"
         role="img" aria-label="{{ $experiencia->nombre }}"></div>

    <section class="detalle__tarjeta">
        <span class="tipo">{{ $experiencia->catalogo?->tipo }}</span>
        <h1 class="titulo-display">{{ $experiencia->nombre }}</h1>
        <p class="detalle__resumen">{{ $experiencia->resumen }}</p>

        <dl class="datos">
            <div>
                <dt>Zona</dt>
                <dd>{{ $experiencia->zona?->nombre }}</dd>
            </div>
            <div>
                <dt>Temporada</dt>
                <dd>{{ $experiencia->temporada ?: 'Todo el año' }}</dd>
            </div>
            <div>
                <dt>Duración</dt>
                <dd>{{ $experiencia->duracion_texto }}</dd>
            </div>
            <div>
                <dt>Precio por persona</dt>
                <dd>{{ $experiencia->precio_texto }}</dd>
            </div>
        </dl>

        {{-- Lleva los datos a C06 · Solicitar experiencia --}}
        <form class="detalle__form" method="GET" action="{{ route('solicitudes.create') }}">
            <input type="hidden" name="experiencia[]" value="{{ $experiencia->id_experiencia }}">

            <div class="campo">
                <label for="fecha">Fecha deseada</label>
                <input class="control" type="date" id="fecha" name="fecha"
                       min="{{ now()->addDay()->toDateString() }}" @guest disabled @endguest>
            </div>

            <div class="campo">
                <label for="personas">Personas @if ($cupoMaximo)(máx. {{ $cupoMaximo }})@endif</label>
                <input class="control" type="number" id="personas" name="personas" value="1" min="1"
                       @if ($cupoMaximo) max="{{ $cupoMaximo }}" @endif @guest disabled @endguest>
            </div>

            @auth
                <button class="btn btn--primario" type="submit">Solicitar experiencia</button>
            @else
                <button class="btn" type="button" disabled>Solicitar experiencia</button>
            @endauth
        </form>

        @guest
            <p class="detalle__nota">
                Necesitas una cuenta para enviar la solicitud.
                <a href="{{ route('login') }}">Ingresar</a> o <a href="{{ route('registro') }}">crear cuenta</a>.
            </p>
        @endguest
    </section>

    <div class="detalle__info">
        <section>
            <h2>Requisitos</h2>
            <p class="texto-suave">{{ $experiencia->requisitos ?: 'Sin requisitos especiales.' }}</p>
        </section>

        <section>
            <h2>Horarios disponibles</h2>
            @if ($experiencia->disponibilidades->isEmpty())
                <p class="texto-suave">Pronto publicaremos horarios para esta experiencia.</p>
            @else
                <div class="tabla-envoltura">
                    <table class="tabla">
                        <thead>
                        <tr>
                            <th scope="col">Día</th>
                            <th scope="col">Horario</th>
                            <th scope="col" class="num">Cupo por fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($experiencia->disponibilidades as $d)
                            <tr>
                                <td>{{ $d->dia_texto }}</td>
                                <td>{{ $d->horario }}</td>
                                <td class="num">{{ $d->pivot->cupo }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    <section class="detalle__antes">
        <h2>Antes de tu visita</h2>
        <p class="texto-suave">
            La fecha que envíes es una preferencia. Revisaremos la disponibilidad y te confirmaremos
            la reservación antes de pedirte el pago.
        </p>
    </section>
</div>
@endsection
