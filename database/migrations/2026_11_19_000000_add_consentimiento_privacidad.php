<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Constancia del consentimiento de privacidad.
 *  - personas: cuándo aceptó el aviso de privacidad y qué versión aceptó (registro).
 *  - pagos: cuándo autorizó el tratamiento de sus datos financieros (pantalla de pago).
 * Las cuentas y pagos que ya existían quedan en NULL: no hay forma de probar
 * que aceptaron, así que no se inventa la fecha.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('personas') && ! Schema::hasColumn('personas', 'aviso_aceptado_at')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->timestamp('aviso_aceptado_at')->nullable();
                $table->string('aviso_version', 20)->nullable();
            });
        }

        if (Schema::hasTable('pagos') && ! Schema::hasColumn('pagos', 'consentimiento_financiero_at')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->timestamp('consentimiento_financiero_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('personas', 'aviso_aceptado_at')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn(['aviso_aceptado_at', 'aviso_version']);
            });
        }

        if (Schema::hasColumn('pagos', 'consentimiento_financiero_at')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->dropColumn('consentimiento_financiero_at');
            });
        }
    }
};
