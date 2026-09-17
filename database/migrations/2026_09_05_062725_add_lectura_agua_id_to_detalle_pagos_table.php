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
        $table->foreignId('lectura_agua_id')
              ->nullable()
              ->after('expensa_id')
              ->constrained('lectura_aguas') 
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
