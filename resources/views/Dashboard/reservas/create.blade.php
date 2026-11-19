{{-- Añadir reservación (administrador). Mismas reglas que el cliente: fecha solo en días disponibles,
     horario de la experiencia y cupo por horario.
     Tablas: solicitudes, asigna_experiencia, personas, experiencias, disponibilidad --}}
@extends('layouts.admin')

@section('titulo', 'Añadir reservación')

@php
    // Datos por experiencia para el formulario: foto, días disponibles y horarios con su cupo.
    $datosExperiencias = $experiencias->mapWithKeys(fn ($e) => [
        $e->id_experiencia => [
            'nombre' => $e->nombre,
            'imagen' => $e->imagen_url,
            'dias' => $e->disponibilidades->pluck('dias_semana')->map(fn ($d) => strtolower($d))->unique()->values(),
            'horarios' => $e->disponibilidades->map(fn ($d) => [
                'id' => $d->id_disponibilidad,
                'dia' => strtolower($d->dias_semana),
                'diaTexto' => $d->dia_texto,
                'horario' => $d->horario,
                'cupo' => (int) $d->pivot->cupo,
            ])->values(),
        ],
    ]);

    // Datos de cada cliente para llenar el formulario al elegirlo.
    $datosClientes = $clientes->mapWithKeys(fn ($c) => [
        $c->id_persona => [
            'nombre' => $c->nombre,
            'ap' => $c->ap,
            'am' => $c->am,
            'email' => $c->email,
            'telefono' => $c->telefono,
        ],
    ]);
@endphp

@section('contenido')
<div class="admin-cabecera">
    <div>
        <p><a href="{{ route('admin.solicitudes.index') }}">← Solicitudes</a></p>
        <h1>Añadir reservación</h1>
        <p>Registra una reservación a nombre de un cliente. Después podrás registrar su pago desde la solicitud.</p>
    </div>
</div>

