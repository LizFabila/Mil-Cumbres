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
