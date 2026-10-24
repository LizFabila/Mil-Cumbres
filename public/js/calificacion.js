// C16 · Calificar experiencia.
// Las estrellas funcionan sin JavaScript (son radios con CSS); aquí solo se
// actualiza el texto de la calificación elegida y el contador del comentario.
(function () {
    const form = document.getElementById('mc-cal-form');
    if (!form) return;

    const texto = document.getElementById('mc-cal-texto-estrellas');
    form.querySelectorAll('input[name="estrellas"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (texto) texto.textContent = radio.dataset.etiqueta;
        });
    });

    const area = document.getElementById('comentario');
    const contador = document.getElementById('mc-cal-contador');
    if (area && contador) {
        const max = parseInt(area.getAttribute('maxlength'), 10) || 1500;
        const actualizar = function () {
            const restantes = max - area.value.length;
            contador.textContent = restantes < 200 ? restantes + ' caracteres disponibles' : '';
        };
        area.addEventListener('input', actualizar);
        actualizar();
    }

    // Sin estrellas no se envía: se avisa en la página sin recargar.
    form.addEventListener('submit', function (e) {
        // En vista previa nunca se envía.
        if (form.hasAttribute('data-vista-previa')) {
            e.preventDefault();
            const elegida = form.querySelector('input[name="estrellas"]:checked');
            if (texto) {
                texto.textContent = elegida
                    ? 'Vista previa: se enviaría con ' + elegida.value + ' estrella' + (elegida.value === '1' ? '' : 's') + ' (no se guardó).'
                    : 'Elige de 1 a 5 estrellas para enviar tu calificación.';
            }
            return;
        }
        if (!form.querySelector('input[name="estrellas"]:checked')) {
            e.preventDefault();
            if (texto) {
                texto.textContent = 'Elige de 1 a 5 estrellas para enviar tu calificación.';
                texto.classList.add('mc-cal-estrellas__texto--error');
            }
            const primera = form.querySelector('input[name="estrellas"]');
            if (primera) primera.focus();
        }
    });
})();
