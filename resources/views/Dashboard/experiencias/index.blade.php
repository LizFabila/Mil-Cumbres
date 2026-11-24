{{-- A06 · Experiencias
     Tablas: experiencias, zonas, catalogo_experiencias, asigna_disponibilidad --}}
@extends('layouts.admin')

@section('titulo', 'Experiencias')

@section('contenido')
<div class="admin-cabecera">
    <div>
        <h1>Experiencias</h1>
        <p>Catálogo publicado en el sitio.</p>
    </div>
    <a class="btn btn--primario" href="{{ route('admin.experiencias.create') }}">Nueva experiencia</a>
</div>

<div class="tabla-envoltura">
    <table class="tabla">
        <thead>
        <tr><th></th><th>Nombre</th><th>Enlace</th><th>Zona</th><th>Duración</th><th class="num">Precio</th><th class="num">Horarios</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($experiencias as $exp)
            <tr>
                <td><img class="miniatura" src="{{ $exp->imagen_url }}" alt=""></td>
                <td>
                    <strong>{{ $exp->nombre }}</strong>
                    <div class="texto-chico"><a href="{{ route('experiencias.show', [$exp, $exp->slug]) }}" target="_blank">Ver en sitio ↗</a></div>
                </td>
                <td>
                    <button type="button" class="btn-enlace" data-url="{{ route('experiencias.show', [$exp, $exp->slug]) }}"
                            title="Copiar enlace a la página de esta experiencia" aria-label="Copiar enlace de {{ $exp->nombre }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10 7H7a5 5 0 0 0 0 10h3"/>
                            <path d="M14 7h3a5 5 0 0 1 0 10h-3"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        <span class="btn-enlace__msg" hidden>Copiado</span>
                    </button>
                </td>
                <td>{{ $exp->zonas_texto }}</td>
                <td>{{ $exp->duracion_texto }}</td>
                <td class="num" style="white-space: nowrap;">
                    @if ((float) $exp->precio > 0)
                        <div style="display: flex; justify-content: flex-end; gap: 6px; align-items: baseline; margin-bottom: 4px;">
                            <small class="texto-suave">Persona:</small>
                            <strong>${{ number_format($exp->precio, 2) }}</strong>
                        </div>
                    @endif
                    @foreach ($exp->grupos as $grupo)
                        <div style="display: flex; justify-content: flex-end; gap: 6px; align-items: baseline; margin-bottom: 4px;">
                            <small class="texto-suave">Grupo:</small>
                            <strong>${{ number_format($grupo->precio, 2) }}</strong>
                        </div>
                    @endforeach
                    @if ((float) $exp->precio <= 0 && $exp->grupos->isEmpty())
                        <span class="texto-suave">Por confirmar</span>
                    @endif
                </td>
                <td class="num">
                    @if ($exp->disponibilidades_count) {{ $exp->disponibilidades_count }}
                    @else <span class="error">0</span> @endif
                </td>
                <td>
                    <div class="acciones-tabla">
                        <a class="btn btn--secundario btn--chico" href="{{ route('admin.experiencias.edit', $exp) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.experiencias.destroy', $exp) }}"
                              onsubmit="return confirm('¿Dar de baja “{{ $exp->nombre }}”? Dejará de mostrarse en el sitio.')">
                            @csrf @method('DELETE')
                            <button class="btn btn--peligro btn--chico" type="submit">Baja</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="vacio">Aún no hay experiencias. <a href="{{ route('admin.experiencias.create') }}">Crea la primera</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $experiencias->links() }}
@endsection

@push('estilos')
<style>
    .btn-enlace {
        display: inline-flex; align-items: center; gap: 6px;
        background: none; border: 1px solid transparent; border-radius: 6px;
        padding: 6px; cursor: pointer; color: inherit;
    }
    .btn-enlace:hover, .btn-enlace:focus-visible { border-color: var(--borde, #ccc); outline: none; }
    .btn-enlace__msg { font-size: .8rem; color: #2e7d32; white-space: nowrap; }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        function copiarTexto(texto) {
            function respaldo() {
                // Para contextos sin HTTPS/localhost, donde navigator.clipboard no existe.
                var area = document.createElement('textarea');
                area.value = texto;
                area.setAttribute('readonly', '');
                area.style.position = 'fixed';
                area.style.top = '-1000px';
                document.body.appendChild(area);
                area.select();
                var ok = false;
                try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
                document.body.removeChild(area);
                return ok;
            }
            if (window.isSecureContext && navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(texto).then(function () { return true; }, function () { return respaldo(); });
            }
            return Promise.resolve(respaldo());
        }

        document.addEventListener('click', function (evento) {
            var boton = evento.target.closest('.btn-enlace');
            if (!boton) return;
            var aviso = boton.querySelector('.btn-enlace__msg');
            copiarTexto(boton.dataset.url).then(function (ok) {
                if (!ok) return;
                aviso.hidden = false;
                clearTimeout(boton._temporizador);
                boton._temporizador = setTimeout(function () { aviso.hidden = true; }, 1500);
            });
        });
    })();
</script>
@endpush
