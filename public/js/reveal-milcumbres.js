/* Mil Cumbres · revelado de elementos al hacer scroll.
   Marca con .is-visible los elementos [data-reveal] al entrar en pantalla.
   Si el navegador no soporta IntersectionObserver o el usuario pide
   movimiento reducido, todo se muestra de inmediato. */
(function () {
    'use strict';

    // Las vistas que no marcan [data-reveal] a mano reciben la animación en
    // sus secciones de primer nivel (sin tocar cada Blade).
    var principal = document.getElementById('contenido-principal');
    if (principal && !document.querySelector('[data-reveal]')) {
        principal.querySelectorAll('section').forEach(function (seccion) {
            if (!seccion.parentElement.closest('section')) {
                seccion.setAttribute('data-reveal', '');
            }
        });
    }

    var elementos = document.querySelectorAll('[data-reveal]');
    if (!elementos.length) return;

    var reducirMovimiento = window.matchMedia
        && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reducirMovimiento || !('IntersectionObserver' in window)) {
        elementos.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }

    document.documentElement.classList.add('js-reveal');

    var UMBRAL = 0.12;

    var observador = new IntersectionObserver(function (entradas, obs) {
        entradas.forEach(function (entrada) {
            if (!entrada.isIntersecting) return;

            // Un elemento más alto que la pantalla nunca llega a mostrar el 12 %
            // de sí mismo (p. ej. "Mis solicitudes" con muchas reservaciones),
            // así que se revela en cuanto empieza a entrar.
            var altoPantalla = entrada.rootBounds ? entrada.rootBounds.height : window.innerHeight;
            var esMuyAlto = entrada.boundingClientRect.height * UMBRAL > altoPantalla;

            if (entrada.intersectionRatio < UMBRAL && !esMuyAlto) return;

            entrada.target.classList.add('is-visible');
            obs.unobserve(entrada.target);
        });
    }, { threshold: [0, UMBRAL], rootMargin: '0px 0px -40px 0px' });

    elementos.forEach(function (el) { observador.observe(el); });
})();
