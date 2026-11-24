<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cómo se cobra la experiencia: 'persona' (precio por persona), 'grupo' (solo precios por grupo)
 * o 'ambos'. Las experiencias que ya existen cobran por persona.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiencias', function (Blueprint $table) {
            $table->string('tipo_precio', 10)->default('persona')->after('precio');
        });
    }

    public function down(): void
    {
        Schema::table('experiencias', function (Blueprint $table) {
            $table->dropColumn('tipo_precio');
        });
    }
};
