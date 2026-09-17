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
    Schema::create('detalle_pagos', function (Blueprint $table) {
        $table->id(); // Llave primaria
        $table->foreignId('pago_id')->constrained('pagos')->cascadeOnDelete(); // Relación con el recibo
        
        // Las relaciones con expensas o servicios son "nullable" porque un pago puede ser de una cosa o de la otra
        $table->foreignId('expensa_id')->nullable()->constrained('expensas')->nullOnDelete(); 
        $table->foreignId('servicio_id')->nullable(); // Ajusta si tienes tabla de servicios de agua
        
        $table->string('concepto');
        $table->decimal('monto', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pagos');
    }
};
