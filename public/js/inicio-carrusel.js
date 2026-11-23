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
    const menu = hero.querySelector('[data-hero-menu]');
    const botonMenu = menu && menu.querySelector('[data-hero-menu-boton]');
    const itemsMenu = menu ? Array.from(menu.querySelectorAll('[data-hero-ver]')) : [];
    const nombreActual = menu && menu.querySelector('[data-hero-menu-actual]');
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
        // Sombra más ligera solo cuando la foto lo pide (data-sombra="ligera").
        hero.classList.toggle('is-sombra-ligera', !!(fotos[i] && fotos[i].hasAttribute('data-sombra')));
        // Mariposas monarca que salen volando de la foto cuando esa diapositiva aparece.
        if (fotos[i] && fotos[i].hasAttribute('data-mariposas')) volarMariposas(fotos[i]);
        indicadores.forEach(function (b, k) {
            b.classList.toggle('is-activo', k === i);
            b.setAttribute('aria-selected', k === i ? 'true' : 'false');
        });
        // Marca en el menú lateral la experiencia que se está viendo.
        itemsMenu.forEach(function (a) {
            const actualMenu = parseInt(a.dataset.heroVer, 10) === i;
            a.classList.toggle('is-actual', actualMenu);
            if (actualMenu) a.setAttribute('aria-current', 'true'); else a.removeAttribute('aria-current');
        });
        if (nombreActual) {
            const item = itemsMenu.find(function (a) { return parseInt(a.dataset.heroVer, 10) === i; });
            nombreActual.textContent = item ? item.dataset.nombre : 'Bienvenida';
            nombreActual.style.animation = 'none'; void nombreActual.offsetWidth; nombreActual.style.animation = '';
        }
        // Reinicia la barra de avance del indicador activo.
        const barra = indicadores[i] && indicadores[i].querySelector('span');
        if (barra) { barra.style.animation = 'none'; void barra.offsetWidth; barra.style.animation = ''; }
    }

    // ---------- Mariposas monarca ----------
    // Cada mariposa es un SVG con las alas abiertas que aletea (CSS) mientras avanza
    // por una trayectoria aleatoria que sale de la foto. Se quitan al terminar.
    const MARIPOSAS_POR_FOTO = 7;
    const ALA_MONARCA =
        // Ala anterior: borde negro grueso, parche más claro al centro.
        '<path d="M50 34 C55 22 66 10 80 6 C90 3 97 6 97 13 C97 22 92 30 84 36 C76 42 62 42 50 38 Z" fill="#E8771E" stroke="#1d130a" stroke-width="2.6" stroke-linejoin="round"/>' +
        '<ellipse cx="68" cy="24" rx="8" ry="5" transform="rotate(-30 68 24)" fill="#F59A4E" opacity=".5"/>' +
        // Ala posterior.
        '<path d="M50 38 C60 38 76 40 84 48 C91 56 90 66 80 67 C72 68 66 64 60 58 C55 52 52 45 50 40 Z" fill="#E8771E" stroke="#1d130a" stroke-width="2.6" stroke-linejoin="round"/>' +
        '<ellipse cx="70" cy="52" rx="6" ry="3.5" transform="rotate(35 70 52)" fill="#F59A4E" opacity=".45"/>' +
        // Venas oscuras que parten del cuerpo.
        '<path d="M50 35 L76 12 M50 35 L88 22 M50 36 L86 32 M51 40 L72 46 M52 42 L74 58" stroke="#1d130a" stroke-width="1.1" fill="none" stroke-linecap="round" opacity=".9"/>' +
        // Manchas blancas dentro del borde negro.
        '<g fill="#fff6e6">' +
        '<circle cx="92" cy="9" r="1.1"/><circle cx="96" cy="16" r="1.1"/><circle cx="93" cy="27" r="1.1"/>' +
        '<circle cx="85" cy="34" r="1.1"/><circle cx="72" cy="40" r="1"/>' +
        '<circle cx="88" cy="50" r="1"/><circle cx="89" cy="60" r="1.1"/><circle cx="81" cy="64" r="1"/>' +
        '<circle cx="72" cy="62" r="1"/><circle cx="64" cy="57" r=".9"/>' +
        '</g>';

    function mariposaSvg() {
        return '<svg viewBox="0 0 100 70" aria-hidden="true" focusable="false">' +
            '<g transform="matrix(-1 0 0 1 100 0)"><g class="inicio-hero__ala">' + ALA_MONARCA + '</g></g>' +
            '<g class="inicio-hero__ala">' + ALA_MONARCA + '</g>' +
            // Abdomen y tórax.
            '<path d="M48.4 40 Q50 52 50 58 Q50 52 51.6 40 Z" fill="#1d130a"/>' +
            '<ellipse cx="50" cy="31" rx="2.8" ry="5" fill="#1d130a"/>' +
            '<circle cx="48.9" cy="28.5" r=".55" fill="#fff6e6"/><circle cx="51.1" cy="28.5" r=".55" fill="#fff6e6"/>' +
            '<circle cx="50" cy="23.6" r="2.1" fill="#1d130a"/>' +
            // Antenas con punta en maza.
            '<path d="M49 22 Q46 15 41 14 M51 22 Q54 15 59 14" stroke="#1d130a" stroke-width=".7" fill="none" stroke-linecap="round"/>' +
            '<circle cx="41" cy="14" r=".8" fill="#1d130a"/><circle cx="59" cy="14" r=".8" fill="#1d130a"/>' +
            '</svg>';
    }

    function volarMariposas(foto) {
        if (menosMovimiento) return;
        let capa = foto.querySelector('.inicio-hero__mariposas');
        if (!capa) {
            capa = document.createElement('div');
            capa.className = 'inicio-hero__mariposas';
            capa.setAttribute('aria-hidden', 'true');
            foto.appendChild(capa);
        }
        capa.textContent = '';

        for (let n = 0; n < MARIPOSAS_POR_FOTO; n++) {
            const m = document.createElement('div');
            m.className = 'inicio-hero__mariposa';
            m.innerHTML = mariposaSvg();
            m.style.left = (45 + Math.random() * 35) + '%';
            m.style.top = (15 + Math.random() * 50) + '%';
            // Aleteo de 0.42 a 0.67 s por ciclo, distinto en cada mariposa.
            m.style.setProperty('--aleteo', (0.42 + Math.random() * 0.25).toFixed(2) + 's');
            capa.appendChild(m);

            const dx = 160 + Math.random() * 220;                 // hacia la derecha
            const dy = -(80 + Math.random() * 200);               // hacia arriba
            const vaiven = (Math.random() - 0.5) * 80;            // zigzag lateral
            const giro = (Math.random() * 30) - 15;
            const duracion = 3800 + Math.random() * 2000;
            const retardo = n * 180 + Math.random() * 400;

            const anim = m.animate([
                { transform: 'translate(0px, 0px) rotate(' + giro + 'deg) scale(.5)', opacity: 0, offset: 0 },
                { transform: 'translate(' + (dx * .2 + vaiven) + 'px, ' + (dy * .2) + 'px) rotate(' + giro + 'deg) scale(.9)', opacity: 1, offset: .2 },
                { transform: 'translate(' + (dx * .6 - vaiven) + 'px, ' + (dy * .6) + 'px) rotate(' + giro + 'deg) scale(1)', opacity: 1, offset: .6 },
                { transform: 'translate(' + dx + 'px, ' + dy + 'px) rotate(' + giro + 'deg) scale(1)', opacity: 0, offset: 1 }
            ], { duration: duracion, delay: retardo, easing: 'ease-in-out', fill: 'both' });
            anim.onfinish = function () { m.remove(); };
        }
    }

    let menuAbierto = false;
    function reproduciendo() { return !pausadoPorUsuario && !enEspera && !menuAbierto; }

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

    // Menú lateral de experiencias.
    // Pasar el mouse (o el foco) por una experiencia la muestra en el carrusel; el clic abre su detalle.
    // Con el menú abierto el carrusel se detiene para que se pueda leer con calma.
    if (menu && botonMenu) {
        function abrirMenu(abrir) {
            menuAbierto = abrir;
            menu.classList.toggle('is-abierto', abrir);
            botonMenu.setAttribute('aria-expanded', abrir ? 'true' : 'false');
            botonMenu.setAttribute('aria-label', abrir ? 'Cerrar menú de experiencias' : 'Abrir menú de experiencias');
            programar();
        }

        botonMenu.addEventListener('click', function () { abrirMenu(!menuAbierto); });

        itemsMenu.forEach(function (a) {
            function mostrar() {
                const i = parseInt(a.dataset.heroVer, 10);
                if (i !== actual) ir(i);
            }
            a.addEventListener('mouseenter', mostrar);
            a.addEventListener('focus', mostrar);
        });

        // Se cierra con Escape o al hacer clic fuera del menú.
        menu.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && menuAbierto) { abrirMenu(false); botonMenu.focus(); }
        });
        document.addEventListener('click', function (e) {
            if (menuAbierto && !menu.contains(e.target)) abrirMenu(false);
        });

        botonMenu.setAttribute('aria-label', 'Abrir menú de experiencias');
    }

    actualizarBotonPausa();
    marcar(0);
    programar();
})();
