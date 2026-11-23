{{-- Contacto directo: franja superior derecha del encabezado.
     Datos en config/milcumbres.php → contacto. Si una red no tiene dato,
     se muestra un ícono atenuado (marcador pendiente), no un enlace roto. --}}

@php
    $contacto = config('milcumbres.contacto');

    $telefono = $contacto['telefono'] ?? null;
    $telefonoTexto = $telefono
        ? preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1 $2 $3', $telefono)
        : null;

    $whatsapp = $contacto['whatsapp'] ?? null;
    $whatsappUrl = $whatsapp ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : null;
@endphp

<nav class="mc-contacto" aria-label="Contacto directo">

    @if ($telefono)
        <a class="mc-contacto__enlace mc-contacto__enlace--texto" href="tel:+52{{ $telefono }}">
            <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z" fill="currentColor"/>
            </svg>
            <span>{{ $telefonoTexto }}</span>
        </a>
    @endif

    @if ($whatsappUrl)
        <a class="mc-contacto__enlace" href="{{ $whatsappUrl }}" target="_blank" rel="noopener" aria-label="Escribir por WhatsApp">
            <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M9 8.8c.2-.4.5-.4.7-.4h.4c.2 0 .4.1.5.4l.6 1.4c.1.2 0 .4-.1.6l-.4.5c.5 1 1.3 1.8 2.3 2.3l.5-.4c.2-.1.4-.2.6-.1l1.4.6c.3.1.4.3.4.5v.4c0 .2 0 .5-.4.7-.5.3-1.2.5-2 .2-1.6-.6-3-2-3.6-3.6-.3-.9-.1-1.6.2-2.1z" fill="currentColor"/>
            </svg>
            <span class="mc-contacto__texto">WhatsApp</span>
        </a>
    @else
        <span class="mc-contacto__enlace mc-contacto__enlace--pendiente" title="Pendiente: falta el número de WhatsApp en config/milcumbres.php" role="img" aria-label="WhatsApp (pendiente de configurar)">
            <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
        </span>
    @endif

    @foreach ([
        'facebook' => ['etiqueta' => 'Facebook', 'icono' => 'M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z'],
        'instagram' => ['etiqueta' => 'Instagram', 'icono' => null],
        'tiktok' => ['etiqueta' => 'TikTok', 'icono' => 'M12.53.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z'],
        'linkedin' => ['etiqueta' => 'LinkedIn', 'icono' => 'M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 4.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z'],
    ] as $clave => $red)

        @if (! empty($contacto[$clave]))
            <a class="mc-contacto__enlace" href="{{ $contacto[$clave] }}" target="_blank" rel="noopener" aria-label="{{ $red['etiqueta'] }}">
                @if ($red['icono'])
                    <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="{{ $red['icono'] }}" fill="currentColor"/>
                    </svg>
                @else
                    <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="17.3" cy="6.7" r="1.2" fill="currentColor"/>
                    </svg>
                @endif
            </a>
        @else
            <span class="mc-contacto__enlace mc-contacto__enlace--pendiente" title="Pendiente: falta la URL de {{ $red['etiqueta'] }} en config/milcumbres.php" role="img" aria-label="{{ $red['etiqueta'] }} (pendiente de configurar)">
                <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    @if ($red['icono'])
                        <path d="{{ $red['icono'] }}" fill="currentColor"/>
                    @else
                        <rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/>
                    @endif
                </svg>
            </span>
        @endif

    @endforeach

    @if (! empty($contacto['correo']))
        <a class="mc-contacto__enlace mc-contacto__enlace--texto" href="mailto:{{ $contacto['correo'] }}">
            <svg class="mc-contacto__icono" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/>
                <path d="M3.5 7l8.5 6 8.5-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
            </svg>
            <span>{{ $contacto['correo'] }}</span>
        </a>
    @endif

</nav>
