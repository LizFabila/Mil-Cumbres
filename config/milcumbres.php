<?php

/*
|--------------------------------------------------------------------------
| Mil Cumbres
|--------------------------------------------------------------------------
| Imágenes temporales (Unsplash) mientras se consiguen las fotos originales.
| Para usar fotos propias: súbelas a public/img/ y cambia la URL por
| asset('img/archivo.jpg'), o guarda la ruta en experiencias.imagen.
*/

$u = fn (string $id, int $w = 1600) => "https://images.unsplash.com/photo-{$id}?w={$w}&q=80&auto=format&fit=crop";

return [

    'contacto' => [
        'lugar' => 'Valle de Bravo',
        'correo' => 'milcumbresmx@gmail.com',
        'telefono' => '7224686206',
        // En null se muestra un ícono atenuado en la franja de contacto (pendiente).
        // whatsapp: solo dígitos con lada, p. ej. '527221234567'.
        'whatsapp' => '527224686206',
        'facebook' => 'https://www.facebook.com/share/1HruwCK89m/',
        'instagram' => 'https://www.instagram.com/milcumbres_turismoregenerativo/',
    ],

    // Datos que se muestran al cliente para hacer la transferencia (C09).
    'pago' => [
        'banco' => 'BBVA',
        'titular' => 'Mil Cumbres Turismo Regenerativo',
        'clabe' => '000000000000000000',
    ],

    // Cartera de clientes (A09). Un cliente con este número de solicitudes o más
    // es "frecuente"; con menos, "esporádico". Mínimo 2.
    'cartera' => [
        'umbral_frecuente' => 3,
    ],

    'imagenes' => [
        // Foto propia del inicio: colocar el archivo en public/img/hero-inicio.jpg.
        // Mientras no exista, se usa la imagen de respaldo (sierra con neblina).
        // OJO: no llamar asset() aquí; al cargar la config aún no existe la petición
        // y `php artisan serve` falla. Las vistas resuelven la ruta local con asset().
        'hero_inicio' => $u('1500534314209-a25ddb2bd429', 2000),
        'hero_inicio_local' => is_file(public_path('img/hero-inicio.jpg'))
            ? 'img/hero-inicio.jpg'
            : null,
        'hero_catalogo' => $u('1470071459604-3b5ec3a7fe05', 2000),    // valle al amanecer
        'consultoria' => $u('1523348837708-15d4a09cfac2'),            // plántulas
        'consultoria_agrario' => $u('1416879595882-3373a0480b5b'),    // trabajo de suelo
        'consultoria_empresa' => $u('1500382017468-9049fed747ef'),    // campo al atardecer
        'modelo' => $u('1472214103451-9374bd1c798e', 2000),           // paisaje verde
        'acceso' => $u('1441974231531-c6227db76b6e'),                 // bosque (login / registro)
        'experiencia_default' => $u('1464822759023-fed622ff2c3b'),

        // Tarjetas de "Tu próxima aventura" (inicio). Cambia cualquier URL por
        // asset('img/archivo.jpg') cuando tengas tus propias fotos.
        'pasos' => [
            'explora' => $u('1470071459604-3b5ec3a7fe05', 900),   // valle al amanecer
            'solicita' => $u('1472214103451-9374bd1c798e', 900),  // paisaje verde (también en Nuestro modelo)
            'confirma' => $u('1545205597-3d9d02c29597', 900),     // personas en actividad al aire libre (yoga)
        ],

        // Tarjetas de "Nuestra metodología" (consultoría).
        'metodologia' => [
            'escuchar' => $u('1416879595882-3373a0480b5b', 900),  // trabajo de campo
            'disenar' => $u('1523348837708-15d4a09cfac2', 900),   // plántulas
            'acompanar' => $u('1500382017468-9049fed747ef', 900), // campo al atardecer
        ],

        // Una por tipo de catálogo, para la portada.
        'tipo' => [
            'Bienestar' => $u('1545205597-3d9d02c29597', 900),
            'Natural' => $u('1444464666168-49d633b86797', 900),
            'Cultural' => $u('1518105779142-d975f22f1b0a', 900),
        ],
    ],
];
