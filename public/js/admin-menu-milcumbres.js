/* Menú lateral del panel administrativo en pantallas pequeñas.
   El botón ☰ abre el menú; la ×, el fondo oscuro o la tecla Esc lo cierran. */
(function () {
    'use strict';

    var body = document.querySelector('.mc-admin-body');
    var sidebar = document.getElementById('mc-admin-sidebar');
    var boton = document.querySelector('[data-mc-adm-toggle]');
    var fondo = document.querySelector('.mc-adm-backdrop');

    if (!body || !sidebar || !boton) return;

    function abrirMenu(abrir) {
        body.classList.toggle('mc-adm-menu-open', abrir);
        boton.setAttribute('aria-expanded', abrir ? 'true' : 'false');
        boton.setAttribute('aria-label', abrir ? 'Cerrar menú administrativo' : 'Abrir menú administrativo');

        if (fondo) {
            fondo.hidden = !abrir;
        }
    }

    boton.addEventListener('click', function () {
        abrirMenu(!body.classList.contains('mc-adm-menu-open'));
    });

    document.querySelectorAll('[data-mc-adm-close]').forEach(function (elemento) {
        elemento.addEventListener('click', function () {
            abrirMenu(false);
        });
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') {
            abrirMenu(false);
        }
    });

    // Al pasar a pantalla grande el menú siempre está visible; se limpia el estado móvil.
    var escritorio = window.matchMedia('(min-width: 1081px)');
    escritorio.addEventListener('change', function (evento) {
        if (evento.matches) {
            abrirMenu(false);
        }
    });
})();
