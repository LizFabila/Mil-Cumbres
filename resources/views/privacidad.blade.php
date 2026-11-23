{{-- Aviso de privacidad integral · Mil Cumbres
     Los datos marcados en rojo (.aviso-pendiente) se deben sustituir
     por la información real de la persona dueña del sistema.
--}}

@extends('layouts.app')

@section('titulo', 'Aviso de privacidad')

@section('descripcion',
    'Aviso de privacidad integral de Mil Cumbres · Turismo Regenerativo.'
)

@push('estilos')
<style>
    .aviso { background:#F6F2E9; color:var(--tema-oscuro, #143D36); font-family:"Public Sans",system-ui,sans-serif; padding:64px 0 96px; }
    .aviso__hoja { max-width:820px; margin:0 auto; padding:56px 64px; background:#fff; border:1px solid #D9E1D8; }
    .aviso h1 { font-family:"Fraunces",Georgia,serif; font-weight:500; font-size:38px; line-height:1.15; margin:0 0 6px; }
    /* Flecha para regresar a la página anterior (esquina derecha, siempre visible) */
    .aviso__barra { position:sticky; top:172px; z-index:5; display:flex; justify-content:flex-end; align-items:flex-start; height:0; }
    .aviso__regresar { display:inline-flex; align-items:center; gap:8px; margin-top:-28px; margin-right:-36px; padding:10px 16px; background:var(--tema-oscuro, #143D36); color:#fff; font-size:14px; font-weight:600; text-decoration:none; box-shadow:0 6px 18px var(--tm-rgba-20-61-54-p18, rgba(20,61,54,.18)); }
    .aviso__regresar:hover { background:var(--tema-principal-claro, #1B6B57); }
    .aviso__regresar:focus-visible { outline:3px solid var(--tm-5aa0d8, #5AA0D8); outline-offset:3px; }
    .aviso__regresar span[aria-hidden] { font-size:18px; line-height:1; }
    .aviso__sub { margin:0 0 4px; font-size:13px; font-weight:700; letter-spacing:1.5px; color:var(--tema-principal-claro, #1B6B57); text-transform:uppercase; }
    .aviso__fecha { margin:18px 0 0; font-size:14px; color:var(--tm-5f716b, #5F716B); }
    .aviso__indice { margin:32px 0 8px; padding:20px 24px; background:#F7F9F5; border:1px solid #DCE5DB; }
    .aviso__indice strong { display:block; margin-bottom:10px; font-size:13px; letter-spacing:1px; text-transform:uppercase; }
    .aviso__indice ol { margin:0; padding-left:22px; columns:2; column-gap:32px; font-size:14px; line-height:1.9; }
    .aviso__indice a { color:var(--tema-principal-claro, #1B6B57); text-decoration:none; }
    .aviso__indice a:hover { text-decoration:underline; }
    .aviso section { padding-top:34px; scroll-margin-top:150px; }
    .aviso h2 { font-family:"Fraunces",Georgia,serif; font-weight:500; font-size:23px; margin:0 0 12px; padding-bottom:8px; border-bottom:1px solid #D9E1D8; }
    .aviso h3 { font-size:15px; font-weight:700; margin:20px 0 6px; }
    .aviso p, .aviso li { font-size:15.5px; line-height:1.75; color:var(--tm-3f504a, #3F504A); }
    .aviso p { margin:0 0 12px; }
    /* Texto justificado; la división silábica evita huecos grandes entre palabras */
    .aviso section > p, .aviso section li { text-align:justify; hyphens:auto; -webkit-hyphens:auto; }
    .aviso ul, .aviso ol.aviso__lista { margin:0 0 14px; padding-left:22px; }
    .aviso li { margin-bottom:4px; }
    .aviso strong { color:var(--tema-oscuro, #143D36); }
    .aviso a.aviso__correo { color:var(--tema-principal-claro, #1B6B57); font-weight:600; }

    /* Datos pendientes: información de la persona dueña del sistema */
    .aviso-pendiente { display:inline; padding:1px 6px; border:1px dashed #C0392B; border-radius:4px; background:#FDECEA; color:#B42318; font-weight:600; hyphens:none; -webkit-hyphens:none; }
    .aviso-pendiente-bloque { margin:4px 0 16px; padding:14px 18px; border:1px dashed #C0392B; border-left:4px solid #C0392B; background:#FDECEA; color:#7A1E14; font-size:14.5px; line-height:1.7; }
    .aviso-pendiente-bloque strong { color:#B42318; display:block; margin-bottom:4px; font-size:12px; letter-spacing:1px; text-transform:uppercase; }
    .aviso-pendiente-bloque dl { margin:0; display:grid; grid-template-columns:max-content 1fr; gap:4px 14px; }
    .aviso-pendiente-bloque dt { font-weight:700; }
    .aviso-pendiente-bloque dd { margin:0; }

    @media (max-width:700px) {
        .aviso { padding:24px 0 56px; }
        .aviso__hoja { padding:30px 20px; border-left:0; border-right:0; }
        .aviso h1 { font-size:30px; }
        .aviso__barra { top:140px; }
        .aviso__regresar { margin-top:-14px; margin-right:-8px; padding:9px 13px; font-size:13px; }
        .aviso__indice ol { columns:1; }
        .aviso-pendiente-bloque dl { grid-template-columns:1fr; gap:0; }
        .aviso-pendiente-bloque dd { margin-bottom:6px; }
    }
</style>
@endpush

@section('contenido')
@php
    $correo = config('milcumbres.contacto.correo');
    // Página desde la que se llegó al aviso; si no hay (o es el mismo aviso), se regresa al inicio.
    $anterior = url()->previous();
    if ($anterior === url()->current() || ! str_starts_with($anterior, url('/'))) {
        $anterior = route('inicio');
    }
@endphp

<div class="aviso">
<article class="aviso__hoja">

    <div class="aviso__barra">
        <a href="{{ $anterior }}" class="aviso__regresar" id="aviso-regresar" aria-label="Regresar a la página anterior">
            <span aria-hidden="true">←</span> Regresar
        </a>
    </div>

    <p class="aviso__sub">Mil Cumbres · Turismo Regenerativo</p>
    <h1>Aviso de privacidad integral</h1>
    <p class="aviso__fecha">Última actualización: <span class="aviso-pendiente">[día] de [mes] de [año]</span></p>

    <nav class="aviso__indice" aria-label="Secciones del aviso de privacidad">
        <strong>Contenido</strong>
        <ol>
            <li><a href="#responsable">Responsable de tus datos</a></li>
            <li><a href="#datos">Datos que recabamos</a></li>
            <li><a href="#finalidades">Para qué usamos tus datos</a></li>
            <li><a href="#transferencias">Con quién compartimos tus datos</a></li>
            <li><a href="#arco">Derechos ARCO</a></li>
            <li><a href="#revocacion">Revocar el consentimiento o limitar el uso</a></li>
            <li><a href="#cookies">Cookies y tecnologías similares</a></li>
            <li><a href="#seguridad">Seguridad y conservación</a></li>
            <li><a href="#cambios">Cambios a este aviso</a></li>
            <li><a href="#consentimiento">Consentimiento</a></li>
        </ol>
    </nav>

    <section id="responsable">
        <h2>1. Responsable de tus datos</h2>
        <p>
            <span class="aviso-pendiente">[Nombre completo o razón social del responsable]</span>,
            con Registro Federal de Contribuyentes <span class="aviso-pendiente">[RFC]</span>,
            quien opera con el nombre comercial <strong>Mil Cumbres · Turismo Regenerativo</strong>,
            con domicilio en <span class="aviso-pendiente">[calle y número]</span>,
            colonia <span class="aviso-pendiente">[colonia]</span>,
            C.P. <span class="aviso-pendiente">[código postal]</span>,
            Valle de Bravo, Estado de México, es responsable del tratamiento de los datos personales
            que nos proporcionas, conforme a la Ley Federal de Protección de Datos Personales en
            Posesión de los Particulares vigente.
        </p>
        <div class="aviso-pendiente-bloque" role="note">
            <strong>Datos pendientes de la persona dueña del sistema</strong>
            <dl>
                <dt>Nombre o razón social:</dt><dd>Nombre completo si es persona física, o razón social si es empresa.</dd>
                <dt>RFC:</dt><dd>Registro Federal de Contribuyentes del responsable.</dd>
                <dt>Domicilio:</dt><dd>Calle, número, colonia y código postal para recibir notificaciones.</dd>
            </dl>
        </div>
    </section>

    <section id="datos">
        <h2>2. Datos que recabamos</h2>
        <p>Según cómo uses el sitio, podemos recabar los siguientes datos personales:</p>
        <ul>
            <li><strong>Identificación y contacto:</strong> nombre, apellidos, correo electrónico y teléfono.</li>
            <li><strong>De tu cuenta:</strong> contraseña, que se guarda cifrada y que nadie en Mil Cumbres puede leer.</li>
            <li><strong>De tus solicitudes:</strong> experiencias elegidas, fechas deseadas, número de personas y las notas que decidas escribir.</li>
            <li><strong>De consultorías:</strong> nombre y tipo de la organización que representas, su ubicación y la descripción de lo que necesitas.</li>
            <li><strong>Financieros:</strong> referencia de la transferencia y comprobante de pago, que puede mostrar tu nombre, banco y parte de tu número de cuenta.</li>
            <li><strong>De opinión:</strong> calificaciones y comentarios sobre las experiencias.</li>
            <li><strong>Técnicos:</strong> dirección IP y tipo de navegador, que se registran al iniciar sesión.</li>
        </ul>
        <p>
            Mil Cumbres no solicita datos personales sensibles. Te pedimos no incluirlos en las notas
            o comentarios, por ejemplo información de salud. Si una experiencia llegara a requerir
            alguno, te lo pediremos por separado y con tu consentimiento expreso y por escrito.
        </p>
    </section>

    <section id="finalidades">
        <h2>3. Para qué usamos tus datos</h2>
        <h3>Finalidades necesarias para darte el servicio</h3>
        <ul>
            <li>Crear y administrar tu cuenta, verificar tu correo y ayudarte a recuperar tu contraseña.</li>
            <li>Recibir, gestionar y confirmar tus solicitudes de experiencias y reservaciones.</li>
            <li>Validar los pagos que registras.</li>
            <li>Atender y dar seguimiento a tus solicitudes de consultoría.</li>
            <li>Comunicarnos contigo sobre cambios, cancelaciones o avisos de tus reservaciones.</li>
        </ul>
        <h3>Finalidades secundarias, que no son necesarias para el servicio</h3>
        <ul>
            <li>Publicar en el sitio tu calificación y comentario, junto con tu nombre de pila, solo si marcas la casilla que lo autoriza.</li>
            <li>Pedirte tu opinión para mejorar nuestras experiencias.</li>
        </ul>
        <p>
            Si no quieres que usemos tus datos para las finalidades secundarias, no marques la casilla
            correspondiente o escríbenos en cualquier momento a
            <a class="aviso__correo" href="mailto:{{ $correo }}">{{ $correo }}</a>
            con el asunto "Finalidades secundarias". Negarte no afecta los servicios que nos solicitas.
        </p>
    </section>

    <section id="transferencias">
        <h2>4. Con quién compartimos tus datos</h2>
        <p>
            Mil Cumbres no transfiere tus datos personales a terceros, salvo en los casos que la ley
            permite sin tu consentimiento, como el requerimiento de una autoridad competente.
        </p>
        <p>
            Para operar el sitio usamos proveedores de alojamiento web y de correo electrónico, que
            tratan los datos solo por cuenta y bajo instrucciones de Mil Cumbres.
        </p>
    </section>

    <section id="arco">
        <h2>5. Derechos ARCO</h2>
        <p>
            Tienes derecho a conocer qué datos tenemos de ti (<strong>Acceso</strong>), pedir que se
            corrijan si están mal (<strong>Rectificación</strong>), que se eliminen
            (<strong>Cancelación</strong>) u oponerte a un uso específico (<strong>Oposición</strong>).
        </p>
        <p>
            Para ejercerlos, envía un correo a
            <a class="aviso__correo" href="mailto:{{ $correo }}">{{ $correo }}</a>
            con el asunto "Derechos ARCO" e incluye:
        </p>
        <ol class="aviso__lista">
            <li>Tu nombre completo y el correo con el que te registraste.</li>
            <li>Copia de una identificación oficial vigente o, en su caso, del documento que acredite a tu representante.</li>
            <li>Qué derecho quieres ejercer y sobre qué datos.</li>
            <li>Si pides una rectificación, el dato correcto y, si hace falta, un documento que lo respalde.</li>
        </ol>
        <p>
            Te responderemos en un plazo máximo de <strong>20 días hábiles</strong> a partir de que
            recibamos tu solicitud. Si procede, el cambio se hará efectivo dentro de los
            <strong>15 días hábiles</strong> siguientes a la respuesta.
        </p>
        <p>
            La persona encargada de atender estas solicitudes es
            <span class="aviso-pendiente">[nombre y cargo del responsable de datos personales]</span>.
        </p>
    </section>

    <section id="revocacion">
        <h2>6. Revocar el consentimiento o limitar el uso</h2>
        <p>
            Puedes revocar el consentimiento que nos diste o pedir que limitemos el uso o la
            divulgación de tus datos con el mismo procedimiento de la sección 5. Si revocas el
            consentimiento para una finalidad necesaria, es posible que no podamos seguir atendiendo
            tus reservaciones.
        </p>
    </section>

    <section id="cookies">
        <h2>7. Cookies y tecnologías similares</h2>
        <p>
            Este sitio usa una cookie de sesión, indispensable para mantener tu sesión iniciada, y
            registra tu dirección IP y tu navegador por seguridad. También carga tipografías de
            Google Fonts e imágenes de Unsplash, servicios que reciben tu dirección IP al mostrar la
            página. No usamos cookies de publicidad ni de seguimiento.
        </p>
        <p>
            Al entrar al sitio te mostramos un aviso sobre el uso de cookies. Cuando lo cierras,
            guardamos la cookie <strong>mc_aviso_cookies</strong> durante un año, solo para no
            volver a mostrártelo.
        </p>
        <p>
            Puedes borrar o bloquear las cookies desde la configuración de tu navegador, aunque sin la
            cookie de sesión no podrás iniciar sesión.
        </p>
    </section>

    <section id="seguridad">
        <h2>8. Seguridad y conservación</h2>
        <p>
            Las contraseñas se guardan cifradas, los comprobantes de pago solo los consulta el
            personal administrativo y el acceso al panel requiere usuario y contraseña. Conservamos
            tus datos mientras tengas una cuenta activa o mientras lo exija la ley.
        </p>
    </section>

    <section id="cambios">
        <h2>9. Cambios a este aviso</h2>
        <p>
            Este aviso puede cambiar por nuevas disposiciones legales o por cambios en nuestros
            servicios. Publicaremos cualquier cambio en esta misma página con su fecha de última
            actualización y, si el cambio es importante, también te avisaremos al correo con el que
            te registraste.
        </p>
    </section>

    <section id="consentimiento">
        <h2>10. Consentimiento</h2>
        <p>
            Al marcar la casilla "He leído y acepto el aviso de privacidad" al registrarte, consientes
            el tratamiento de tus datos para las finalidades necesarias. Para tus datos financieros te
            pediremos tu consentimiento expreso en la pantalla de pago.
        </p>
        <p>
            Si tienes dudas sobre este aviso, escríbenos a
            <a class="aviso__correo" href="mailto:{{ $correo }}">{{ $correo }}</a>.
        </p>
    </section>

</article>
</div>

@endsection

@push('scripts')
<script>
    // Si se llegó desde otra página del sitio, regresa con el historial del navegador:
    // así vuelve exactamente al punto donde estaba la persona (y conserva lo que llevaba escrito).
    document.getElementById('aviso-regresar').addEventListener('click', function (e) {
        var mismoSitio = document.referrer && document.referrer.indexOf(location.origin) === 0;
        if (mismoSitio && history.length > 1) {
            e.preventDefault();
            history.back();
        }
    });
</script>
@endpush
