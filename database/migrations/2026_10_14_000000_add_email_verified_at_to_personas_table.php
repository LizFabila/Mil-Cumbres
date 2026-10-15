<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega la fecha de verificación del correo a personas (C15).
 * Las cuentas que ya existían se marcan como verificadas para que nadie
 * (por ejemplo, los administradores) se quede sin acceso.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('personas') || Schema::hasColumn('personas', 'email_verified_at')) {
            return;
        }

        Schema::table('personas', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        DB::table('personas')->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('personas', 'email_verified_at')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }
    }
};
