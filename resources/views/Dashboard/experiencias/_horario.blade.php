{{-- Fila de horario del editor A07. $i = índice, $h = ['dia','inicio','fin','cupo'] --}}
<div class="horario-fila">
    <div class="campo">
        <label>Día</label>
        <select class="control" name="horarios[{{ $i }}][dia]" required>
            @foreach (\App\Models\Disponibilidad::DIAS as $dia)
                <option value="{{ $dia }}" @selected(($h['dia'] ?? 'Sabado') === $dia)>
                    {{ str_replace(['Miercoles', 'Sabado'], ['Miércoles', 'Sábado'], $dia) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="campo">
        <label>Inicio</label>
        <input class="control" type="time" name="horarios[{{ $i }}][inicio]" value="{{ $h['inicio'] ?? '07:00' }}" required>
    </div>
    <div class="campo">
        <label>Fin</label>
        <input class="control" type="time" name="horarios[{{ $i }}][fin]" value="{{ $h['fin'] ?? '10:00' }}" required>
    </div>
    <div class="campo">
        <label>Cupo</label>
        <input class="control" type="number" min="1" name="horarios[{{ $i }}][cupo]" value="{{ $h['cupo'] ?? 12 }}" required>
    </div>
    <button class="btn btn--peligro btn--chico" type="button" data-quitar aria-label="Quitar horario">✕</button>
</div>
