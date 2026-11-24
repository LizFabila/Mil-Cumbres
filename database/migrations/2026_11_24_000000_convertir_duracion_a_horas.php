<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * La duración de las experiencias se captura en horas (por ejemplo 1.5), no en minutos.
 * Convierte la columna sin perder datos: 180 min → 3.00 h.
 * Solo actúa si la columna todavía es entera, así que no vuelve a dividir si se repite.
 */
return new class extends Migration
{
    public function up(): void
    {
        $columna = $this->columna();

        if (! $columna || $columna->tipo === 'decimal') {
            return;
        }

        $nulo = $columna->nulo === 'YES' ? 'NULL' : 'NOT NULL';

        // Primero se cambia el tipo (los enteros pasan sin cambiar su valor) y después se convierte a horas.
        DB::statement("ALTER TABLE experiencias MODIFY duracion DECIMAL(5,2) {$nulo}");
        DB::statement('UPDATE experiencias SET duracion = ROUND(duracion / 60, 2) WHERE duracion IS NOT NULL');
    }

    public function down(): void
    {
        $columna = $this->columna();

        if (! $columna || $columna->tipo !== 'decimal') {
            return;
        }

        $nulo = $columna->nulo === 'YES' ? 'NULL' : 'NOT NULL';

        DB::statement('UPDATE experiencias SET duracion = ROUND(duracion * 60) WHERE duracion IS NOT NULL');
        DB::statement("ALTER TABLE experiencias MODIFY duracion SMALLINT UNSIGNED {$nulo}");
    }

    private function columna(): ?object
    {
        return DB::selectOne(
            "SELECT DATA_TYPE AS tipo, IS_NULLABLE AS nulo FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'experiencias' AND COLUMN_NAME = 'duracion'"
        );
    }
};
