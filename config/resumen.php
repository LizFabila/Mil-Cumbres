<?php

/*
|--------------------------------------------------------------------------
| Umbrales del semáforo del resumen del panel administrador
|--------------------------------------------------------------------------
|
| Indicadores de conteo (menor es mejor): verde si el valor es menor o
| igual a "verde"; amarillo si es menor o igual a "amarillo"; rojo en
| otro caso.
|
| Ritmo de ingresos (mayor es mejor): porcentaje del ritmo esperado,
| calculado con lo aprobado en el mes anterior a la misma fecha.
|
| Valores provisionales: no hay metas registradas en el sistema.
| Ajustarlos según la capacidad real de atención de Mil Cumbres.
*/

return [
    'semaforo' => [
        'pendientes' => ['verde' => 2, 'amarillo' => 5],
        'pagos_por_revisar' => ['verde' => 0, 'amarillo' => 2],
        'consultorias_abiertas' => ['verde' => 2, 'amarillo' => 5],
        'ritmo' => ['verde' => 100, 'amarillo' => 70],
    ],
];
