<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Modalidad de precio que eligió el cliente al pedir la experiencia: 'persona' o 'grupo'.
 * Las reservaciones anteriores quedan en NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->string('modalidad_precio', 10)->nullable()->after('precio_total');
        });
    }

    public function down(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->dropColumn('modalidad_precio');
        });
    }
};
