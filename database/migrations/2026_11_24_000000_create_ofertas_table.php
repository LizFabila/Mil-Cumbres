<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ofertas por tiempo limitado que da de alta el administrador.
 * Una oferta baja el precio de UNA experiencia entre dos fechas (ambas incluidas).
 * modo = 'porcentaje' → valor es el % de descuento; modo = 'precio' → valor es el precio final por persona.
 * Relación lógica con experiencias, igual que el resto de las tablas del proyecto (sin FK).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->increments('id_oferta');
            $table->unsignedInteger('id_experiencia');
            $table->string('modo', 12)->default('porcentaje');
            $table->decimal('valor', 10, 2);
            $table->string('etiqueta', 40)->nullable();
            $table->date('inicia');
            $table->date('termina');
            $table->timestamps();
            $table->softDeletes();

            $table->index('id_experiencia', 'idx_ofertas_experiencia');
            $table->index(['inicia', 'termina'], 'idx_ofertas_vigencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
