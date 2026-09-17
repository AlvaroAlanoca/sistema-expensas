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
Schema::create('reportes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained(); // Reemplaza idGenerador
    $table->string('tipo_reporte', 65);
    $table->json('datos_generados');
    $table->tinyInteger('estado')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
