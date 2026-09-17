<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('lecturas_gas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('propiedad_id')->constrained('propiedades')->cascadeOnDelete();
        $table->string('mes_gestion');
        $table->decimal('lectura_anterior', 10, 2)->default(0);
        $table->decimal('lectura_actual', 10, 2)->default(0);
        $table->decimal('consumo_m3', 10, 2)->default(0);
        $table->decimal('monto_total', 10, 2)->default(0);
        $table->decimal('monto_pagado', 10, 2)->default(0);
        $table->decimal('monto_pendiente', 10, 2)->default(0);
        $table->tinyInteger('estado')->default(2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectura_gases');
    }
};
