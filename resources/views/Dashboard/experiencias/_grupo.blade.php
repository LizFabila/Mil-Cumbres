{{-- Fila de grupo de precio del editor A07. $i = índice, $g = ['personas','precio'] --}}
<div class="grupo-fila">
    <div class="campo">
        <label>Personas</label>
        <input class="control" type="number" min="1" max="999" step="1" name="grupos[{{ $i }}][personas]"
               value="{{ $g['personas'] ?? '' }}" required>
    </div>
    <div class="campo">
        <label>Precio total del grupo (MXN)</label>
        <input class="control" type="number" min="0" step="0.01" name="grupos[{{ $i }}][precio]"
               value="{{ $g['precio'] ?? '' }}" required>
    </div>
    <button class="btn btn--peligro btn--chico" type="button" data-quitar aria-label="Quitar grupo">✕</button>
</div>
