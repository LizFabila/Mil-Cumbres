{{-- Lista de todas las personas, con cambio de rol y baja. --}}
<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr><th>Nombre</th><th>Correo</th><th>Teléfono</th><th class="num">Solicitudes</th><th>Alta</th><th>Rol</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($personas as $p)
            @php $soyYo = $p->is(auth()->user()); @endphp
            <tr>
                <td><strong>{{ $p->nombre_completo }}</strong></td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->telefono ?: '—' }}</td>
                <td class="num">{{ $p->solicitudes_count }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                <td>
                    @if ($soyYo)
                        {{ $p->rol?->nombre }} <span class="texto-chico texto-suave">(tú)</span>
                    @else
                        <form method="POST" action="{{ route('admin.usuarios.update', $p) }}">
                            @csrf @method('PATCH')
                            <select class="select-inline" name="id_rol" onchange="this.form.submit()" aria-label="Rol de {{ $p->nombre }}">
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id_rol }}" @selected($p->id_rol === $rol->id_rol)>{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </td>
                <td>
                    @unless ($soyYo)
                        <form method="POST" action="{{ route('admin.usuarios.destroy', $p) }}"
                              onsubmit="return confirm('¿Dar de baja a {{ $p->nombre_completo }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn--peligro btn--chico" type="submit">Baja</button>
                        </form>
                    @endunless
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="vacio">Sin resultados.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $personas->links() }}
