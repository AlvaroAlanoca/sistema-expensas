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
        $table->decimal('cuota_ordinaria', 10, 2)->default(0)->after('propiedad_id');
        $table->decimal('cuota_extraordinaria', 10, 2)->default(0)->after('cuota_ordinaria');
        $table->decimal('fondo_mantenimiento', 10, 2)->default(0)->after('cuota_extraordinaria');
        $table->decimal('fondo_ascensor', 10, 2)->default(0)->after('fondo_mantenimiento');
        $table->decimal('multas', 10, 2)->default(0)->after('fondo_ascensor');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expensas', function (Blueprint $table) {
            //
        });
    }
};
