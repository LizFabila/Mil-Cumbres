<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Se quitó la pantalla "Identidad del sitio", así que el lema ya no se puede
     * editar desde el panel. Se deja fijo en el texto correcto.
     */
    public function up(): void
    {
        if (Schema::hasTable('configuracion_sitio')) {
            DB::table('configuracion_sitio')->update(['lema' => 'Turismo Regenerativo']);
        }
    }

    public function down(): void
    {
        // No hay nada que revertir: el lema correcto es el mismo que el original.
    }
};
