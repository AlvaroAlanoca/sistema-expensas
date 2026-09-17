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
    Schema::table('egresos', function (Blueprint $table) {
        $table->string('concepto')->after('caja_chica_id');
        $table->date('fecha_egreso')->default(now())->after('monto');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egresos', function (Blueprint $table) {
            //
        });
    }
};
