<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Una oferta puede aplicar a una persona individual (precio por persona, como antes)
 * o a un grupo de la experiencia (descuento sobre el precio total de ese grupo).
 * Las ofertas que ya existían quedan como 'persona'.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ofertas', function (Blueprint $table) {
            $table->string('aplica_a', 10)->default('persona')->after('modo');
            $table->unsignedSmallInteger('grupo_personas')->nullable()->after('aplica_a');
        });
    }

    public function down(): void
    {
        Schema::table('ofertas', function (Blueprint $table) {
            $table->dropColumn(['aplica_a', 'grupo_personas']);
        });
    }
};
