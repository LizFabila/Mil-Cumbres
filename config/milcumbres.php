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
        'correo' => 'hola@milcumbres.mx',
        'telefono' => '7221234567',
    ],

    // Datos que se muestran al cliente para hacer la transferencia (C09).
    'pago' => [
        'banco' => 'BBVA',
        'titular' => 'Mil Cumbres Turismo Regenerativo',
        'clabe' => '000000000000000000',
    ],

    'imagenes' => [
        'hero_inicio' => $u('1500534314209-a25ddb2bd429', 2000),      // sierra con neblina
        'hero_catalogo' => $u('1470071459604-3b5ec3a7fe05', 2000),    // valle al amanecer
        'consultoria' => $u('1523348837708-15d4a09cfac2'),            // plántulas
        'consultoria_agrario' => $u('1416879595882-3373a0480b5b'),    // trabajo de suelo
        'consultoria_empresa' => $u('1500382017468-9049fed747ef'),    // campo al atardecer
        'modelo' => $u('1472214103451-9374bd1c798e', 2000),           // paisaje verde
        'acceso' => $u('1441974231531-c6227db76b6e'),                 // bosque (login / registro)
        'experiencia_default' => $u('1464822759023-fed622ff2c3b'),

        // Una por tipo de catálogo, para la portada.
        'tipo' => [
            'Bienestar' => $u('1545205597-3d9d02c29597', 900),
            'Natural' => $u('1444464666168-49d633b86797', 900),
            'Cultural' => $u('1518105779142-d975f22f1b0a', 900),
        ],
    ],
];
