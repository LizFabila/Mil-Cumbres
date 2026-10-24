<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Experiencia extends Model
{
    use SoftDeletes;

    protected $table = 'experiencias';
    protected $primaryKey = 'id_experiencia';

    protected $fillable = [
        'nombre', 'id_zona', 'id_catalogo_exp', 'resumen', 'temporada',
        'duracion', 'precio', 'requisitos', 'imagen',
    ];

    protected function casts(): array
    {
        return [
            'duracion' => 'integer',
            'precio' => 'decimal:2',
        ];
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(CatalogoExperiencia::class, 'id_catalogo_exp', 'id_catalogo_exp');
    }

    /** Horarios asignados (tabla asigna_disponibilidad) con su cupo. */
    public function disponibilidades(): BelongsToMany
    {
        return $this->belongsToMany(Disponibilidad::class, 'asigna_disponibilidad', 'id_experiencia', 'id_disponibilidad')
            ->using(AsignaDisponibilidad::class)
            ->withPivot('id_asigna_disponibilidad', 'cupo')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    
    public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(
            Solicitud::class,
            'asigna_experiencia',
            'id_experiencia',
            'id_solicitud'
        )
            ->using(AsignaExperiencia::class)
            ->withPivot('fecha_deseada', 'cant_persona', 'nota')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    /** Calificaciones que los clientes dieron a esta experiencia. */
    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'id_experiencia', 'id_experiencia');
    }

    /**
     * Datos del bloque público de opiniones en el detalle de la experiencia.
     *  - El promedio usa todas las calificaciones no ocultas y se muestra
     *    a partir del mínimo configurado.
     *  - Los testimonios solo incluyen opiniones publicadas con permiso del cliente.
     */
    public function resumenOpiniones(int $testimonios = 6): array
    {
        $vigentes = Calificacion::query()
            ->where('id_experiencia', $this->id_experiencia)
            ->vigentes();
        $metricas = Calificacion::metricas($vigentes);

        $aspectos = AspectoCalificacion::ordenados()->get()->map(fn ($a) => [
            'nombre' => $a->nombre,
            'promedio' => CalificacionAspecto::where('id_aspecto', $a->id_aspecto)
                ->whereIn('id_calificacion', (clone $vigentes)->select('id_calificacion'))
                ->avg('puntuacion'),
        ])->filter(fn ($a) => $a['promedio'] !== null)->values();

        return [
            'metricas' => $metricas,
            'mostrarPromedio' => $metricas['total'] >= (int) config('milcumbres.calificaciones.minimo_para_promedio', 3),
            'aspectos' => $aspectos,
            'testimonios' => $this->calificaciones()->publicadas()
                ->whereNotNull('comentario')
                ->with(['persona', 'asignacion'])
                ->latest()
                ->take($testimonios)
                ->get(),
        ];
    }


    /** "3 h (180 min)", "1 h 30 min (90 min)", "45 min" */
    protected function duracionTexto(): Attribute
    {
        return Attribute::get(function () {
            $h = intdiv($this->duracion, 60);
            $m = $this->duracion % 60;
            if ($h === 0) {
                return "{$m} min";
            }

            return $h.' h'.($m ? " {$m} min" : '')." ({$this->duracion} min)";
        });
    }

    protected function precioTexto(): Attribute
    {
        return Attribute::get(function () {
            if ((float) $this->precio <= 0) {
                return 'Precio por confirmar';
            }

            return '$'.number_format((float) $this->precio, 2).' MXN';
        });
    }

    /** Acepta URL completa (imágenes temporales) o ruta dentro de storage/app/public. */
    protected function imagenUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->imagen) {
                return config('milcumbres.imagenes.experiencia_default');
            }

            return Str::startsWith($this->imagen, ['http://', 'https://'])
                ? $this->imagen
                : asset('storage/'.$this->imagen);
        });
    }

    protected function slug(): Attribute
    {
        return Attribute::get(fn () => Str::slug($this->nombre));
    }

    /** Cupo máximo para una fecha: el del horario asignado al día de la semana de esa fecha. */
    public function cupoParaFecha(Carbon|string $fecha): int
    {
        $dia = Disponibilidad::diaDeFecha(Carbon::parse($fecha));

        return (int) $this->disponibilidades
            ->where('dias_semana', $dia)
            ->max(fn ($d) => $d->pivot->cupo);
    }

    /** Personas ya apartadas en solicitudes vigentes (no canceladas) para esa fecha. */
    public function personasReservadas(
    Carbon|string $fecha,
    ?int $exceptoSolicitud = null
): int {
    $fecha = Carbon::parse($fecha)->toDateString();

    return (int) $this->solicitudes()
        ->where('solicitudes.estado', '!=', 'cancelada')
        ->where(function ($query) use ($fecha) {
            $query->where(function ($query) use ($fecha) {
                $query->whereNotNull('asigna_experiencia.fecha_deseada')
                    ->whereDate('asigna_experiencia.fecha_deseada', $fecha);
            })->orWhere(function ($query) use ($fecha) {
                $query->whereNull('asigna_experiencia.fecha_deseada')
                    ->whereDate('solicitudes.fecha_deseada', $fecha);
            });
        })
        ->when($exceptoSolicitud, function ($query) use ($exceptoSolicitud) {
            $query->where(
                'solicitudes.id_solicitud',
                '!=',
                $exceptoSolicitud
            );
        })
        ->sum(DB::raw('COALESCE(asigna_experiencia.cant_persona, solicitudes.cant_persona)'));
}

    public function lugaresDisponibles(Carbon|string $fecha): int
    {
        return max(0, $this->cupoParaFecha($fecha) - $this->personasReservadas($fecha));
    }
}
