<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Galería de imágenes por experiencia (máximo 10, lo valida el controlador).
 * Relación lógica (sin FK) y borrado lógico, igual que el resto de las tablas.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS experiencia_imagenes (
                id_experiencia_imagen INT UNSIGNED NOT NULL AUTO_INCREMENT,
                id_experiencia        INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> experiencias.id_experiencia',
                ruta                  VARCHAR(255) NOT NULL COMMENT 'Ruta dentro de storage/app/public',
                orden                 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
                created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at            DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id_experiencia_imagen),
                KEY idx_experiencia_imagenes_exp (id_experiencia, orden)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS experiencia_imagenes');
    }
};
