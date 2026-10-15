
document.addEventListener('DOMContentLoaded', () => {

    const header = document.getElementById('mc-hub');

    if (!header) return;

    const mobileButton = header.querySelector(
        '.mc-hub__mobile-toggle'
    );

    const menuButtons = Array.from(
        header.querySelectorAll('[data-mc-menu-toggle]')
    );

    // Obtener los botones y sus paneles
    const menus = menuButtons.map(button => {

        const id = button.getAttribute('aria-controls');

        return {
            button,
            panel: document.getElementById(id)
        };

    }).filter(item => item.panel !== null);


    // Cerrar todos los desplegables
    function cerrarDesplegables() {

        menus.forEach(({ button, panel }) => {

            panel.hidden = true;

            button.setAttribute(
                'aria-expanded',
                'false'
            );

        });

    }


    // Cerrar menú móvil
    function cerrarMenuMovil() {

        header.classList.remove('is-mobile-open');

        if (mobileButton) {

            mobileButton.setAttribute(
                'aria-expanded',
                'false'
            );

            mobileButton.setAttribute(
                'aria-label',
                'Abrir menú principal'
            );

        }

    }


    // Abrir o cerrar el panel seleccionado
    menus.forEach(({ button, panel }) => {

        button.addEventListener('click', () => {

            const abrir = panel.hidden;

            cerrarDesplegables();

            if (abrir) {

                panel.hidden = false;

                button.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        });

    });


    // Botón hamburguesa
    if (mobileButton) {

        mobileButton.addEventListener('click', () => {

            const abierto = header.classList.toggle(
                'is-mobile-open'
            );

            mobileButton.setAttribute(
                'aria-expanded',
                String(abierto)
            );

            mobileButton.setAttribute(
                'aria-label',
                abierto
                    ? 'Cerrar menú principal'
                    : 'Abrir menú principal'
            );

            if (!abierto) {
                cerrarDesplegables();
            }

        });

    }


    // Cerrar al hacer clic fuera
    document.addEventListener('click', (event) => {

        if (!header.contains(event.target)) {

            cerrarDesplegables();

            cerrarMenuMovil();

        }

    });


    // Cerrar con Escape
    document.addEventListener('keydown', (event) => {

        if (event.key === 'Escape') {

            const abierto = menus.find(
                ({ panel }) => !panel.hidden
            );

            cerrarDesplegables();

            cerrarMenuMovil();

            if (abierto) {
                abierto.button.focus();
            }

        }

    });


    // Restablecer al cambiar tamaño de pantalla
    window.addEventListener('resize', () => {

        if (window.innerWidth > 1120) {

            cerrarMenuMovil();

            cerrarDesplegables();

        }

    });


    // Cerrar los desplegables al navegar
    header.querySelectorAll(
        '.mc-hub__dropdown a, .mc-hub__nav > a'
    ).forEach(link => {

        link.addEventListener('click', () => {

            cerrarDesplegables();

            cerrarMenuMovil();

        });

    });

});
