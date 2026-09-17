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
Schema::create('pagos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained(); // Quién pagó
    $table->foreignId('expensa_id')->constrained('expensas');
    $table->foreignId('metodo_pago_id')->constrained('metodos_pagos'); 
    $table->foreignId('caja_chica_id')->constrained('cajas_chicas');
    $table->decimal('monto', 10, 2);
    $table->date('fecha_pago');
    $table->tinyInteger('estado')->default(1); // 1: Aprobado, 0: Rechazado
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
