<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Precio por persona con el que se pidió cada experiencia (con oferta, si la había).
 * Así el total no cambia si después termina la oferta o se edita el precio de la experiencia.
 * Las reservaciones anteriores quedan en NULL y siguen usando el precio de la experiencia.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->decimal('precio_unitario', 10, 2)->nullable()->after('cant_persona');
        });
    }

    public function down(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->dropColumn('precio_unitario');
        });
    }
};
