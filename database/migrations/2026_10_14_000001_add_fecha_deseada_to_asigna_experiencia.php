
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->date('fecha_deseada')->nullable()->after('id_experiencia');
        });
    }

    public function down(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->dropColumn('fecha_deseada');
        });
    }
};
