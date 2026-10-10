{{-- Aviso de privacidad (texto provisional: sustituir por el aviso legal definitivo) --}}
@extends('layouts.app')

@section('titulo', 'Aviso de privacidad')

@section('contenido')
<div class="contenedor contenedor--angosto seccion">
    <h1 class="titulo-seccion">Aviso de privacidad</h1>
    <p class="aviso aviso--info">Texto provisional. Reemplázalo por el aviso de privacidad definitivo.</p>
    <p class="texto-suave">
        Mil Cumbres, con domicilio en {{ config('milcumbres.contacto.lugar') }}, es responsable del tratamiento de los datos
        personales que nos proporcionas (nombre, correo electrónico y teléfono). Los usamos únicamente para gestionar tus
        solicitudes de experiencias y de consultoría, confirmar reservaciones y validar pagos.
    </p>
    <p class="texto-suave">
        Puedes ejercer tus derechos de acceso, rectificación, cancelación u oposición escribiendo a
        <a href="mailto:{{ config('milcumbres.contacto.correo') }}">{{ config('milcumbres.contacto.correo') }}</a>.
    </p>
</div>
@endsection
