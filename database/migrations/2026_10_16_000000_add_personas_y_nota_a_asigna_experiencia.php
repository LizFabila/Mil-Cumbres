<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->unsignedInteger('cant_persona')->nullable()->after('fecha_deseada');
            $table->text('nota')->nullable()->after('cant_persona');
        });

        // Las asignaciones existentes heredan el número de personas de su solicitud.
        $filas = DB::table('asigna_experiencia')
            ->whereNull('cant_persona')
            ->get(['id_asigna_exp', 'id_solicitud']);

        foreach ($filas as $fila) {
            $personas = DB::table('solicitudes')
                ->where('id_solicitud', $fila->id_solicitud)
                ->value('cant_persona');

            DB::table('asigna_experiencia')
                ->where('id_asigna_exp', $fila->id_asigna_exp)
                ->update(['cant_persona' => $personas ?? 1]);
        }
    }

    public function down(): void
    {
        Schema::table('asigna_experiencia', function (Blueprint $table) {
            $table->dropColumn(['cant_persona', 'nota']);
        });
    }
};
