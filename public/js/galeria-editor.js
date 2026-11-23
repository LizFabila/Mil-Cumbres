// A07 · Editor de recorte (galería y portada), mismo encuadre que ve el cliente (16:9).
// Las fotos pueden ser de cualquier tamaño: se reducen en el navegador (máx. 1600 px) al guardar.
(function () {
    const ANCHO_SALIDA = 1600;

    function acotar(valor, minimo, maximo) {
        return Math.min(maximo, Math.max(minimo, valor));
    }

    function cargarImagen(archivo) {
        return new Promise(function (resolver, rechazar) {
            const url = URL.createObjectURL(archivo);
            const img = new Image();
            img.onload = function () { resolver({ img: img, url: url }); };
            img.onerror = function () {
                URL.revokeObjectURL(url);
                rechazar(new Error('No se pudo leer la imagen ' + archivo.name + '.'));
            };
            img.src = url;
        });
    }

    // Escala y posición en pantalla. El centro del cuadro corresponde a (cx, cy) de la imagen (0 a 1).
    function geometria(item) {
        const W = item.marco.clientWidth;
        const H = item.marco.clientHeight;
        const s = Math.max(W / item.w, H / item.h) * item.zoom;
        return { W: W, H: H, w: item.w, h: item.h, s: s, dw: item.w * s, dh: item.h * s };
    }

    // Límites del centro para que el cuadro siempre quede cubierto por la foto.
    function limites(item) {
        const g = geometria(item);
        return {
            minX: (g.W / 2) / g.dw,
            maxX: 1 - (g.W / 2) / g.dw,
            minY: (g.H / 2) / g.dh,
            maxY: 1 - (g.H / 2) / g.dh,
        };
    }

    function sincronizarDeslizadores(item, l) {
        const fraccion = function (valor, min, max) {
            return max - min > 1e-6 ? (valor - min) / (max - min) : 0.5;
        };
        const d = item.deslizadores;
        d.zoom.value = item.zoom;
        d.horizontal.value = fraccion(item.cx, l.minX, l.maxX);
        d.vertical.value = fraccion(item.cy, l.minY, l.maxY);
        // Si la foto ya llena el cuadro en un sentido, ese deslizador no hace nada.
        d.horizontal.disabled = l.maxX - l.minX <= 1e-6;
        d.vertical.disabled = l.maxY - l.minY <= 1e-6;
    }

    function dibujar(item) {
        const g = geometria(item);
        const l = limites(item);
        item.cx = acotar(item.cx, l.minX, l.maxX);
        item.cy = acotar(item.cy, l.minY, l.maxY);

        item.img.style.width = g.dw + 'px';
        item.img.style.height = g.dh + 'px';
        item.img.style.left = (g.W / 2 - item.cx * g.dw) + 'px';
        item.img.style.top = (g.H / 2 - item.cy * g.dh) + 'px';

        sincronizarDeslizadores(item, l);
    }

    function exportar(item) {
        const g = geometria(item);
        // Región visible, en píxeles de la imagen original.
        const rw = g.W / g.s;
        const rh = g.H / g.s;
        const sx = acotar(item.cx * g.w - rw / 2, 0, g.w - rw);
        const sy = acotar(item.cy * g.h - rh / 2, 0, g.h - rh);
        const ancho = Math.min(ANCHO_SALIDA, Math.round(rw));
        const alto = Math.round(ancho * g.H / g.W);

        const lienzo = document.createElement('canvas');
        lienzo.width = ancho;
        lienzo.height = alto;
        lienzo.getContext('2d').drawImage(item.img, sx, sy, rw, rh, 0, 0, ancho, alto);

        return new Promise(function (resolver, rechazar) {
            lienzo.toBlob(function (blob) {
                if (blob) resolver(blob);
                else rechazar(new Error('No se pudo preparar la imagen.'));
            }, 'image/jpeg', 0.88);
        });
    }

    // Crea el recuadro de una foto con su cuadrícula, zoom, posición y arrastre.
    function crearItem(archivo, cargada, alQuitar) {
        const elemento = document.createElement('div');
        elemento.className = 'galeria-editor__item';
        elemento.innerHTML =
            '<div class="galeria-editor__marco">' +
                '<img class="galeria-editor__imagen" alt="" draggable="false">' +
                '<div class="galeria-editor__cuadricula" aria-hidden="true"></div>' +
            '</div>' +
            '<div class="galeria-editor__controles">' +
                '<label class="galeria-editor__zoom">Acercar' +
                    '<input type="range" min="1" max="4" step="0.01" value="1" data-zoom></label>' +
                '<label class="galeria-editor__zoom">Arriba y abajo' +
                    '<input type="range" min="0" max="1" step="0.001" value="0.5" data-vertical></label>' +
                '<label class="galeria-editor__zoom">Izquierda y derecha' +
                    '<input type="range" min="0" max="1" step="0.001" value="0.5" data-horizontal></label>' +
                '<button type="button" class="btn btn--texto btn--chico" data-quitar>Quitar</button>' +
            '</div>' +
            '<p class="ayuda mb-0"></p>';

        const item = {
            archivo: archivo,
            img: elemento.querySelector('img'),
            w: cargada.img.naturalWidth,
            h: cargada.img.naturalHeight,
            url: cargada.url,
            marco: elemento.querySelector('.galeria-editor__marco'),
            zoom: 1,
            cx: 0.5,
            cy: 0.5,
            elemento: elemento,
            deslizadores: {
                zoom: elemento.querySelector('[data-zoom]'),
                vertical: elemento.querySelector('[data-vertical]'),
                horizontal: elemento.querySelector('[data-horizontal]'),
            },
        };
        item.img.src = cargada.url;

        const d = item.deslizadores;
        d.zoom.addEventListener('input', function () {
            item.zoom = parseFloat(d.zoom.value);
            dibujar(item);
        });
        d.horizontal.addEventListener('input', function () {
            const l = limites(item);
            item.cx = l.minX + parseFloat(d.horizontal.value) * (l.maxX - l.minX);
            dibujar(item);
        });
        d.vertical.addEventListener('input', function () {
            const l = limites(item);
            item.cy = l.minY + parseFloat(d.vertical.value) * (l.maxY - l.minY);
            dibujar(item);
        });

        // Arrastrar la foto dentro del cuadro (en ambos sentidos, con mouse o pantalla táctil).
        let arrastre = null;
        item.marco.addEventListener('pointerdown', function (e) {
            arrastre = { x: e.clientX, y: e.clientY, cx: item.cx, cy: item.cy };
            item.marco.setPointerCapture(e.pointerId);
        });
        item.marco.addEventListener('pointermove', function (e) {
            if (!arrastre) return;
            const g = geometria(item);
            item.cx = arrastre.cx - (e.clientX - arrastre.x) / g.dw;
            item.cy = arrastre.cy - (e.clientY - arrastre.y) / g.dh;
            dibujar(item);
        });
        ['pointerup', 'pointercancel'].forEach(function (evento) {
            item.marco.addEventListener(evento, function () { arrastre = null; });
        });

        // Rueda del mouse para acercar o alejar.
        item.marco.addEventListener('wheel', function (e) {
            e.preventDefault();
            item.zoom = acotar(item.zoom * (1 - e.deltaY * 0.001), 1, 4);
            dibujar(item);
        }, { passive: false });

        elemento.querySelector('[data-quitar]').addEventListener('click', function () {
            alQuitar(item);
        });

        const mb = (archivo.size / (1024 * 1024)).toFixed(1);
        elemento.querySelector('.ayuda').textContent = archivo.name + ' · ' + mb + ' MB → se guardará a ' +
            Math.min(ANCHO_SALIDA, item.w) + ' px de ancho';

        return item;
    }

    // ----- Galería: varias fotos, se suben con el botón "Cargar" (formulario propio) -----
    function iniciarGaleria() {
        const inputGaleria = document.getElementById('imagenes');
        const formGaleria = document.getElementById('form-galeria');
        const editor = document.getElementById('galeria-editor');
        if (!inputGaleria || !formGaleria || !editor) return;

        const lista = document.getElementById('galeria-editor-lista');
        const conteo = document.getElementById('galeria-editor-conteo');
        const aviso = document.getElementById('galeria-editor-aviso');
        const boton = document.getElementById('boton-cargar-galeria');
        const MAXIMO = parseInt(editor.dataset.maximo || '10', 10);
        const pendientes = [];
        let contador = 0;
        let preparando = false;

        function mostrarAviso(texto) {
            aviso.textContent = texto;
            aviso.hidden = !texto;
        }

        // Fotos de la galería que siguen (no marcadas para quitar).
        function galeriaActual() {
            return Array.from(document.querySelectorAll('.galeria-admin__item input[name="imagenes_eliminar[]"]'))
                .filter(function (c) { return !c.checked; }).length;
        }

        function actualizarConteo() {
            const n = pendientes.length;
            editor.hidden = n === 0;
            conteo.textContent = n + (n === 1 ? ' imagen por cargar' : ' imágenes por cargar');
        }

        function quitarPendiente(item) {
            const indice = pendientes.indexOf(item);
            if (indice >= 0) pendientes.splice(indice, 1);
            URL.revokeObjectURL(item.url);
            item.elemento.remove();
            mostrarAviso('');
            actualizarConteo();
        }

        inputGaleria.addEventListener('change', async function () {
            const archivos = Array.from(inputGaleria.files || []);
            inputGaleria.value = '';
            mostrarAviso('');

            for (const archivo of archivos) {
                if (MAXIMO - galeriaActual() - pendientes.length <= 0) {
                    mostrarAviso('La galería admite máximo ' + MAXIMO + ' imágenes. Quita alguna o quita alguna marcada.');
                    break;
                }
                try {
                    const cargada = await cargarImagen(archivo);
                    const item = crearItem(archivo, cargada, quitarPendiente);
                    pendientes.push(item);
                    actualizarConteo(); // primero mostrar el editor: el cuadro necesita medidas reales
                    lista.appendChild(item.elemento);
                    dibujar(item);
                } catch (error) {
                    mostrarAviso(error.message);
                }
            }
        });

        window.addEventListener('resize', function () {
            pendientes.forEach(dibujar);
        });

        formGaleria.addEventListener('submit', async function (e) {
            if (pendientes.length === 0) return; // solo quitar marcadas: envío normal

            e.preventDefault();
            if (preparando) return;
            preparando = true;
            boton.disabled = true;
            boton.textContent = 'Cargando…';
            mostrarAviso('Preparando imágenes…');

            try {
                const transferencia = new DataTransfer();
                for (let i = 0; i < pendientes.length; i++) {
                    const blob = await exportar(pendientes[i]);
                    transferencia.items.add(new File([blob], 'galeria-' + (++contador) + '.jpg', { type: 'image/jpeg' }));
                }
                inputGaleria.files = transferencia.files;
                formGaleria.submit();
            } catch (error) {
                boton.disabled = false;
                boton.textContent = 'Cargar';
                preparando = false;
                mostrarAviso(error.message);
            }
        });
    }

    // ----- Portada: una sola foto, recortada antes de guardar la experiencia -----
    function iniciarPortada() {
        const inputPortada = document.getElementById('imagen_archivo');
        const editor = document.getElementById('portada-editor');
        if (!inputPortada || !editor) return;

        const lista = document.getElementById('portada-editor-lista');
        const aviso = document.getElementById('portada-editor-aviso');
        const vista = document.getElementById('vista-imagen');
        // En edición el campo va en su propio formulario (Cargar); en alta, en el formulario principal.
        const formPortada = inputPortada.form;
        const boton = document.getElementById('boton-cargar-portada');
        let actual = null;
        let preparando = false;

        function mostrarAviso(texto) {
            aviso.textContent = texto;
            aviso.hidden = !texto;
        }

        function quitarActual() {
            if (actual) {
                URL.revokeObjectURL(actual.url);
                actual.elemento.remove();
                actual = null;
            }
            editor.hidden = true;
            if (vista) vista.hidden = false;
        }

        inputPortada.addEventListener('change', async function () {
            const archivo = inputPortada.files[0];
            if (!archivo) return;
            mostrarAviso('');
            try {
                const cargada = await cargarImagen(archivo);
                quitarActual();
                actual = crearItem(archivo, cargada, function () {
                    quitarActual();
                    inputPortada.value = ''; // sin foto: se quita también el archivo elegido
                });
                editor.hidden = false;
                if (vista) vista.hidden = true;
                lista.appendChild(actual.elemento);
                dibujar(actual);
            } catch (error) {
                mostrarAviso(error.message);
            }
        });

        window.addEventListener('resize', function () {
            if (actual) dibujar(actual);
        });

        formPortada.addEventListener('submit', async function (e) {
            if (!actual) return; // sin recorte: envío normal

            e.preventDefault();
            if (preparando) return;
            preparando = true;
            if (boton) {
                boton.disabled = true;
                boton.textContent = 'Cargando…';
            }
            mostrarAviso('Preparando la portada…');

            try {
                const blob = await exportar(actual);
                const transferencia = new DataTransfer();
                transferencia.items.add(new File([blob], 'portada.jpg', { type: 'image/jpeg' }));
                inputPortada.files = transferencia.files;
                formPortada.submit();
            } catch (error) {
                preparando = false;
                if (boton) {
                    boton.disabled = false;
                    boton.textContent = 'Cargar';
                }
                mostrarAviso(error.message);
            }
        });
    }

    iniciarGaleria();
    iniciarPortada();
})();
