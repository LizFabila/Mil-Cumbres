<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use App\Models\Pago;
use App\Models\Solicitud;
use App\Models\SolicitudConsultoria;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResumenController extends Controller
{
    /** Lienzo de la gráfica de línea (viewBox). */
    private const LINEA_ANCHO = 600;
    private const LINEA_ALTO = 200;

    /** Centro y radio del pastel dentro de su viewBox. */
    private const PASTEL_CENTRO = 100;
    private const PASTEL_RADIO = 90;

    /**
     * Colores de las porciones: [fondo, texto]. No son semáforo,
     * así que no usan rojo, amarillo ni verde.
     */
    private const PASTEL_COLORES = [
        ['#12A4B5', '#FFFFFF'],
        ['#7B61D6', '#FFFFFF'],
        ['#F2863A', '#143D36'],
        ['#3D7FE0', '#FFFFFF'],
    ];

    /** A08 · Resumen */
    public function index(): View
    {
        $inicioMes = now()->startOfMonth();
        $umbrales = config('resumen.semaforo');

        $pendientes = Solicitud::where('estado', 'pendiente')->count();
        $pagosPorRevisar = Pago::where('estado', 'pendiente')->count();
        $consultoriasAbiertas = SolicitudConsultoria::whereIn('estado', ['recibida', 'en_proceso'])->count();

        // Ritmo: ingresos del mes frente a lo que se esperaría según el mes anterior.
        $ingresosMes = $this->ingresosEntre($inicioMes, today()->endOfDay());

        $mesAnterior = now()->subMonthNoOverflow();
        $ingresosMesAnterior = $this->ingresosEntre(
            $mesAnterior->copy()->startOfMonth(),
            $mesAnterior->copy()->endOfMonth(),
        );
        $ritmoEsperado = $ingresosMesAnterior
            * min(now()->day, $mesAnterior->daysInMonth) / $mesAnterior->daysInMonth;
        $ritmo = $ritmoEsperado > 0 ? (int) round($ingresosMes / $ritmoEsperado * 100) : null;

        $kpis = [
            'confirmadas_mes' => Solicitud::where('estado', 'confirmada')
                ->where('updated_at', '>=', $inicioMes)->count(),
            'personas_proximas' => (int) Solicitud::where('estado', 'confirmada')
                ->whereBetween('fecha_deseada', [today(), today()->addDays(30)])->sum('cant_persona'),
        ];

        $semaforos = [
            $this->tarjetaConteo(
                'Solicitudes pendientes',
                $pendientes,
                $umbrales['pendientes'],
                'Solicitudes que requieren revisión.',
                route('admin.solicitudes.index', ['estado' => 'pendiente']),
            ),
            $this->tarjetaConteo(
                'Pagos por revisar',
                $pagosPorRevisar,
                $umbrales['pagos_por_revisar'],
                'Comprobantes que necesitan validación.',
                route('admin.pagos.index'),
            ),
            $this->tarjetaConteo(
                'Consultorías abiertas',
                $consultoriasAbiertas,
                $umbrales['consultorias_abiertas'],
                'Solicitudes de acompañamiento sin cerrar.',
                route('admin.consultorias.index'),
            ),
            $this->tarjetaRitmo($ingresosMes, $ritmo, $umbrales['ritmo']),
        ];

        // Tendencia: línea de ingresos aprobados de los últimos seis meses.
        $meses = collect(range(5, 0))->map(function (int $hace) {
            $mes = now()->subMonthsNoOverflow($hace);

            return [
                'etiqueta' => ucfirst($mes->translatedFormat('M Y')),
                'valor' => $this->ingresosEntre($mes->copy()->startOfMonth(), $mes->copy()->endOfMonth()),
                'en_curso' => $hace === 0,
            ];
        });

        $tope = $this->tope((float) $meses->max('valor'));
        $ingresosPorMes = $this->conPuntos($meses, 'valor', $tope);
        $trazoIngresos = $ingresosPorMes
            ->map(fn (array $mes) => $mes['x'].','.$mes['y'])
            ->implode(' ');
        $ejeIngresos = collect(range(0, 4))->map(fn (int $k) => [
            'etiqueta' => number_format($tope * $k / 4),
            'posicion' => round($this->alturaY($tope * $k / 4, $tope) / self::LINEA_ALTO * 100, 2),
        ]);

        $lineaViewBox = sprintf('0 0 %d %d', self::LINEA_ANCHO, self::LINEA_ALTO);

        // Comparación: visitantes confirmados por periodo dentro de los próximos 30 días.
        $visitantesPorPeriodo = $this->conAncho(
            collect([[0, 6], [7, 13], [14, 21], [22, 30]])->map(function (array $rango) {
                $desde = today()->addDays($rango[0]);
                $hasta = today()->addDays($rango[1]);

                return [
                    'etiqueta' => $desde->translatedFormat('j M').' al '.$hasta->translatedFormat('j M'),
                    'valor' => (int) Solicitud::where('estado', 'confirmada')
                        ->whereBetween('fecha_deseada', [$desde, $hasta])
                        ->sum('cant_persona'),
                ];
            }),
            'valor',
        );

        // Composición: un pastel 3D por grupo con el reparto de sus estados.
        $flujo = collect([
            'Solicitudes' => $this->conteos(Solicitud::class, Solicitud::ESTADOS),
            'Pagos' => $this->conteos(Pago::class, Pago::ESTADOS),
            'Consultorías' => $this->conteos(SolicitudConsultoria::class, SolicitudConsultoria::ESTADOS),
        ])->map(fn (Collection $estados) => $this->pastel($estados));

        // Experiencias más solicitadas (solicitudes no canceladas).
        $top = Experiencia::query()
            ->select('experiencias.id_experiencia', 'experiencias.nombre')
            ->selectRaw('COUNT(s.id_solicitud) as solicitudes, COALESCE(SUM(s.cant_persona),0) as personas')
            ->join('asigna_experiencia as ae', fn ($j) => $j->on('ae.id_experiencia', '=', 'experiencias.id_experiencia')->whereNull('ae.deleted_at'))
            ->join('solicitudes as s', fn ($j) => $j->on('s.id_solicitud', '=', 'ae.id_solicitud')->whereNull('s.deleted_at')->where('s.estado', '!=', 'cancelada'))
            ->groupBy('experiencias.id_experiencia', 'experiencias.nombre')
            ->orderByDesc('solicitudes')
            ->take(5)
            ->get();

        $proximas = Solicitud::with(['persona', 'experiencias'])
            ->where('estado', 'confirmada')
            ->whereDate('fecha_deseada', '>=', today())
            ->orderBy('fecha_deseada')
            ->take(6)
            ->get();

        return view('Dashboard.resumen', compact(
            'kpis',
            'semaforos',
            'ingresosPorMes',
            'trazoIngresos',
            'ejeIngresos',
            'lineaViewBox',
            'visitantesPorPeriodo',
            'flujo',
            'top',
            'proximas',
        ));
    }

    private function ingresosEntre(Carbon $desde, Carbon $hasta): float
    {
        return (float) Solicitud::whereHas('pagos', fn ($q) => $q
            ->where('estado', 'aprobado')
            ->whereBetween('fecha', [$desde, $hasta]))
            ->sum('total');
    }

    /** Conteo por estado con porcentaje sobre el grupo. */
    private function conteos(string $modelo, array $estados): Collection
    {
        $totales = $modelo::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $suma = max(1, (int) $totales->sum());

        return collect($estados)->map(function (string $estado) use ($totales, $suma) {
            $total = (int) ($totales[$estado] ?? 0);

            return [
                'estado' => $estado,
                'total' => $total,
                'porcentaje' => (int) round($total / $suma * 100),
            ];
        });
    }

    private function conAncho(Collection $items, string $campo): Collection
    {
        $maximo = max(1, (float) $items->max($campo));

        return $items->map(fn (array $item) => $item + [
            'ancho' => (int) round($item[$campo] / $maximo * 100),
        ]);
    }

    /** Tope del eje vertical: múltiplo de 4 pasos "redondos" que cubre el máximo. */
    private function tope(float $maximo): float
    {
        $bruto = max(1, $maximo) / 4;
        $potencia = 10 ** floor(log10($bruto));

        $paso = collect([1, 2, 2.5, 5, 10])
            ->map(fn (float $f) => $f * $potencia)
            ->first(fn (float $p) => $p >= $bruto);

        return $paso * 4;
    }

    /** Altura en el viewBox de la línea para un valor dentro del tope. */
    private function alturaY(float $valor, float $tope): float
    {
        return self::LINEA_ALTO - 10 - $valor / $tope * (self::LINEA_ALTO - 20);
    }

    /** Coordenadas de cada punto de la línea, centradas en su columna. */
    private function conPuntos(Collection $items, string $campo, float $tope): Collection
    {
        $total = max(1, $items->count());

        return $items->values()->map(fn (array $item, int $i) => $item + [
            'x' => round(($i + 0.5) / $total * self::LINEA_ANCHO, 1),
            'y' => round($this->alturaY((float) $item[$campo], $tope), 1),
        ]);
    }

    /** Reparto de un grupo en porciones de un pastel 3D (SVG), con sus posiciones calculadas aquí. */
    private function pastel(Collection $estados): array
    {
        $total = (int) $estados->sum('total');
        $centro = self::PASTEL_CENTRO;
        $radio = self::PASTEL_RADIO;
        $angulo = -M_PI / 2;

        $porciones = $estados->values()->map(function (array $item, int $i) use ($total, $centro, $radio, &$angulo) {
            [$color, $texto] = self::PASTEL_COLORES[$i % count(self::PASTEL_COLORES)];

            $fraccion = $total > 0 ? $item['total'] / $total : 0;
            $inicio = $angulo;
            $fin = $angulo + $fraccion * 2 * M_PI;
            $angulo = $fin;
            $medio = ($inicio + $fin) / 2;

            return [
                'estado' => $item['estado'],
                'total' => $item['total'],
                'porcentaje' => $item['porcentaje'],
                'color' => $color,
                'texto' => $texto,
                'd' => $this->arco($centro, $centro, $radio, $inicio, $fin),
                // Desplazamiento al pasar el cursor: sale en la dirección de su porción.
                'dx' => round(6 * cos($medio), 2),
                'dy' => round(6 * sin($medio), 2),
                // Etiqueta dentro de la porción, solo si cabe (7% o más).
                'lx' => round($centro + 0.62 * $radio * cos($medio), 2),
                'ly' => round($centro + 0.62 * $radio * sin($medio), 2),
                'etiqueta' => $fraccion >= 0.07 ? $item['porcentaje'].'%' : null,
            ];
        });

        return [
            'total' => $total,
            'porciones' => $porciones,
        ];
    }

    /** Trayectoria SVG de un sector de círculo; el círculo completo necesita dos arcos. */
    private function arco(float $cx, float $cy, float $r, float $inicio, float $fin): ?string
    {
        $abarca = $fin - $inicio;

        if ($abarca <= 0) {
            return null;
        }

        if ($abarca >= 2 * M_PI - 1e-6) {
            return sprintf(
                'M %1$s %2$s A %3$s %3$s 0 1 1 %4$s %5$s A %3$s %3$s 0 1 1 %1$s %2$s Z',
                $cx,
                round($cy - $r, 2),
                $r,
                $cx,
                round($cy + $r, 2),
            );
        }

        return sprintf(
            'M %s %s L %s %s A %s %s 0 %d 1 %s %s Z',
            $cx,
            $cy,
            round($cx + $r * cos($inicio), 2),
            round($cy + $r * sin($inicio), 2),
            $r,
            $r,
            $abarca > M_PI ? 1 : 0,
            round($cx + $r * cos($fin), 2),
            round($cy + $r * sin($fin), 2),
        );
    }

    /** Semáforo de conteo: menor es mejor. */
    private function tarjetaConteo(string $titulo, int $valor, array $umbral, string $ayuda, string $ruta): array
    {
        $tono = match (true) {
            $valor <= $umbral['verde'] => 'verde',
            $valor <= $umbral['amarillo'] => 'amarillo',
            default => 'rojo',
        };

        return $this->tarjeta($titulo, (string) $valor, $tono, $ayuda, $ruta, [
            'verde' => $umbral['verde'] === 0 ? '0' : '0 a '.$umbral['verde'],
            'amarillo' => ($umbral['verde'] + 1).' a '.$umbral['amarillo'],
            'rojo' => ($umbral['amarillo'] + 1).' o más',
        ]);
    }

    /** Semáforo de ritmo: mayor es mejor, frente al mes anterior. Incluye escala de bala. */
    private function tarjetaRitmo(float $ingresos, ?int $ritmo, array $umbral): array
    {
        $tono = match (true) {
            $ritmo === null => 'sin_referencia',
            $ritmo >= $umbral['verde'] => 'verde',
            $ritmo >= $umbral['amarillo'] => 'amarillo',
            default => 'rojo',
        };

        $escalaMaxima = max(130, $umbral['verde'] + 30);

        $tarjeta = $this->tarjeta(
            'Ingresos del mes',
            '$'.number_format($ingresos, 0).' MXN',
            $tono,
            $ritmo === null
                ? 'El mes anterior no tiene ingresos aprobados para comparar.'
                : "Va en {$ritmo}% del ritmo esperado según el mes anterior.",
            route('admin.pagos.index'),
            [
                'verde' => $umbral['verde'].'% o más',
                'amarillo' => $umbral['amarillo'].'% a '.($umbral['verde'] - 1).'%',
                'rojo' => 'menos de '.$umbral['amarillo'].'%',
            ],
        );

        $tarjeta['escala'] = [
            'valor' => $ritmo,
            'marcador' => $ritmo === null
                ? null
                : round(min($escalaMaxima, max(0, $ritmo)) / $escalaMaxima * 100, 2),
            'amarillo' => $umbral['amarillo'],
            'verde' => $umbral['verde'],
            'corte_amarillo' => round($umbral['amarillo'] / $escalaMaxima * 100, 2),
            'corte_verde' => round($umbral['verde'] / $escalaMaxima * 100, 2),
        ];

        return $tarjeta;
    }

    private function tarjeta(string $titulo, string $valor, string $tono, string $ayuda, string $ruta, array $rangos): array
    {
        return [
            'titulo' => $titulo,
            'valor' => $valor,
            'tono' => $tono,
            'etiqueta' => match ($tono) {
                'verde' => 'En orden',
                'amarillo' => 'Atender pronto',
                'rojo' => 'Atender ya',
                default => 'Sin referencia',
            },
            'ayuda' => $ayuda,
            'ruta' => $ruta,
            'rangos' => $rangos,
        ];
    }
}
