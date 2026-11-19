// Inicio: botones anterior y siguiente de los carruseles de galería.
(function () {
    document.querySelectorAll('[data-ini2-carrusel]').forEach(function (carrusel) {
        const nombre = carrusel.dataset.ini2Carrusel;
        const anterior = document.querySelector('[data-ini2-anterior="' + nombre + '"]');
        const siguiente = document.querySelector('[data-ini2-siguiente="' + nombre + '"]');

        function paso() {
            const foto = carrusel.querySelector('.ini2-carrusel__foto');
            return foto ? foto.getBoundingClientRect().width + 18 : 300;
        }

        if (anterior) {
            anterior.addEventListener('click', function () {
                carrusel.scrollBy({ left: -paso(), behavior: 'smooth' });
            });
        }
        if (siguiente) {
            siguiente.addEventListener('click', function () {
                carrusel.scrollBy({ left: paso(), behavior: 'smooth' });
            });
        }

        // Con teclado: flechas izquierda y derecha cuando el carrusel tiene el foco.
        carrusel.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') carrusel.scrollBy({ left: paso(), behavior: 'smooth' });
            if (e.key === 'ArrowLeft') carrusel.scrollBy({ left: -paso(), behavior: 'smooth' });
        });
    });
})();

// Animaciones: cada bloque se revela al entrar en pantalla. Sin JS, todo queda visible.
(function () {
    document.documentElement.classList.add('ini2-js');
    const elementos = document.querySelectorAll('[data-ini2]');

    if (!('IntersectionObserver' in window)) {
        elementos.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }

    const observador = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (entrada) {
            // Se anima cada vez que el bloque entra o sale de pantalla al desplazarse.
            entrada.target.classList.toggle('is-visible', entrada.isIntersecting);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    elementos.forEach(function (el) { observador.observe(el); });
})();
