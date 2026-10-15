{{-- Lista de requisitos de contraseña segura. Se actualiza en vivo con public/js/validar-contrasena.js.
     Uso: <x-requisitos-contrasena para="id-del-input" /> --}}
@props(['para'])

<div class="requisitos" data-requisitos="{{ $para }}" id="requisitos-{{ $para }}" aria-live="polite">
    <p class="requisitos__titulo">La contraseña debe cumplir con los siguientes requisitos:</p>
    <ul>
        <li data-regla="letra"><span>Al menos <strong>una letra</strong></span></li>
        <li data-regla="mayuscula"><span>Al menos <strong>una letra mayúscula</strong></span></li>
        <li data-regla="numero"><span>Al menos <strong>un número</strong></span></li>
        <li data-regla="especial"><span>Al menos <strong>un carácter especial</strong> (por ejemplo ! @ # $ % * ?)</span></li>
        <li data-regla="largo"><span>Mínimo <strong>8 caracteres</strong></span></li>
    </ul>
</div>
