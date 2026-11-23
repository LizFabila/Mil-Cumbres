<?php

/*
|--------------------------------------------------------------------------
| Diapositivas del carrusel del inicio (hero)
|--------------------------------------------------------------------------
| Títulos tomados del PDF «Mil Cumbres Turismo Regenerativo» (páginas 8 a 13)
| y la experiencia «Luciérnagas» del PDF «Experiencias Mil Cumbres 2026».
| Las imágenes están en public/img/hero-experiencias/.
| El orden de este arreglo es el orden en que rotan en el carrusel.
| Las frases de bienvenida no están aquí: son fijas en la vista inicio.blade.php.
*/

return [
    ['nombre' => 'Experiencias Turísticas', 'imagen' => 'img/hero-experiencias/sec-08.jpg', 'mariposas' => true],
    ['nombre' => 'Pedagogía de Naturaleza', 'imagen' => 'img/hero-experiencias/sec-10.jpg'],
    ['nombre' => 'Proyectos de Restauración y Conservación', 'imagen' => 'img/hero-experiencias/sec-11.jpg', 'sombra' => 'ligera'],
    ['nombre' => 'Del Bosque a tu Mesa', 'imagen' => 'img/hero-experiencias/sec-12.jpg'],
    ['nombre' => 'Transformación Organizacional', 'imagen' => 'img/hero-experiencias/sec-13.jpg'],
    ['nombre' => 'El cielo en la tierra: luciérnagas', 'imagen' => 'img/hero-experiencias/luciernagas.jpg'],
];
