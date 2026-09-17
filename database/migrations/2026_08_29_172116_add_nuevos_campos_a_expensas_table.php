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
    Schema::table('expensas', function (Blueprint $table) {
        // Agregamos los campos financieros con valor por defecto 0
        $table->decimal('monto_pagado', 10, 2)->default(0)->after('monto');
        $table->decimal('monto_pendiente', 10, 2)->default(0)->after('monto_pagado');
        $table->decimal('monto_ahorro', 10, 2)->default(0)->after('monto_pendiente');
        
        // Agregamos los campos descriptivos y de control
        $table->string('mes_gestion')->nullable()->after('monto_ahorro');
        $table->tinyInteger('status')->default(1)->after('estado');
    });
}

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('expensas', function (Blueprint $table) {
        $table->dropColumn([
            'monto_pagado', 
            'monto_pendiente', 
            'monto_ahorro', 
            'mes_gestion', 
            'status'
        ]);
    });
}
};
