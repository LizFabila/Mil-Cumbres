// Panel de ofertas: se abre con [data-mc-ofertas-abrir] o al entrar con #ofertas en la dirección.
(() => {
    const panel = document.querySelector('[data-mc-ofertas]');
    if (!panel) return;

    let origen = null;

    const abrir = (boton) => {
        origen = boton || null;
        panel.hidden = false;
        document.body.classList.add('mc-ofertas-abierto');
        panel.querySelector('.mc-ofertas__cerrar')?.focus();
    };

    const cerrar = () => {
        panel.hidden = true;
        document.body.classList.remove('mc-ofertas-abierto');
        if (location.hash === '#ofertas') history.replaceState(null, '', location.pathname + location.search);
        origen?.focus();
    };

    document.querySelectorAll('[data-mc-ofertas-abrir]').forEach((b) => b.addEventListener('click', () => abrir(b)));
    panel.querySelectorAll('[data-mc-ofertas-cerrar]').forEach((b) => b.addEventListener('click', cerrar));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !panel.hidden) cerrar(); });

    // Enlace directo para compartir en redes: misitio.com/#ofertas
    if (location.hash === '#ofertas') abrir();
})();
