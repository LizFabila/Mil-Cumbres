/* Mil Cumbres · acentos de títulos según el fondo.
   Las cursivas (<em>) de h1-h3 van en azul sobre fondo claro y en lima sobre
   fondo oscuro. Se decide por el color del título: si es claro, la sección es
   oscura. Así no hace falta una regla por cada vista. */
(function () {
    'use strict';

    var principal = document.getElementById('contenido-principal');
    if (!principal) return;

    function luminancia(color) {
        var n = color.match(/[\d.]+/g);
        if (!n) return 0;
        return 0.299 * n[0] + 0.587 * n[1] + 0.114 * n[2];
    }

    principal.querySelectorAll('h1 em, h2 em, h3 em').forEach(function (em) {
        var titulo = em.closest('h1, h2, h3');
        var claro = luminancia(getComputedStyle(titulo).color) > 150;
        em.setAttribute('data-acento', claro ? 'lima' : 'azul');
    });
})();
