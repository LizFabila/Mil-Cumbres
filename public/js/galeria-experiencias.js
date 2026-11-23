// Galería de experiencias: el botón circular pasa a la siguiente imagen (catálogo y detalle).
// Cada bloque tiene data-galeria con la lista de URLs (portada + galería) y un <img data-galeria-img>.
// Si el bloque tiene [data-galeria-contador], muestra "n / total" y se actualiza al pulsar.
(function () {
    document.querySelectorAll('[data-galeria]').forEach(function (bloque) {
        var urls;
        try {
            urls = JSON.parse(bloque.getAttribute('data-galeria'));
        } catch (e) {
            return;
        }

        var img = bloque.querySelector('[data-galeria-img]');
        var boton = bloque.querySelector('[data-galeria-siguiente]');
        var contador = bloque.querySelector('[data-galeria-contador]');

        if (!img || !boton || !Array.isArray(urls) || urls.length < 2) {
            return;
        }

        var indice = 0;

        function mostrarContador() {
            if (contador) {
                contador.textContent = (indice + 1) + ' / ' + urls.length;
            }
        }

        boton.addEventListener('click', function (evento) {
            // Está dentro de una tarjeta con enlace: que no navegue al detalle.
            evento.preventDefault();
            evento.stopPropagation();

            indice = (indice + 1) % urls.length;
            img.src = urls[indice];
            mostrarContador();
        });
    });
})();
