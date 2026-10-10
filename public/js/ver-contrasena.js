// Botón "Mostrar / Ocultar" para campos de contraseña: <button data-ver="id-del-input">
document.querySelectorAll('[data-ver]').forEach(function (boton) {
    const input = document.getElementById(boton.dataset.ver);
    if (!input) return;

    boton.addEventListener('click', function () {
        const mostrar = input.type === 'password';
        input.type = mostrar ? 'text' : 'password';
        boton.textContent = mostrar ? 'Ocultar' : 'Mostrar';
        boton.setAttribute('aria-pressed', String(mostrar));
        input.focus();
    });
});
