<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Una sola fila con la identidad del sitio (nombre, lema y logotipo) que edita el administrador.
        Schema::create('configuracion_sitio', function (Blueprint $table) {
            $table->id('id_configuracion');
            $table->string('nombre', 60);
            $table->string('lema', 80)->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        DB::table('configuracion_sitio')->insert([
            'nombre' => 'Mil Cumbres',
            'lema' => 'Turismo Regenerativo',
            'logo' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_sitio');
    }
};
