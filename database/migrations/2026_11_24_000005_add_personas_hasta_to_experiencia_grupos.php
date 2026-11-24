<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Un grupo cubre un rango de personas: `personas` es el mínimo y `personas_hasta` el máximo.
 * Los grupos que ya existen eran de un solo tamaño, así que su máximo es igual a su mínimo.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiencia_grupos', function (Blueprint $table) {
            $table->unsignedSmallInteger('personas_hasta')->nullable()->after('personas');
        });

        DB::table('experiencia_grupos')->update(['personas_hasta' => DB::raw('personas')]);
    }

    public function down(): void
    {
        Schema::table('experiencia_grupos', function (Blueprint $table) {
            $table->dropColumn('personas_hasta');
        });
    }
};
