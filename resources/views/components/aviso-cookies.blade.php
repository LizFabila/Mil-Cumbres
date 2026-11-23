{{-- Aviso de cookies · Mil Cumbres
     Informa que el sitio usa una cookie de sesión (indispensable) y enlaza
     a la sección de cookies del aviso de privacidad. No hay cookies de
     publicidad, por eso solo se ofrece "Entendido" y no "Rechazar".
     Al cerrarlo se guarda la cookie mc_aviso_cookies por un año para no
     volver a mostrarlo. Se revisa con JavaScript porque Laravel cifra las
     cookies que lee del servidor y esta la escribe el navegador.
--}}

<div class="mc-cookies" id="mc-cookies" role="region" aria-label="Aviso de cookies" hidden>
    <p class="mc-cookies__texto">
        <strong>Usamos cookies.</strong>
        Este sitio usa una cookie de sesión, indispensable para que puedas iniciar sesión
        y hacer tus reservaciones. No usamos cookies de publicidad.
        <a href="{{ route('privacidad') }}#cookies">Más información en el aviso de privacidad</a>.
    </p>
    <button type="button" class="mc-cookies__boton" id="mc-cookies-ok">Entendido</button>
</div>

<style>
    .mc-cookies {
        position: fixed;
        left: 16px;
        right: 16px;
        bottom: 16px;
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 20px;
        max-width: 980px;
        margin: 0 auto;
        padding: 18px 22px;
        background: var(--tema-oscuro, #143D36);
        color: #fff;
        box-shadow: 0 10px 30px var(--tm-rgba-20-61-54-p25, rgba(20, 61, 54, .25));
        font-family: 'Public Sans', system-ui, sans-serif;
    }
    .mc-cookies[hidden] { display: none; }
    .mc-cookies__texto { margin: 0; font-size: 14px; line-height: 1.6; }
    .mc-cookies__texto a { color: var(--tm-a8d5c4, #A8D5C4); font-weight: 600; text-decoration: underline; }
    .mc-cookies__boton {
        flex-shrink: 0;
        padding: 11px 22px;
        border: 0;
        background: #fff;
        color: var(--tema-oscuro, #143D36);
        font: 700 14px 'Public Sans', system-ui, sans-serif;
        cursor: pointer;
    }
    .mc-cookies__boton:hover { background: #E7F0EC; }
    .mc-cookies__boton:focus-visible { outline: 3px solid var(--tm-5aa0d8, #5AA0D8); outline-offset: 3px; }
    @media (max-width: 640px) {
        .mc-cookies { flex-direction: column; align-items: stretch; gap: 14px; left: 10px; right: 10px; bottom: 10px; }
        .mc-cookies__boton { width: 100%; }
    }
</style>

<script>
    (function () {
        var banner = document.getElementById('mc-cookies');
        if (!banner) return;

        if (document.cookie.split('; ').indexOf('mc_aviso_cookies=1') === -1) {
            banner.hidden = false;
        }

        document.getElementById('mc-cookies-ok').addEventListener('click', function () {
            document.cookie = 'mc_aviso_cookies=1; max-age=31536000; path=/; SameSite=Lax';
            banner.hidden = true;
        });
    })();
</script>
