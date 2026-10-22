// C07 · Celebración al enviar una solicitud: confeti y serpentinas.
// - Se dibuja en un <canvas> encima de la página, sin bloquear clics.
// - Se muestra una sola vez por folio (si recargas el acuse, no se repite).
// - Respeta "reducir movimiento" del sistema operativo.
(function () {
    const contenedor = document.querySelector('[data-celebrar]');
    if (!contenedor) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // Una vez por folio (sessionStorage puede no estar disponible: en ese caso, celebra).
    const clave = 'mc-celebrado-' + contenedor.dataset.celebrar;
    try {
        if (sessionStorage.getItem(clave)) return;
        sessionStorage.setItem(clave, '1');
    } catch (e) { /* sin almacenamiento: continúa */ }

    const COLORES = ['#1B6B57', '#2F8A6F', '#5AA0D8', '#E5B94F', '#D69A2D', '#E9785F', '#F6F2E9', '#8CC5AE'];
    const DURACION = 5200;   // ms antes de empezar a desvanecer
    const DESVANECER = 900;  // ms de desvanecido final

    const canvas = document.createElement('canvas');
    canvas.className = 'mc-celebracion';
    canvas.setAttribute('aria-hidden', 'true');
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');

    let ancho = 0;
    let alto = 0;
    const dpr = Math.min(window.devicePixelRatio || 1, 2);

    function ajustar() {
        ancho = window.innerWidth;
        alto = window.innerHeight;
        canvas.width = ancho * dpr;
        canvas.height = alto * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }
    ajustar();
    window.addEventListener('resize', ajustar);

    const azar = (min, max) => Math.random() * (max - min) + min;
    const color = () => COLORES[Math.floor(Math.random() * COLORES.length)];

    // ---------- Confeti ----------
    const confeti = [];

    function lanzarConfeti(x, y, angulo, apertura, cantidad, fuerza) {
        for (let i = 0; i < cantidad; i++) {
            const a = (angulo + azar(-apertura, apertura)) * Math.PI / 180;
            const v = azar(fuerza * 0.55, fuerza);
            confeti.push({
                x, y,
                vx: Math.cos(a) * v,
                vy: Math.sin(a) * v,
                w: azar(6, 11),
                h: azar(9, 16),
                giro: azar(0, Math.PI * 2),
                vGiro: azar(-0.25, 0.25),
                aleteo: azar(0, Math.PI * 2),
                vAleteo: azar(0.08, 0.2),
                color: color(),
                circulo: Math.random() < 0.25,
            });
        }
    }

    function lluvia(cantidad) {
        for (let i = 0; i < cantidad; i++) {
            confeti.push({
                x: azar(0, ancho), y: azar(-alto * 0.6, -10),
                vx: azar(-1, 1), vy: azar(1, 3),
                w: azar(6, 10), h: azar(8, 14),
                giro: azar(0, Math.PI * 2), vGiro: azar(-0.2, 0.2),
                aleteo: azar(0, Math.PI * 2), vAleteo: azar(0.08, 0.18),
                color: color(), circulo: Math.random() < 0.25,
            });
        }
    }

    // ---------- Serpentinas ----------
    // Cada serpentina es una "cabeza" con física que deja una estela rizada.
    const serpentinas = [];

    function lanzarSerpentinas(x, y, angulo, cantidad) {
        for (let i = 0; i < cantidad; i++) {
            const a = (angulo + azar(-14, 14)) * Math.PI / 180;
            const v = azar(0.6, 0.85) * fuerza;
            serpentinas.push({
                x, y,
                vx: Math.cos(a) * v,
                vy: Math.sin(a) * v,
                estela: [],
                largo: Math.floor(azar(26, 40)),
                fase: azar(0, Math.PI * 2),
                rizo: azar(7, 12),
                grosor: azar(4, 6),
                color: color(),
            });
        }
    }

    // Fuerza según el alto de la ventana: el punto más alto del disparo queda dentro de la pantalla.
    const fuerza = Math.min(26, Math.sqrt(alto) * 0.85);

    // Disparos iniciales: esquinas inferiores hacia el centro + lluvia superior.
    function disparar() {
        lanzarConfeti(0, alto, -60, 22, 110, fuerza);
        lanzarConfeti(ancho, alto, -120, 22, 110, fuerza);
        lanzarSerpentinas(0, alto, -62, 7);
        lanzarSerpentinas(ancho, alto, -118, 7);
    }

    disparar();
    setTimeout(() => lluvia(90), 450);
    setTimeout(() => {
        lanzarConfeti(ancho * 0.15, alto, -75, 18, 50, fuerza * 0.85);
        lanzarConfeti(ancho * 0.85, alto, -105, 18, 50, fuerza * 0.85);
    }, 900);

    // ---------- Animación ----------
    const GRAVEDAD = 0.32;
    const inicio = performance.now();

    function cuadro(ahora) {
        const t = ahora - inicio;
        const opacidad = t < DURACION ? 1 : Math.max(0, 1 - (t - DURACION) / DESVANECER);

        ctx.clearRect(0, 0, ancho, alto);
        ctx.globalAlpha = opacidad;

        // Serpentinas: estela con ondulación perpendicular al movimiento.
        for (const s of serpentinas) {
            s.vx *= 0.985;
            s.vy = s.vy * 0.985 + GRAVEDAD * 0.55;
            s.x += s.vx;
            s.y += s.vy;
            s.fase += 0.35;

            const vel = Math.hypot(s.vx, s.vy) || 1;
            const nx = -s.vy / vel;
            const ny = s.vx / vel;
            const onda = Math.sin(s.fase) * s.rizo;

            s.estela.unshift({ x: s.x + nx * onda, y: s.y + ny * onda });
            if (s.estela.length > s.largo) s.estela.pop();

            if (s.estela.length > 2) {
                ctx.beginPath();
                ctx.moveTo(s.estela[0].x, s.estela[0].y);
                for (let i = 1; i < s.estela.length - 1; i++) {
                    const mx = (s.estela[i].x + s.estela[i + 1].x) / 2;
                    const my = (s.estela[i].y + s.estela[i + 1].y) / 2;
                    ctx.quadraticCurveTo(s.estela[i].x, s.estela[i].y, mx, my);
                }
                ctx.strokeStyle = s.color;
                ctx.lineWidth = s.grosor;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.stroke();
            }
        }

        // Confeti: papelitos que giran y "aletean" (se ven de canto y de frente).
        for (const p of confeti) {
            p.vx *= 0.986;
            p.vy = p.vy * 0.986 + GRAVEDAD;
            if (p.vy > 4.2) p.vy = 4.2;            // caída suave, como papel
            p.aleteo += p.vAleteo;
            p.x += p.vx + Math.sin(p.aleteo) * 0.9;
            p.y += p.vy;
            p.giro += p.vGiro;

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.giro);
            ctx.fillStyle = p.color;
            if (p.circulo) {
                ctx.beginPath();
                ctx.arc(0, 0, p.w / 2, 0, Math.PI * 2);
                ctx.fill();
            } else {
                ctx.scale(1, Math.cos(p.aleteo));
                ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
            }
            ctx.restore();
        }

        if (opacidad > 0) {
            requestAnimationFrame(cuadro);
        } else {
            window.removeEventListener('resize', ajustar);
            canvas.remove();
        }
    }

    requestAnimationFrame(cuadro);
})();
