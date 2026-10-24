<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Calificaciones de experiencias.
 *
 * Se escribe en SQL para conservar las convenciones de experiencias.sql:
 * relaciones lógicas (sin FOREIGN KEY), borrado lógico con deleted_at y la
 * columna generada "activo" dentro de los UNIQUE.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Catálogo de aspectos del servicio que se califican.
        DB::statement("
            CREATE TABLE aspectos_calificacion (
              id_aspecto   INT UNSIGNED NOT NULL AUTO_INCREMENT,
              nombre       VARCHAR(60)  NOT NULL,
              descripcion  VARCHAR(160) NULL,
              orden        TINYINT UNSIGNED NOT NULL DEFAULT 0,
              created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              deleted_at   DATETIME NULL DEFAULT NULL,
              activo       TINYINT(1) GENERATED ALWAYS AS (IF(deleted_at IS NULL, 1, NULL)) STORED,
              PRIMARY KEY (id_aspecto),
              UNIQUE KEY uq_aspectos_nombre (nombre, activo)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Una calificación por experiencia vivida dentro de una solicitud.
        DB::statement("
            CREATE TABLE calificaciones (
              id_calificacion   INT UNSIGNED NOT NULL AUTO_INCREMENT,
              id_asigna_exp     INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> asigna_experiencia.id_asigna_exp',
              id_persona        INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> personas.id_persona',
              id_experiencia    INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> experiencias.id_experiencia (copia para promedios)',
              estrellas         TINYINT UNSIGNED NOT NULL COMMENT 'CSAT: satisfacción general de 1 a 5',
              recomendacion     TINYINT UNSIGNED NULL     COMMENT 'NPS: probabilidad de recomendar de 0 a 10',
              comentario        TEXT NULL,
              permite_publicar  TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Consentimiento para mostrar la opinión en el sitio',
              estado            ENUM('pendiente','publicada','oculta') NOT NULL DEFAULT 'pendiente' COMMENT 'Moderación',
              respuesta         TEXT NULL     COMMENT 'Respuesta pública de Mil Cumbres',
              fecha_respuesta   DATETIME NULL,
              created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              deleted_at        DATETIME NULL DEFAULT NULL,
              activo            TINYINT(1) GENERATED ALWAYS AS (IF(deleted_at IS NULL, 1, NULL)) STORED,
              PRIMARY KEY (id_calificacion),
              UNIQUE KEY uq_calificaciones_asigna (id_asigna_exp, activo),
              KEY idx_calificaciones_experiencia (id_experiencia, estado),
              KEY idx_calificaciones_persona (id_persona),
              CONSTRAINT chk_calificaciones_estrellas     CHECK (estrellas BETWEEN 1 AND 5),
              CONSTRAINT chk_calificaciones_recomendacion CHECK (recomendacion IS NULL OR recomendacion BETWEEN 0 AND 10)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Puntuación de cada aspecto dentro de una calificación.
        DB::statement("
            CREATE TABLE calificacion_aspectos (
              id_calificacion_aspecto  INT UNSIGNED NOT NULL AUTO_INCREMENT,
              id_calificacion          INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> calificaciones.id_calificacion',
              id_aspecto               INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> aspectos_calificacion.id_aspecto',
              puntuacion               TINYINT UNSIGNED NOT NULL,
              created_at               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              updated_at               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              deleted_at               DATETIME NULL DEFAULT NULL,
              activo                   TINYINT(1) GENERATED ALWAYS AS (IF(deleted_at IS NULL, 1, NULL)) STORED,
              PRIMARY KEY (id_calificacion_aspecto),
              UNIQUE KEY uq_calif_aspecto (id_calificacion, id_aspecto, activo),
              KEY idx_calif_aspecto_aspecto (id_aspecto),
              CONSTRAINT chk_calif_aspecto_puntuacion CHECK (puntuacion BETWEEN 1 AND 5)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        DB::table('aspectos_calificacion')->insert([
            ['nombre' => 'Guía y anfitriones', 'descripcion' => 'Conocimiento, trato y acompañamiento', 'orden' => 1],
            ['nombre' => 'Organización y puntualidad', 'descripcion' => 'Punto de encuentro, horarios e información', 'orden' => 2],
            ['nombre' => 'Seguridad', 'descripcion' => 'Equipo, indicaciones y cuidado del grupo', 'orden' => 3],
            ['nombre' => 'Relación calidad-precio', 'descripcion' => 'Lo recibido frente a lo pagado', 'orden' => 4],
            ['nombre' => 'Cuidado del entorno', 'descripcion' => 'Respeto a la naturaleza y a la comunidad', 'orden' => 5],
        ]);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS calificacion_aspectos');
        DB::statement('DROP TABLE IF EXISTS calificaciones');
        DB::statement('DROP TABLE IF EXISTS aspectos_calificacion');
    }
};
