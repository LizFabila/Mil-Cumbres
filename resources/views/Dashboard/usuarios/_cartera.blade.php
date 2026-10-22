{{-- Cartera de clientes: experiencias y consultorías, cada una dividida en
     frecuentes y esporádicos. Un cliente puede aparecer en ambas secciones. --}}
@php
    $secciones = [
        'experiencias' => ['titulo' => 'Experiencias', 'datos' => $cartera['experiencias']],
        'consultorias' => ['titulo' => 'Consultorías', 'datos' => $cartera['consultorias']],
    ];
@endphp

@foreach ($secciones as $clave => $seccion)
    <section class="cartera-seccion" aria-labelledby="cartera-{{ $clave }}">
        <header class="cartera-seccion__cabecera">
            <h2 id="cartera-{{ $clave }}">{{ $seccion['titulo'] }}</h2>
            <span class="texto-suave">
                {{ $seccion['datos']['frecuentes']->count() + $seccion['datos']['esporadicos']->count() }} clientes
            </span>
        </header>

        <div class="cartera-grupos">
            @include('Dashboard.usuarios._cartera-tabla', [
                'titulo' => 'Frecuentes',
                'filas' => $seccion['datos']['frecuentes'],
                'vacio' => 'Ningún cliente es frecuente todavía.',
            ])
            @include('Dashboard.usuarios._cartera-tabla', [
                'titulo' => 'Esporádicos',
                'filas' => $seccion['datos']['esporadicos'],
                'vacio' => 'Sin clientes en esta categoría.',
            ])
        </div>
    </section>
@endforeach
