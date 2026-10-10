// A07 · Agregar / quitar filas de horario y vista previa de imagen.
(function () {
    const contenedor = document.getElementById('horarios');
    const plantilla = document.getElementById('plantilla-horario');
    const agregar = document.getElementById('agregar-horario');
    let siguiente = contenedor ? contenedor.children.length : 0;

    if (agregar) {
        agregar.addEventListener('click', function () {
            const html = plantilla.innerHTML.replaceAll('__I__', 'n' + siguiente++);
            contenedor.insertAdjacentHTML('beforeend', html);
        });
    }

    if (contenedor) {
        contenedor.addEventListener('click', function (e) {
            const boton = e.target.closest('[data-quitar]');
            if (boton) boton.closest('.horario-fila').remove();
        });
    }

    const vista = document.getElementById('vista-imagen');
    const archivo = document.getElementById('imagen_archivo');
    const url = document.getElementById('imagen_url');

    if (archivo) {
        archivo.addEventListener('change', function () {
            if (archivo.files[0]) vista.src = URL.createObjectURL(archivo.files[0]);
        });
    }
    if (url) {
        url.addEventListener('change', function () {
            if (url.value) vista.src = url.value;
        });
    }
})();
