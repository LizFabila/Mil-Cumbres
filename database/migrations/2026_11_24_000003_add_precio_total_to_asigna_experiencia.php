<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Total de la experiencia en la reservación, fijado al pedirla (incluye el precio de grupo y la oferta).
 * Las reservaciones anteriores quedan en NULL y se calculan con precio_unitario × personas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->decimal('precio_total', 10, 2)->nullable()->after('precio_unitario');
        });
    }

    public function down(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->dropColumn('precio_total');
        });
    }
};
