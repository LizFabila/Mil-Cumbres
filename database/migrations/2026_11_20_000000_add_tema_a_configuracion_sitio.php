<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Paleta de colores que eligió el administrador (ver App\Support\Temas).
        Schema::table('configuracion_sitio', function (Blueprint $table) {
            $table->string('tema', 20)->default('actual')->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_sitio', function (Blueprint $table) {
            $table->dropColumn('tema');
        });
    }
};
