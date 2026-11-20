// Cliente: al elegir uno existente se llenan sus datos y quedan bloqueados.
// Si se escribe el correo de un cliente registrado, se selecciona ese cliente.
(function () {
    const selector = document.getElementById('cliente_id');
    if (!selector) return;

    const clientes = window.CLIENTES || {};
    const ayuda = document.getElementById('ayuda-cliente');
    const campos = {
        nombre: document.getElementById('nuevo_nombre'),
        ap: document.getElementById('nuevo_ap'),
        am: document.getElementById('nuevo_am'),
        email: document.getElementById('nuevo_email'),
        telefono: document.getElementById('nuevo_telefono'),
    };
    const obligatorias = document.querySelectorAll('label[for="nuevo_nombre"] .req, label[for="nuevo_ap"] .req, label[for="nuevo_email"] .req');

    function aplicar(limpiar) {
        const cliente = clientes[selector.value];
        Object.keys(campos).forEach(function (k) {
            const campo = campos[k];
            if (!campo) return;
            if (cliente) {
                campo.value = cliente[k] || '';
            } else if (limpiar) {
                campo.value = '';
            }
            campo.readOnly = !!cliente;
            campo.style.background = cliente ? '#f3f7f5' : '';
        });
        obligatorias.forEach(function (r) { r.hidden = !!cliente; });
        if (ayuda) ayuda.textContent = cliente ? ayuda.dataset.existente : ayuda.dataset.nuevo;
    }

    selector.addEventListener('change', function () { aplicar(true); });

    // Correo ya registrado: elige al cliente automáticamente.
    if (campos.email) {
        campos.email.addEventListener('change', function () {
            if (selector.value) return;
            const correo = campos.email.value.trim().toLowerCase();
            const id = Object.keys(clientes).find(function (k) { return (clientes[k].email || '').toLowerCase() === correo; });
            if (id) {
                selector.value = id;
                aplicar(true);
            }
        });
    }

    aplicar(false);
})();

