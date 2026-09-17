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
    Schema::create('ingresos_caja_chica', function (Blueprint $table) {
        $table->id();
        // Ajusta 'cajas_chicas' si el nombre de tu tabla en BD es diferente (ej. 'caja_chica')
        $table->foreignId('caja_chica_id')->constrained('cajas_chicas')->cascadeOnDelete();
        $table->foreignId('pago_id')->constrained('pagos')->cascadeOnDelete();
        
        $table->decimal('monto_ingreso', 10, 2);
        $table->date('fecha_ingreso');
        $table->tinyInteger('estado')->default(1);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_caja_chicas');
    }
};