@if ($errors->any())
    <div class="aviso aviso--error" role="alert">
        @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.reservas.store') }}" novalidate>
    @csrf

    <div class="detalle-admin">
        <div>
            <section class="panel form-grid">
                <h2 class="completo mb-0">Cliente</h2>

                <div class="campo completo">
                    <label for="cliente_id">Cliente existente</label>
                    <select class="control" id="cliente_id" name="cliente_id">
                        <option value="">— Registrar uno nuevo abajo —</option>
                        @foreach ($clientes as $c)
                            <option value="{{ $c->id_persona }}" @selected(old('cliente_id') == $c->id_persona)>
                                {{ $c->nombre }} {{ $c->ap }} · {{ $c->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id') <span class="error">{{ $message }}</span> @enderror
                </div>

                <p class="completo ayuda" id="ayuda-cliente"
                   data-nuevo="Si el cliente no está en la lista, llena estos datos. Se registrará como cliente nuevo. Si escribes el correo de un cliente registrado, sus datos se llenan solos."
                   data-existente="Datos del cliente seleccionado. Para registrar a otra persona, elige «Registrar uno nuevo» en la lista.">
                    Si el cliente no está en la lista, llena estos datos. Se registrará como cliente nuevo.
                </p>

                <div class="campo">
                    <label for="nuevo_nombre">Nombre <span class="req" aria-hidden="true">*</span></label>
                    <input class="control" id="nuevo_nombre" name="nuevo_nombre" maxlength="60" value="{{ old('nuevo_nombre') }}">
                    @error('nuevo_nombre') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="campo">
                    <label for="nuevo_ap">Apellido paterno <span class="req" aria-hidden="true">*</span></label>
                    <input class="control" id="nuevo_ap" name="nuevo_ap" maxlength="60" value="{{ old('nuevo_ap') }}">
                    @error('nuevo_ap') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="campo">
                    <label for="nuevo_am">Apellido materno</label>
                    <input class="control" id="nuevo_am" name="nuevo_am" maxlength="60" value="{{ old('nuevo_am') }}">
                </div>
                <div class="campo">
                    <label for="nuevo_email">Correo <span class="req" aria-hidden="true">*</span></label>
                    <input class="control" type="email" id="nuevo_email" name="nuevo_email" maxlength="120" value="{{ old('nuevo_email') }}">
                    @error('nuevo_email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="campo completo">
                    <label for="nuevo_telefono">Teléfono (10 dígitos, opcional)</label>
                    <input class="control" id="nuevo_telefono" name="nuevo_telefono" inputmode="numeric" maxlength="10" value="{{ old('nuevo_telefono') }}">
                    @error('nuevo_telefono') <span class="error">{{ $message }}</span> @enderror
                </div>
            </section>

            <section class="panel">
                <div class="fila fila--entre">
                    <h2 class="mb-0">Experiencias</h2>
                    <button class="btn btn--secundario btn--chico" type="button" id="agregar-item">+ Agregar experiencia</button>
                </div>
                @error('items') <span class="error">{{ $message }}</span> @enderror
                @error('items.*') <span class="error">{{ $message }}</span> @enderror

                <div id="items">
                    @foreach (old('items', [['id_experiencia' => null, 'fecha' => null, 'id_disponibilidad' => null, 'personas' => 1, 'nota' => null]]) as $i => $item)
                        <div class="horario-fila item-fila">
                            <img class="imagen-exp" alt="" hidden width="72" height="54"
                                 style="object-fit:cover;border-radius:6px">
                            <div class="campo">
                                <label>Experiencia <span class="req" aria-hidden="true">*</span></label>
                                <select class="control exp-item" name="items[{{ $i }}][id_experiencia]" required>
                                    <option value="">Elige…</option>
                                    @foreach ($experiencias as $exp)
                                        <option value="{{ $exp->id_experiencia }}" @selected(($item['id_experiencia'] ?? null) == $exp->id_experiencia)>
                                            {{ $exp->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="campo">
                                <label>Fecha <span class="req" aria-hidden="true">*</span></label>
                                <input class="control fecha-item" type="text" readonly autocomplete="off"
                                       placeholder="Elige una fecha"
                                       name="items[{{ $i }}][fecha]" value="{{ $item['fecha'] ?? '' }}" required>
                            </div>
                            <div class="campo">
                                <label>Horario <span class="req" aria-hidden="true">*</span></label>
                                <select class="control horario-item" name="items[{{ $i }}][id_disponibilidad]" required
                                        data-seleccion="{{ $item['id_disponibilidad'] ?? '' }}"></select>
                            </div>
                            <div class="campo">
                                <label>Personas <span class="req" aria-hidden="true">*</span> <small class="max-personas"></small></label>
                                <input class="control personas-item" type="number" min="1" name="items[{{ $i }}][personas]"
                                       value="{{ $item['personas'] ?? 1 }}" required>
                            </div>
                            <div class="campo">
                                <label>Nota</label>
                                <input class="control" type="text" maxlength="1000" name="items[{{ $i }}][nota]" value="{{ $item['nota'] ?? '' }}">
                            </div>
                            <button class="btn btn--peligro btn--chico" type="button" data-quitar aria-label="Quitar experiencia">✕</button>
                        </div>
                    @endforeach
                </div>

                <template id="plantilla-item">
                    <div class="horario-fila item-fila">
                        <img class="imagen-exp" alt="" hidden width="72" height="54"
                             style="object-fit:cover;border-radius:6px">
                        <div class="campo">
                            <label>Experiencia <span class="req" aria-hidden="true">*</span></label>
                            <select class="control exp-item" name="items[__I__][id_experiencia]" required>
                                <option value="">Elige…</option>
                                @foreach ($experiencias as $exp)
                                    <option value="{{ $exp->id_experiencia }}">{{ $exp->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="campo">
                            <label>Fecha <span class="req" aria-hidden="true">*</span></label>
                            <input class="control fecha-item" type="text" readonly autocomplete="off"
                                   placeholder="Elige una fecha" name="items[__I__][fecha]" required>
                        </div>
                        <div class="campo">
                            <label>Horario <span class="req" aria-hidden="true">*</span></label>
                            <select class="control horario-item" name="items[__I__][id_disponibilidad]" required></select>
                        </div>
                        <div class="campo">
                            <label>Personas <span class="req" aria-hidden="true">*</span> <small class="max-personas"></small></label>
                            <input class="control personas-item" type="number" min="1" name="items[__I__][personas]" value="1" required>
                        </div>
                        <div class="campo">
                            <label>Nota</label>
                            <input class="control" type="text" maxlength="1000" name="items[__I__][nota]">
                        </div>
                        <button class="btn btn--peligro btn--chico" type="button" data-quitar aria-label="Quitar experiencia">✕</button>
                    </div>
                </template>
            </section>
        </div>

        <div>
            <section class="panel">
                <button class="btn btn--primario btn--bloque" type="submit">Registrar reservación</button>
                <a class="btn btn--texto btn--bloque" href="{{ route('admin.solicitudes.index') }}">Cancelar</a>
                <p class="ayuda"><span class="req" aria-hidden="true">*</span> Campo obligatorio. En cliente nuevo, nombre, apellido y correo son obligatorios si no eliges un cliente existente.</p>
            </section>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script>window.EXPERIENCIAS = @json($datosExperiencias);</script>
    <script>window.CLIENTES = @json($datosClientes);</script>
    <script src="{{ asset('js/reserva-admin.js') }}" defer></script>
@endpush
