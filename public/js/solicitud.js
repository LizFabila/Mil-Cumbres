// C06 · Calcula el total estimado y avisa si la fecha no cae en un día con horario.
(function () {
    const form = document.getElementById('form-solicitud');
    if (!form) return;

    const checks = form.querySelectorAll('input[name="experiencias[]"]');
    const personas = form.querySelector('#cant_persona');
    const fecha = form.querySelector('#fecha_deseada');
    const total = document.getElementById('total');
    const avisoDia = document.getElementById('aviso-dia');
    const dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    const moneda = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function actualizar() {
        const n = Math.max(parseInt(personas.value, 10) || 0, 0);
        let suma = 0;
        const sinHorario = [];

        // "2026-10-17" se interpreta en hora local para no recorrer el día.
        const dia = fecha.value ? dias[new Date(fecha.value + 'T12:00:00').getDay()] : null;

        checks.forEach(function (c) {
            if (!c.checked) return;
            suma += parseFloat(c.dataset.precio);
            if (dia && c.dataset.dias.split(',').indexOf(dia) === -1) {
                sinHorario.push(c.closest('label').querySelector('strong').textContent);
            }
        });

        total.textContent = '$' + moneda.format(suma * n) + ' MXN';
        avisoDia.textContent = sinHorario.length
            ? 'Sin horario ese día para: ' + sinHorario.join(', ') + '.'
            : '';
    }

    form.addEventListener('input', actualizar);
    form.addEventListener('change', actualizar);
    actualizar();
})();
