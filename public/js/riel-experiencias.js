// Riel "Experiencias" de las páginas del cliente: abre y cierra el panel.
document.addEventListener('DOMContentLoaded', function () {
    var riel = document.querySelector('[data-riel-experiencias]');
    if (!riel) return;
    var boton = riel.querySelector('[data-riel-boton]');
    var cabecera = document.getElementById('mc-hub');

    // El riel empieza justo donde termina la barra superior (que es sticky).
    function alinearConCabecera() {
        if (cabecera) riel.style.top = cabecera.offsetHeight + 'px';
    }
    alinearConCabecera();
    window.addEventListener('resize', alinearConCabecera);
    if (cabecera && 'ResizeObserver' in window) {
        new ResizeObserver(alinearConCabecera).observe(cabecera);
    }

    function cerrar() {
        riel.classList.remove('is-abierto');
        boton.setAttribute('aria-expanded', 'false');
    }

    boton.addEventListener('click', function () {
        var abre = !riel.classList.contains('is-abierto');
        riel.classList.toggle('is-abierto', abre);
        boton.setAttribute('aria-expanded', abre ? 'true' : 'false');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') cerrar();
    });

    document.addEventListener('click', function (e) {
        if (!riel.contains(e.target)) cerrar();
    });
});