// Añadir reservación (admin): mismas reglas que el cliente.
// - La fecha solo permite los días en que la experiencia tiene horario, desde mañana.
// - El horario aparece según la fecha elegida.
// - El número de personas no puede pasar del cupo del horario elegido.
// - Se muestra la foto de la experiencia.
(function () {
    const contenedor = document.getElementById('items');
    const plantilla = document.getElementById('plantilla-item');
    const agregar = document.getElementById('agregar-item');
    if (!contenedor || !plantilla || !agregar) return;

    const datos = window.EXPERIENCIAS || {};
    const nombresDias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
    const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    let siguiente = contenedor.querySelectorAll('.item-fila').length;

    const hoy = new Date();
    const manana = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate() + 1);

    function leerFecha(valor) {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(valor || '')) return null;
        const [anio, mes, dia] = valor.split('-').map(Number);
        return new Date(anio, mes - 1, dia);
    }

    function iso(f) {
        return f.getFullYear() + '-' + String(f.getMonth() + 1).padStart(2, '0') + '-' + String(f.getDate()).padStart(2, '0');
    }

    function infoExp(fila) {
        const exp = fila.querySelector('.exp-item');
        return (exp && datos[exp.value]) || null;
    }

    // ---- Calendario: solo días con horario de la experiencia, desde mañana ----
    function dibujarCalendario(fila) {
        const fecha = fila.querySelector('.fecha-item');
        const pop = fila._pop;
        const info = infoExp(fila);
        const dias = info ? info.dias : [];
        if (!fila._mes) {
            const elegida = leerFecha(fecha.value);
            const base = elegida && elegida >= manana ? elegida : manana;
            fila._mes = { anio: base.getFullYear(), mes: base.getMonth() };
        }
        const { anio, mes } = fila._mes;
        const primero = new Date(anio, mes, 1);
        const ultimo = new Date(anio, mes + 1, 0).getDate();
        const desplazamiento = (primero.getDay() + 6) % 7;

        pop.replaceChildren();

        const cabecera = document.createElement('div');
        cabecera.style.cssText = 'display:flex;justify-content:space-between;align-items:center;margin-bottom:8px';
        const anterior = document.createElement('button');
        anterior.type = 'button';
        anterior.textContent = '‹';
        anterior.setAttribute('aria-label', 'Mes anterior');
        anterior.disabled = primero <= new Date(manana.getFullYear(), manana.getMonth(), 1);
        const titulo = document.createElement('strong');
        titulo.textContent = meses[mes] + ' ' + anio;
        const siguienteMes = document.createElement('button');
        siguienteMes.type = 'button';
        siguienteMes.textContent = '›';
        siguienteMes.setAttribute('aria-label', 'Mes siguiente');
        cabecera.append(anterior, titulo, siguienteMes);
        pop.appendChild(cabecera);

        anterior.addEventListener('click', function () {
            const f = new Date(anio, mes - 1, 1);
            fila._mes = { anio: f.getFullYear(), mes: f.getMonth() };
            dibujarCalendario(fila);
        });
        siguienteMes.addEventListener('click', function () {
            const f = new Date(anio, mes + 1, 1);
            fila._mes = { anio: f.getFullYear(), mes: f.getMonth() };
            dibujarCalendario(fila);
        });

        // Iniciales de los días (la semana empieza en lunes).
        const semana = document.createElement('div');
        semana.style.cssText = 'display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-bottom:4px';
        ['L', 'M', 'M', 'J', 'V', 'S', 'D'].forEach(function (inicial, k) {
            const celda = document.createElement('abbr');
            celda.textContent = inicial;
            celda.title = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'][k];
            celda.style.cssText = 'text-align:center;font-size:12px;font-weight:700;color:#5F716B;text-decoration:none;padding:2px 0';
            semana.appendChild(celda);
        });
        pop.appendChild(semana);

        const cuadricula = document.createElement('div');
        cuadricula.style.cssText = 'display:grid;grid-template-columns:repeat(7,1fr);gap:4px';
        for (let i = 0; i < desplazamiento; i++) {
            cuadricula.appendChild(document.createElement('span'));
        }
        for (let dia = 1; dia <= ultimo; dia++) {
            const f = new Date(anio, mes, dia);
            // Fuera quedan los días sin horario y los que ya tienen todos sus horarios reservados.
            const permitido = f >= manana && dias.includes(nombresDias[f.getDay()])
                && !(info.sinHorarios || []).includes(iso(f));
            const boton = document.createElement('button');
            boton.type = 'button';
            boton.textContent = String(dia);
            boton.disabled = !permitido;
            boton.style.cssText = 'padding:6px 0;border-radius:6px;border:1px solid transparent;'
                + (permitido ? 'background:#e3efe6;color:#14513f;cursor:pointer;font-weight:600;' : 'background:none;color:#b8bdb9;');
            if (fecha.value === iso(f)) {
                boton.style.borderColor = '#14513f';
            }
            if (permitido) {
                boton.addEventListener('click', function () {
                    fecha.value = iso(f);
                    pop.hidden = true;
                    fecha.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
            cuadricula.appendChild(boton);
        }
        pop.appendChild(cuadricula);

        const leyenda = document.createElement('p');
        leyenda.className = 'ayuda';
        leyenda.style.margin = '8px 0 0';
        leyenda.textContent = info ? 'Verde: día con horario libre · Gris: no disponible o ya reservado' : 'Elige primero la experiencia';
        pop.appendChild(leyenda);
    }

    function prepararFila(fila) {
        if (fila._lista) return;
        fila._lista = true;
        const fecha = fila.querySelector('.fecha-item');
        const campo = fecha.parentElement;
        campo.style.position = 'relative';

        const pop = document.createElement('div');
        pop.className = 'calendario-admin';
        pop.hidden = true;
        pop.style.cssText = 'position:absolute;z-index:30;top:100%;left:0;width:280px;background:#fff;border:1px solid #d6dcd8;border-radius:10px;padding:12px;box-shadow:0 10px 24px rgba(0,0,0,.12)';
        campo.appendChild(pop);
        fila._pop = pop;

        fecha.addEventListener('click', function () {
            if (fecha.disabled) return;
            pop.hidden = !pop.hidden;
            if (!pop.hidden) {
                fila._mes = null;
                dibujarCalendario(fila);
            }
        });
        document.addEventListener('click', function (e) {
            if (!campo.contains(e.target)) pop.hidden = true;
        });
    }

    // ---- Horarios: según la experiencia y la fecha ----
    function llenarHorarios(fila, seleccion) {
        const horario = fila.querySelector('.horario-item');
        const fecha = fila.querySelector('.fecha-item');
        const info = infoExp(fila);
        const lista = info ? info.horarios : [];
        const fechaElegida = leerFecha(fecha.value);
        const dia = fechaElegida ? nombresDias[fechaElegida.getDay()] : null;
        const previa = seleccion !== undefined ? seleccion : horario.value;
        // Sin los horarios apartados por una reservación confirmada en esa fecha.
        const apartados = info && fechaElegida ? ((info.apartados || {})[iso(fechaElegida)] || []) : [];
        const coinciden = lista.filter(h => (!dia || h.dia === dia) && !apartados.includes(Number(h.id)));

        horario.replaceChildren();
        let texto = 'Elige primero la experiencia';
        if (info) texto = dia ? (coinciden.length ? 'Elige un horario' : 'Sin horarios ese día') : 'Elige primero la fecha';
        horario.add(new Option(texto, ''));
        coinciden.forEach(h => horario.add(new Option(h.diaTexto + ' · ' + h.horario + ' (máx. ' + h.cupo + ' personas)', String(h.id))));

        if (coinciden.length === 1 && dia) {
            horario.value = String(coinciden[0].id);
        } else if (previa && Array.from(horario.options).some(o => o.value === String(previa))) {
            horario.value = String(previa);
        }
        ajustarPersonas(fila);
    }

    // ---- Personas: no más que el cupo del horario elegido ----
    function ajustarPersonas(fila) {
        const horario = fila.querySelector('.horario-item');
        const personas = fila.querySelector('.personas-item');
        const pista = fila.querySelector('.max-personas');
        const opcion = horario.selectedOptions[0];
        const info = infoExp(fila);
        const elegido = info && horario.value ? info.horarios.find(h => String(h.id) === horario.value) : null;

        if (elegido) {
            personas.max = elegido.cupo;
            if (Number(personas.value) > elegido.cupo) personas.value = elegido.cupo;
            if (pista) pista.textContent = '(máx. ' + elegido.cupo + ')';
        } else {
            personas.removeAttribute('max');
            if (pista) pista.textContent = '';
        }
        return opcion;
    }

    // ---- Experiencia: foto, fecha y horario ----
    function cambiarExperiencia(fila, limpiarFecha) {
        const exp = fila.querySelector('.exp-item');
        const fecha = fila.querySelector('.fecha-item');
        const img = fila.querySelector('.imagen-exp');
        const info = infoExp(fila);

        fecha.disabled = !info;
        if (limpiarFecha || !info) fecha.value = '';
        fila._mes = null;
        if (fila._pop) fila._pop.hidden = true;

        if (img) {
            if (info && info.imagen) {
                img.src = info.imagen;
                img.alt = info.nombre || '';
                img.hidden = false;
            } else {
                img.removeAttribute('src');
                img.hidden = true;
            }
        }
        llenarHorarios(fila, '');
        fecha.placeholder = exp.value ? 'Elige una fecha' : 'Elige primero la experiencia';
    }

    function iniciarFila(fila, primera) {
        prepararFila(fila);
        const horario = fila.querySelector('.horario-item');
        const seleccion = primera ? horario.dataset.seleccion : '';
        // Al cargar con datos previos conserva la fecha y el horario; al cambiar de experiencia, no.
        cambiarExperiencia(fila, false);
        if (seleccion) llenarHorarios(fila, seleccion);
    }

    contenedor.querySelectorAll('.item-fila').forEach(function (fila) {
        iniciarFila(fila, true);
    });

    agregar.addEventListener('click', function () {
        const html = plantilla.innerHTML.replaceAll('__I__', String(siguiente++));
        contenedor.insertAdjacentHTML('beforeend', html);
        iniciarFila(contenedor.lastElementChild, false);
    });

    contenedor.addEventListener('change', function (e) {
        const fila = e.target.closest('.item-fila');
        if (!fila) return;
        if (e.target.classList.contains('exp-item')) {
            cambiarExperiencia(fila, true);
        } else if (e.target.classList.contains('fecha-item')) {
            llenarHorarios(fila);
        } else if (e.target.classList.contains('horario-item')) {
            ajustarPersonas(fila);
        }
    });

    contenedor.addEventListener('click', function (e) {
        const boton = e.target.closest('[data-quitar]');
        if (boton && contenedor.querySelectorAll('.item-fila').length > 1) {
            boton.closest('.item-fila').remove();
        }
    });
})();
