// C01 · Carrusel del hero de inicio.
// Diapositiva 0 = portada original; después, una por experiencia en el orden del catálogo.
// Avanza sola cada 3 s (también con el mouse encima) y se puede pausar con su botón.
(function () {
    const hero = document.querySelector('[data-hero-carrusel]');
    if (!hero) return;

    const paneles = Array.from(hero.querySelectorAll('.inicio-hero__panel'));
    const fotos = Array.from(hero.querySelectorAll('.inicio-hero__foto'));
    const indicadores = Array.from(hero.querySelectorAll('[data-hero-ir]'));
    const botonPausa = hero.querySelector('[data-hero-pausa]');
    if (paneles.length < 2) return;

    const INTERVALO = 3000;
    const menosMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let actual = 0;
    let temporizador = null;
    let pausadoPorUsuario = menosMovimiento;   // con "reducir movimiento" no avanza solo
    let enEspera = false;                       // mouse encima, foco dentro o pestaña oculta

    hero.style.setProperty('--hero-intervalo', INTERVALO + 'ms');
    hero.classList.add('is-carrusel');
    paneles.forEach(function (p) { p.hidden = false; });

    function marcar(i) {
        paneles.forEach(function (p, k) {
            const activo = k === i;
            p.classList.toggle('is-activo', activo);
            p.setAttribute('aria-hidden', activo ? 'false' : 'true');
            p.inert = !activo;                  // los enlaces ocultos no reciben foco
        });
        fotos.forEach(function (f, k) { f.classList.toggle('is-activa', k === i); });
        indicadores.forEach(function (b, k) {
            b.classList.toggle('is-activo', k === i);
            b.setAttribute('aria-selected', k === i ? 'true' : 'false');
        });
        // Reinicia la barra de avance del indicador activo.
        const barra = indicadores[i] && indicadores[i].querySelector('span');
        if (barra) { barra.style.animation = 'none'; void barra.offsetWidth; barra.style.animation = ''; }
    }

    function reproduciendo() { return !pausadoPorUsuario && !enEspera; }

    function programar() {
        clearTimeout(temporizador);
        hero.classList.toggle('is-reproduciendo', reproduciendo());
        if (reproduciendo()) {
            temporizador = setTimeout(function () { ir(actual + 1); }, INTERVALO);
        }
    }

    function ir(i) {
        actual = (i + paneles.length) % paneles.length;
        marcar(actual);
        programar();
    }

    hero.querySelector('[data-hero-anterior]').addEventListener('click', function () { ir(actual - 1); });
    hero.querySelector('[data-hero-siguiente]').addEventListener('click', function () { ir(actual + 1); });
    indicadores.forEach(function (b) {
        b.addEventListener('click', function () { ir(parseInt(b.dataset.heroIr, 10)); });
    });

    function actualizarBotonPausa() {
        botonPausa.setAttribute('aria-pressed', pausadoPorUsuario ? 'true' : 'false');
        botonPausa.setAttribute('aria-label', pausadoPorUsuario ? 'Reproducir carrusel' : 'Pausar carrusel');
    }
    botonPausa.addEventListener('click', function () {
        pausadoPorUsuario = !pausadoPorUsuario;
        actualizarBotonPausa();
        programar();
    });

    // Flechas del teclado cuando el foco está en los controles.
    hero.querySelector('.inicio-hero__controles').addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') { e.preventDefault(); ir(actual - 1); }
        if (e.key === 'ArrowRight') { e.preventDefault(); ir(actual + 1); }
    });

    // Espera solo mientras se navega con el teclado dentro del carrusel o la pestaña está oculta.
    // Con el mouse encima el tiempo sigue corriendo.
    function esperar(valor) { enEspera = valor; programar(); }
    hero.addEventListener('focusin', function (e) { if (e.target.matches(':focus-visible')) esperar(true); });
    hero.addEventListener('focusout', function (e) { if (!hero.contains(e.relatedTarget)) esperar(document.hidden); });
    document.addEventListener('visibilitychange', function () { esperar(document.hidden); });

    actualizarBotonPausa();
    marcar(0);
    programar();
})();
