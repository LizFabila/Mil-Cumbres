<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Una experiencia puede realizarse en varias zonas.
 * experiencias.id_zona se conserva como zona principal (la primera elegida),
 * para que lo existente siga funcionando; asigna_zona guarda todas.
 * Relación lógica (sin FK) y borrado lógico, igual que el resto de las tablas.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS asigna_zona (
                id_asigna_zona  INT UNSIGNED NOT NULL AUTO_INCREMENT,
                id_experiencia  INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> experiencias.id_experiencia',
                id_zona         INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> zonas.id_zona',
                created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at      DATETIME NULL DEFAULT NULL,
                activo          TINYINT(1) GENERATED ALWAYS AS (IF(deleted_at IS NULL, 1, NULL)) STORED,
                PRIMARY KEY (id_asigna_zona),
                UNIQUE KEY uq_asigna_zona (id_experiencia, id_zona, activo),
                KEY idx_asigna_zona_zona (id_zona)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Cada experiencia existente conserva su zona actual.
        DB::statement('
            INSERT INTO asigna_zona (id_experiencia, id_zona)
            SELECT e.id_experiencia, e.id_zona
            FROM experiencias e
            WHERE e.id_zona IS NOT NULL
              AND NOT EXISTS (
                  SELECT 1 FROM asigna_zona a
                  WHERE a.id_experiencia = e.id_experiencia AND a.id_zona = e.id_zona AND a.deleted_at IS NULL
              )
        ');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS asigna_zona');
    }
};
