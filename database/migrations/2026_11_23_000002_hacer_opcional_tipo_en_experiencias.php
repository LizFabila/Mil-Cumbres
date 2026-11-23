<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * El tipo (categoría) de una experiencia deja de ser obligatorio: el catálogo ya no usa categorías.
 * La tabla experiencias se crea desde experiencias.sql, así que se conserva el tipo de columna que ya tiene.
 */
return new class extends Migration
{
    public function up(): void
    {
        $columna = DB::selectOne(
            "SELECT COLUMN_TYPE AS tipo FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'experiencias' AND COLUMN_NAME = 'id_catalogo_exp'"
        );

        if ($columna) {
            DB::statement("ALTER TABLE experiencias MODIFY id_catalogo_exp {$columna->tipo} NULL");
        }
    }

    public function down(): void
    {
        // No se revierte: las experiencias sin tipo no podrían volver a tener categoría automáticamente.
    }
};
