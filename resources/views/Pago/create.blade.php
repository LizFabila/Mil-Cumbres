{{-- C09 · Registrar pago
     Tablas: pagos, solicitudes --}}
@extends('layouts.app')

@section('titulo', 'Registrar pago')

@section('contenido')
<div class="contenedor contenedor--angosto seccion">
    <nav class="migas mt-0" aria-label="Migas de pan">
        <a href="{{ route('solicitudes.index') }}">Mis solicitudes</a> / Registrar pago
    </nav>

    <h1 class="titulo-seccion">Registrar pago</h1>
    <p class="texto-suave">
        Folio <span class="mono">{{ $solicitud->folio }}</span> ·
        {{ $solicitud->experiencias->pluck('nombre')->implode(' + ') }} ·
        {{ $solicitud->fecha_deseada->translatedFormat('j M Y') }}
    </p>

    <x-avisos />

    @if (! $solicitud->puedePagar())
        <div class="aviso aviso--info">
            @if ($solicitud->estado !== 'confirmada')
                Podrás registrar tu pago cuando confirmemos la solicitud (estado actual: {{ $solicitud->estado }}).
            @else
                Ya recibimos un comprobante para esta solicitud y está en revisión o aprobado.
            @endif
        </div>
    @else
        <div class="grid-2 mt-3">
            <section class="pila">
                <h2 class="subtitulo">1. Haz tu transferencia</h2>
                <div class="datos-banco">
                    <dl class="datos">
                        <div><dt>Banco</dt><dd>{{ config('milcumbres.pago.banco') }}</dd></div>
                        <div><dt>Monto</dt><dd>${{ number_format($solicitud->total, 2) }}</dd></div>
                        <div class="completo"><dt>CLABE</dt><dd>{{ config('milcumbres.pago.clabe') }}</dd></div>
                        <div class="completo"><dt>Titular</dt><dd>{{ config('milcumbres.pago.titular') }}</dd></div>
                        <div class="completo"><dt>Concepto</dt><dd>{{ $solicitud->folio }}</dd></div>
                    </dl>
                </div>
                <p class="ayuda">Usa el folio como concepto para identificar tu pago más rápido.</p>
            </section>

            <form method="POST" action="{{ route('pagos.store', $solicitud) }}" enctype="multipart/form-data" class="pila" novalidate>
                @csrf
                <h2 class="subtitulo">2. Sube tu comprobante</h2>

                <div class="campo">
                    <label for="referencia">Referencia bancaria</label>
                    <input @class(['control', 'es-invalido' => $errors->has('referencia')]) id="referencia" name="referencia"
                           value="{{ old('referencia') }}" maxlength="50" required>
                    @error('referencia') <span class="error">{{ $message }}</span> @enderror
                </div>

                <label class="archivo" for="comprobante">
                    <strong>Comprobante</strong><br>
                    <span class="ayuda">JPG, PNG o PDF · máx. 4 MB</span>
                    <input type="file" id="comprobante" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required>
                </label>
                @error('comprobante') <span class="error">{{ $message }}</span> @enderror

                <button class="btn btn--primario btn--bloque" type="submit">Enviar comprobante</button>
            </form>
        </div>
    @endif

    @if ($solicitud->pagos->isNotEmpty())
        <h2 class="subtitulo mt-4">Pagos registrados</h2>
        <div class="tabla-envoltura">
            <table class="tabla">
                <thead><tr><th>Fecha</th><th>Referencia</th><th>Estado</th></tr></thead>
                <tbody>
                @foreach ($solicitud->pagos as $pago)
                    <tr>
                        <td>{{ $pago->fecha->format('d/m/Y H:i') }}</td>
                        <td class="mono">{{ $pago->referencia }}</td>
                        <td><x-estado :valor="$pago->estado" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
