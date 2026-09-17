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
    Schema::table('detalle_pagos', function (Blueprint $table) {
        $table->foreignId('lectura_gas_id')
              ->nullable()
              ->after('lectura_agua_id')
              ->constrained('lecturas_gas')
              ->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_pagos', function (Blueprint $table) {
            //
        });
    }
};
