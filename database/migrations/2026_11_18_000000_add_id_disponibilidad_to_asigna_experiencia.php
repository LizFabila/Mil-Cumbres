<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Guarda el horario que eligió el cliente (o el administrador) para cada experiencia
 * de una reservación. El cupo se cuenta por horario y por fecha.
 * Relación lógica, igual que el resto de las tablas del proyecto (sin FK).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->unsignedInteger('id_disponibilidad')->nullable()->after('id_experiencia');
            $table->index('id_disponibilidad', 'idx_asigna_exp_disponibilidad');
        });
    }

    public function down(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->dropIndex('idx_asigna_exp_disponibilidad');
            $table->dropColumn('id_disponibilidad');
        });
    }
};
