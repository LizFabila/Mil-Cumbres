// C06 · Calcula el total y valida los días de cada experiencia.
(function () {
    const form = document.getElementById('form-solicitud');
    if (!form) return;

    const checks = form.querySelectorAll('input[name="experiencias[]"]');
    const personas = document.getElementById('cant_persona');
    const total = document.getElementById('total');
    const avisoDia = document.getElementById('aviso-dia');

    const moneda = new Intl.NumberFormat('es-MX', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function actualizar() {
        const n = Math.max(
            parseInt(personas?.value, 10) || 0,
            0
        );

        let suma = 0;
        const sinHorario = [];

        checks.forEach(function (casilla) {
            if (!casilla.checked) return;

            suma += parseFloat(casilla.dataset.precio) || 0;

            const id = casilla.value;
            const bloque = form.querySelector(
                '.fecha-experiencia[data-experiencia="' + id + '"]'
            );

            if (!bloque) return;

            const campo = bloque.querySelector('input[type="date"]');

            if (!campo || !campo.value) return;

            const dia = new Date(
                campo.value + 'T12:00:00'
            ).getDay();

            const dias = (bloque.dataset.dias || '')
                .split(',')
                .map(valor => Number(valor.trim()) % 7);

            if (!dias.includes(dia)) {
                const nombre = casilla
                    .closest('label')
                    ?.querySelector('strong')
                    ?.textContent
                    ?.trim() || 'Experiencia seleccionada';

                sinHorario.push(nombre);
            }
        });

        if (total) {
            total.textContent = '$' + moneda.format(suma * n) + ' MXN';
        }

        if (avisoDia) {
            avisoDia.textContent = sinHorario.length
                ? 'Revisa las fechas de estas experiencias: '
                    + sinHorario.join(', ')
                    + '.'
                : '';
        }
    }

    form.addEventListener('input', actualizar);
    form.addEventListener('change', actualizar);

    actualizar();
});