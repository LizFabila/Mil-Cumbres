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

    /** Cómo se cobra: por persona o solo por grupo. */
    public const TIPOS_PRECIO = [
        'persona' => 'Precio por persona',
        'grupo' => 'Precio por grupo',
    ];

    protected $fillable = [
        'nombre', 'id_zona', 'id_catalogo_exp', 'resumen', 'temporada',
        'duracion', 'precio', 'tipo_precio', 'requisitos', 'imagen',
    ];

    protected function casts(): array
    {
        return [
            'duracion' => 'decimal:2',
            'precio' => 'decimal:2',
        ];
    }

    /** Zona principal (la primera que se eligió). */
    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }

    /** Todas las zonas donde se realiza la experiencia (tabla asigna_zona). */
    public function zonas(): BelongsToMany
    {
        return $this->belongsToMany(Zona::class, 'asigna_zona', 'id_experiencia', 'id_zona')
            ->using(AsignaZona::class)
            ->withPivot('id_asigna_zona')
            ->wherePivotNull('deleted_at')
            ->withTimestamps()
            ->orderBy('zonas.nombre');
    }

    /** "Avándaro · Monte Alto"; si aún no tiene zonas asignadas, usa la principal. */
    protected function zonasTexto(): Attribute
    {
        return Attribute::get(function () {
            $nombres = $this->zonas->pluck('nombre');

            if ($nombres->isEmpty() && $this->zona) {
                $nombres = collect([$this->zona->nombre]);
            }

            return $nombres->implode(' · ');
        });
    }

    /**
     * Guarda las zonas elegidas respetando el borrado lógico:
     * reactiva o crea las elegidas y da de baja (deleted_at) las que se quitaron.
     */
    public function sincronizarZonas(array $ids): void
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));

        foreach ($ids as $idZona) {
            AsignaZona::firstOrCreate(['id_experiencia' => $this->id_experiencia, 'id_zona' => $idZona]);
        }

        AsignaZona::where('id_experiencia', $this->id_experiencia)
            ->whereNotIn('id_zona', $ids)
            ->get()
            ->each->delete();

        $this->unsetRelation('zonas');
    }

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(CatalogoExperiencia::class, 'id_catalogo_exp', 'id_catalogo_exp');
    }

    /** Grupos con precio total (tabla experiencia_grupos), de menos a más personas. */
    public function grupos(): HasMany
    {
        return $this->hasMany(ExperienciaGrupo::class, 'id_experiencia', 'id_experiencia')
            ->orderBy('personas');
    }

    /** Oferta de grupo vigente para ese grupo, o null (se identifica por el primer número de personas). */
    public function ofertaDeGrupo(ExperienciaGrupo $grupo): ?Oferta
    {
        return Oferta::vigentesGruposPorExperiencia()
            ->get($this->id_experiencia)
            ?->first(fn ($o) => (int) $o->grupo_personas === (int) $grupo->personas);
    }

    /** Precio total del grupo hoy: con su oferta de grupo, si la hay. */
    public function precioGrupoActual(ExperienciaGrupo $grupo): float
    {
        $oferta = $this->ofertaDeGrupo($grupo);

        return $oferta ? $oferta->aplicarAGrupo((float) $grupo->precio) : (float) $grupo->precio;
    }

    /** El grupo cuyo rango cubre ese número de personas, o null. */
    public function grupoPara(int $personas): ?ExperienciaGrupo
    {
        return $this->grupos->first(
            fn ($g) => $personas >= (int) $g->personas && $personas <= (int) ($g->personas_hasta ?? $g->personas)
        );
    }

    /**
     * Total hoy para N personas: si N cae en un grupo, el precio total de ese grupo;
     * si no, precio por persona de hoy × N.
     */
    public function precioParaPersonas(int $personas): float
    {
        $grupo = $this->grupoPara($personas);

        return $grupo ? $this->precioGrupoActual($grupo) : (float) $this->precio_actual * $personas;
    }

    /** Grupos en formato simple para el JavaScript de las páginas de reservación. */
    public function gruposParaJs(): array
    {
        return $this->grupos
            ->map(fn ($g) => [
                'desde' => (int) $g->personas,
                'hasta' => (int) ($g->personas_hasta ?? $g->personas),
                'precio' => $this->precioGrupoActual($g),
            ])
            ->values()
            ->all();
    }

    /** "4 a 8" o "4 a 8 y 10 a 12", para escribir "grupos de … personas" al cliente. */
    public function rangosGruposTexto(): string
    {
        return $this->grupos->map(function ($g) {
            $hasta = (int) ($g->personas_hasta ?? $g->personas);

            return $hasta > (int) $g->personas ? "{$g->personas} a {$hasta}" : (string) $g->personas;
        })->implode(' y ');
    }

    /**
     * Guarda los grupos (rango y precio) con borrado lógico.
     * Cada grupo se identifica por su número mínimo de personas.
     */
    public function sincronizarGrupos(array $grupos): void
    {
        $vigentes = [];

        foreach ($grupos as $g) {
            $grupo = ExperienciaGrupo::updateOrCreate(
                ['id_experiencia' => $this->id_experiencia, 'personas' => (int) $g['personas']],
                ['personas_hasta' => (int) $g['personas_hasta'], 'precio' => $g['precio']],
            );
            $vigentes[] = $grupo->id_experiencia_grupo;
        }

        ExperienciaGrupo::where('id_experiencia', $this->id_experiencia)
            ->whereNotIn('id_experiencia_grupo', $vigentes)
            ->get()
            ->each->delete();

        $this->unsetRelation('grupos');
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
            ->withPivot('fecha_deseada', 'cant_persona', 'precio_unitario', 'precio_total', 'nota', 'id_disponibilidad')
            ->wherePivotNull('deleted_at')
            ->withTimestamps();
    }

    /** Máximo de imágenes en la galería de una experiencia (la portada no cuenta). */
    public const MAX_IMAGENES = 10;

    /** Galería de la experiencia, en el orden que eligió el administrador. */
    public function imagenes(): HasMany
    {
        return $this->hasMany(ExperienciaImagen::class, 'id_experiencia', 'id_experiencia')
            ->orderBy('orden')
            ->orderBy('id_experiencia_imagen');
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


    /** Duración en horas: "3 h", "1.5 h", "0.75 h". */
    protected function duracionTexto(): Attribute
    {
        return Attribute::get(function () {
            $horas = rtrim(rtrim(number_format((float) $this->duracion, 2, '.', ''), '0'), '.');

            return ($horas !== '' ? $horas : '0').' h';
        });
    }

    /** Ofertas de la experiencia (activas, programadas y vencidas). */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'id_experiencia', 'id_experiencia');
    }

    /** Oferta vigente hoy (la de menor precio si hubiera varias), o null. */
    protected function ofertaActual(): Attribute
    {
        return Attribute::get(fn () => Oferta::vigentesPorExperiencia()->get($this->id_experiencia));
    }

    /** Precio por persona que se cobra hoy: con oferta si la hay. */
    protected function precioActual(): Attribute
    {
        return Attribute::get(fn () => $this->oferta_actual?->precio_oferta ?? (float) $this->precio);
    }

    protected function precioActualTexto(): Attribute
    {
        return Attribute::get(fn () => (float) $this->precio_actual <= 0
            ? ($this->tipo_precio !== 'persona' ? 'Por grupo' : 'Precio por confirmar')
            : '$'.number_format((float) $this->precio_actual, 2).' MXN');
    }

    /**
     * Precio por persona dentro de una solicitud: el que quedó guardado al pedirla
     * (asigna_experiencia.precio_unitario) o, en reservaciones anteriores, el de la experiencia.
     */
    public function precioEnSolicitud(): float
    {
        return (float) ($this->pivot?->precio_unitario ?? $this->precio);
    }

    /** Total de esta experiencia en la reservación: el que se fijó al pedir o, si no hay, precio × personas. */
    public function totalEnSolicitud(): float
    {
        if ($this->pivot?->precio_total !== null) {
            return (float) $this->pivot->precio_total;
        }

        return $this->precioEnSolicitud() * (int) $this->pivot?->cant_persona;
    }

    protected function precioTexto(): Attribute
    {
        return Attribute::get(function () {
            if ((float) $this->precio <= 0) {
                return $this->tipo_precio !== 'persona' ? 'Por grupo' : 'Precio por confirmar';
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

    /** Cupo de un horario concreto (el que se guardó en la tabla de disponibilidad). */
    public function cupoParaHorario(int $idDisponibilidad): int
    {
        $disp = $this->disponibilidades->firstWhere('id_disponibilidad', $idDisponibilidad);

        return $disp ? (int) $disp->pivot->cupo : 0;
    }

    /**
     * Personas ya apartadas en solicitudes vigentes (no canceladas) para esa fecha.
     * Si se indica un horario, cuenta solo las reservaciones de ese horario.
     */
    public function personasReservadas(
        Carbon|string $fecha,
        ?int $exceptoSolicitud = null,
        ?int $idDisponibilidad = null
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
                $query->where('solicitudes.id_solicitud', '!=', $exceptoSolicitud);
            })
            ->when($idDisponibilidad, function ($query) use ($idDisponibilidad) {
                $query->where('asigna_experiencia.id_disponibilidad', $idDisponibilidad);
            })
            ->sum(DB::raw('COALESCE(asigna_experiencia.cant_persona, solicitudes.cant_persona)'));
    }

    /** Estados en los que una reservación aparta su horario para los demás clientes. */
    public const ESTADOS_QUE_APARTAN = ['confirmada', 'completada'];

    /**
     * Horarios apartados por reservaciones confirmadas, de hoy en adelante.
     * Devuelve ['2026-11-22' => [3, 5], ...] (fecha => ids de disponibilidad).
     */
    public function horariosApartados(?int $exceptoSolicitud = null): array
    {
        return $this->solicitudes()
            ->whereIn('solicitudes.estado', self::ESTADOS_QUE_APARTAN)
            ->whereRaw('DATE(COALESCE(asigna_experiencia.fecha_deseada, solicitudes.fecha_deseada)) >= ?', [now()->toDateString()])
            ->when($exceptoSolicitud, fn ($q) => $q->where('solicitudes.id_solicitud', '!=', $exceptoSolicitud))
            ->get()
            ->groupBy(fn ($s) => Carbon::parse($s->pivot->fecha_deseada ?? $s->fecha_deseada)->toDateString())
            ->map(function ($grupo, $fecha) {
                // Las reservaciones anteriores a los horarios no guardan cuál eligieron:
                // apartan todos los horarios de ese día.
                $delDia = $this->disponibilidades
                    ->where('dias_semana', Disponibilidad::diaDeFecha(Carbon::parse($fecha)))
                    ->pluck('id_disponibilidad');

                return $grupo
                    ->flatMap(fn ($s) => $s->pivot->id_disponibilidad ? [$s->pivot->id_disponibilidad] : $delDia)
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();
            })
            ->all();
    }

    /** ¿Ese horario ya está apartado por una reservación confirmada en esa fecha? */
    public function horarioApartado(int $idDisponibilidad, Carbon|string $fecha, ?int $exceptoSolicitud = null): bool
    {
        $fecha = Carbon::parse($fecha)->toDateString();

        return in_array($idDisponibilidad, $this->horariosApartados($exceptoSolicitud)[$fecha] ?? [], true);
    }

    /**
     * Fechas en las que ya no queda ningún horario libre: todos los horarios
     * de ese día de la semana están apartados. El calendario las muestra en gris.
     */
    public function fechasSinHorarios(?array $apartados = null): array
    {
        $apartados ??= $this->horariosApartados();

        return collect($apartados)
            ->filter(function ($ids, $fecha) {
                $dia = Disponibilidad::diaDeFecha(Carbon::parse($fecha));
                $delDia = $this->disponibilidades->where('dias_semana', $dia)->pluck('id_disponibilidad')->map(fn ($id) => (int) $id);

                return $delDia->isNotEmpty() && $delDia->diff($ids)->isEmpty();
            })
            ->keys()
            ->values()
            ->all();
    }

    public function lugaresDisponibles(Carbon|string $fecha): int
    {
        return max(0, $this->cupoParaFecha($fecha) - $this->personasReservadas($fecha));
    }

    public function lugaresParaHorario(Carbon|string $fecha, int $idDisponibilidad): int
    {
        return max(0, $this->cupoParaHorario($idDisponibilidad) - $this->personasReservadas($fecha, null, $idDisponibilidad));
    }

    /**
     * Revisa que el horario pertenezca a la experiencia, caiga en el día de la fecha
     * y tenga lugares para el número de personas. Devuelve el mensaje de error o null.
     */
    public function errorDeReserva(int $idDisponibilidad, Carbon|string $fecha, int $personas, ?int $exceptoSolicitud = null): ?string
    {
        $disp = $this->disponibilidades->firstWhere('id_disponibilidad', $idDisponibilidad);

        if (! $disp) {
            return "Elige un horario válido para {$this->nombre}.";
        }

        if ($disp->dias_semana !== Disponibilidad::diaDeFecha(Carbon::parse($fecha))) {
            return "El horario elegido de {$this->nombre} no corresponde al día de la fecha.";
        }

        // Una reservación confirmada aparta el horario completo en esa fecha.
        if ($this->horarioApartado($idDisponibilidad, $fecha, $exceptoSolicitud)) {
            return "El horario {$disp->horario} de {$this->nombre} ya está reservado para esa fecha. Elige otro horario u otra fecha.";
        }

        $libres = $this->lugaresParaHorario($fecha, $idDisponibilidad);

        if ($personas > $libres) {
            return "{$this->nombre} solo tiene {$libres} lugares en ese horario para esa fecha.";
        }

        return null;
    }
}
