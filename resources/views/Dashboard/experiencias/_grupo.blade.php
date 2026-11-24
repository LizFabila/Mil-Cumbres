{{-- Rango de grupo del editor A07. $i = índice, $g = ['desde','hasta','precio'] --}}
<div class="grupo-fila">
    <div class="campo">
        <label>Total de personas: desde</label>
        <input class="control" type="number" min="1" max="999" step="1" name="grupos[{{ $i }}][desde]"
               value="{{ $g['desde'] ?? '' }}" required>
    </div>
    <div class="campo">
        <label>hasta</label>
        <input class="control" type="number" min="1" max="999" step="1" name="grupos[{{ $i }}][hasta]"
               value="{{ $g['hasta'] ?? '' }}" required>
    </div>
    <div class="campo">
        <label>Precio total del grupo (MXN)</label>
        <input class="control" type="number" min="0" step="0.01" name="grupos[{{ $i }}][precio]"
               value="{{ $g['precio'] ?? '' }}" required>
    </div>
    <button class="btn btn--peligro btn--chico" type="button" data-quitar aria-label="Quitar grupo">✕</button>
</div>
