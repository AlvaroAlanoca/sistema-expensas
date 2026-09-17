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
Schema::create('expensas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('propiedad_id')->constrained('propiedades')->cascadeOnDelete();
    $table->decimal('monto', 10, 2); // 10 dígitos totales, 2 decimales
    $table->date('fecha_vencimiento'); // Cambiado de SMALLINT a DATE
    $table->tinyInteger('estado')->default(0); // 0: Pendiente, 1: Pagado, 2: Vencido
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expensas');
    }
};
