{{-- C06 · Solicitar experiencia
     Tablas: solicitudes, asigna_experiencia, experiencias, asigna_disponibilidad, disponibilidad --}}
@extends('layouts.app')

@section('titulo', 'Solicitar experiencia')

@section('contenido')
<div class="contenedor contenedor--angosto seccion">
    <nav class="migas mt-0" aria-label="Migas de pan">
        <a href="{{ route('experiencias.index') }}">Experiencias</a> / Solicitar
    </nav>

    <h1 class="titulo-seccion">Solicitar experiencia</h1>
    <p class="texto-suave">Elige una o varias experiencias para el mismo día. Te confirmaremos disponibilidad antes de pedirte el pago.</p>

    <x-avisos />

    <form method="POST" action="{{ route('solicitudes.store') }}" class="tarjeta pila mt-3" id="form-solicitud" novalidate>
        @csrf

        <fieldset class="pila" aria-describedby="ayuda-exp">
            <legend class="subtitulo">1. Experiencias</legend>
            <span class="ayuda" id="ayuda-exp">El total se calcula como precio por persona × número de personas.</span>

            @php $marcadas = old('experiencias', $seleccionadas); @endphp
            @foreach ($experiencias as $exp)
                <label class="opcion-exp">
                    <input type="checkbox" name="experiencias[]" value="{{ $exp->id_experiencia }}"
                           data-precio="{{ $exp->precio }}"
                           data-dias="{{ $exp->disponibilidades->pluck('dias_semana')->unique()->implode(',') }}"
                           @checked(in_array($exp->id_experiencia, array_map('intval', (array) $marcadas)))>
                    <img src="{{ $exp->imagen_url }}" alt="" loading="lazy">
                    <span>
                        <strong>{{ $exp->nombre }}</strong>
                        <small>
                            {{ $exp->zona?->nombre }} · {{ $exp->duracion_texto }}
                            @if ($exp->disponibilidades->isNotEmpty())
                                · {{ $exp->disponibilidades->map->dia_texto->unique()->implode(', ') }}
                            @endif
                        </small>
                    </span>
                    <strong>{{ $exp->precio_texto }}</strong>
                </label>
            @endforeach
            @error('experiencias') <span class="error">{{ $message }}</span> @enderror
        </fieldset>

        <fieldset class="form-grid">
            <legend class="subtitulo completo">2. Fecha y personas</legend>

            <div class="campo">
                <label for="fecha_deseada">Fecha deseada</label>
                <input @class(['control', 'es-invalido' => $errors->has('fecha_deseada')]) type="date" id="fecha_deseada"
                       name="fecha_deseada" value="{{ old('fecha_deseada', $fecha) }}" min="{{ now()->addDay()->toDateString() }}" required>
                <span class="ayuda" id="aviso-dia"></span>
                @error('fecha_deseada') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="cant_persona">Número de personas</label>
                <input @class(['control', 'es-invalido' => $errors->has('cant_persona')]) type="number" id="cant_persona"
                       name="cant_persona" value="{{ old('cant_persona', $personas) }}" min="1" required>
                @error('cant_persona') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="campo completo">
                <label for="nota">Nota <span class="ayuda">(opcional)</span></label>
                <textarea class="control" id="nota" name="nota" maxlength="1000"
                          placeholder="Alergias, movilidad reducida, si viajan con niños…">{{ old('nota') }}</textarea>
            </div>
        </fieldset>

        <div class="resumen-total">
            <span class="texto-suave">Total estimado</span>
            <strong id="total">$0.00 MXN</strong>
        </div>

        <button class="btn btn--primario btn--bloque" type="submit">Enviar solicitud</button>
        <p class="ayuda centrado mb-0">Aún no se cobra nada. Recibirás un folio para dar seguimiento.</p>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/solicitud.js') }}" defer></script>
@endpush
