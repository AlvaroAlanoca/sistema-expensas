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
    // Forzamos el nombre en español para evitar 'gasto_ascensors'
    Schema::create('gastos_ascensor', function (Blueprint $table) {
        $table->id();
        $table->string('mes_gestion');
        $table->string('tipo'); // Mantenimiento, Reparación, Reserva
        $table->string('concepto'); // Ej: "Cambio de botonera piso 4"
        $table->decimal('monto_total', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasto_ascensors');
    }
};
