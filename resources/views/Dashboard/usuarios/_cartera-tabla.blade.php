{{-- Una tabla de la cartera. Recibe: $titulo, $filas, $vacio. --}}
<article class="cartera-grupo">
    <h3>{{ $titulo }} <span class="cartera-grupo__total">{{ $filas->count() }}</span></h3>
    <div class="tabla-envoltura">
        <table class="tabla">
            <thead>
            <tr><th>Cliente</th><th>Correo</th><th class="num">Solicitudes</th><th>Última</th></tr>
            </thead>
            <tbody>
            @forelse ($filas as $f)
                <tr>
                    <td>
                        <strong>{{ $f['nombre'] }}</strong>
                        @if ($f['telefono'])<br><span class="texto-chico texto-suave">{{ $f['telefono'] }}</span>@endif
                    </td>
                    <td>{{ $f['email'] }}</td>
                    <td class="num">{{ $f['total'] }}</td>
                    <td>{{ $f['ultima']?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="vacio">{{ $vacio }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</article>
