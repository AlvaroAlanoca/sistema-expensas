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
    Schema::table('ingresos_caja_chica', function (Blueprint $table) {
        // Hacemos que el pago_id no sea obligatorio
        $table->unsignedBigInteger('pago_id')->nullable()->change();
        // Agregamos el concepto para los ingresos manuales
        $table->string('concepto')->nullable()->after('pago_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
