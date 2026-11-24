<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Precios por grupo de una experiencia: número de personas y precio TOTAL del grupo.
 * Relación lógica (sin FK) y borrado lógico, igual que experiencia_imagenes.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS experiencia_grupos (
                id_experiencia_grupo INT UNSIGNED NOT NULL AUTO_INCREMENT,
                id_experiencia       INT UNSIGNED NOT NULL COMMENT 'Relación lógica -> experiencias.id_experiencia',
                personas             SMALLINT UNSIGNED NOT NULL COMMENT 'Número de personas del grupo',
                precio               DECIMAL(10,2) NOT NULL COMMENT 'Precio total del grupo (MXN)',
                created_at           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at           DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id_experiencia_grupo),
                KEY idx_experiencia_grupos_exp (id_experiencia, personas)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS experiencia_grupos');
    }
};
