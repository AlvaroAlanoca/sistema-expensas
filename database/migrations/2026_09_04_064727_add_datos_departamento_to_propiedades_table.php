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
    Schema::table('propiedades', function (Blueprint $table) {
        $table->string('torre')->nullable()->after('estado_ocupacion');
        $table->decimal('metros_cuadrados', 8, 2)->nullable()->after('piso');
        
        // Usamos decimal 5,2 porque un porcentaje nunca pasará de 100.00
        $table->decimal('porcentaje_participacion', 5, 2)->nullable()->after('metros_cuadrados')->comment('Ej: 15.50 para 15.5%');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            //
        });
    }
};
