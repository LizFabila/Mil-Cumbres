// Revisa en vivo los requisitos de contraseña segura: <div data-requisitos="id-del-input">
// Las mismas reglas se validan en el servidor (app/Support/ReglasContrasena.php).
const reglasContrasena = {
    letra: (valor) => /[a-zA-Z]/.test(valor),
    mayuscula: (valor) => /[A-Z]/.test(valor),
    numero: (valor) => /[0-9]/.test(valor),
    especial: (valor) => /[^A-Za-z0-9]/.test(valor),
    largo: (valor) => valor.length >= 8,
};

document.querySelectorAll('[data-requisitos]').forEach(function (lista) {
    const input = document.getElementById(lista.dataset.requisitos);
    if (!input) return;

    function revisar() {
        let todasCumplen = true;

        lista.querySelectorAll('[data-regla]').forEach(function (item) {
            const cumple = reglasContrasena[item.dataset.regla](input.value);
            item.classList.toggle('cumple', cumple);
            item.classList.toggle('no-cumple', !cumple && input.value !== '');
            if (!cumple) todasCumplen = false;
        });

        input.setCustomValidity(todasCumplen ? '' : 'La contraseña no cumple con los requisitos.');
    }

    input.addEventListener('input', revisar);
    revisar();

    // Si confirman la contraseña, avisa cuando no coincide.
    const confirmacion = document.getElementById(lista.dataset.requisitos + '_confirmation');
    const avisoConfirmacion = document.getElementById('coincide-' + lista.dataset.requisitos);
    if (confirmacion && avisoConfirmacion) {
        function revisarConfirmacion() {
            const distinta = confirmacion.value !== '' && confirmacion.value !== input.value;
            // Se usa style.display porque el CSS del aviso (display: block) le gana al atributo hidden.
            avisoConfirmacion.style.display = distinta ? '' : 'none';
        }
        confirmacion.addEventListener('input', revisarConfirmacion);
        input.addEventListener('input', revisarConfirmacion);
        revisarConfirmacion();
    }

    // No deja enviar el formulario si la contraseña no cumple.
    const form = input.form;
    if (form) {
        form.addEventListener('submit', function (evento) {
            revisar();
            if (!input.checkValidity()) {
                evento.preventDefault();
                lista.classList.add('requisitos--alerta');
                input.focus();
            }
        });
    }
});
