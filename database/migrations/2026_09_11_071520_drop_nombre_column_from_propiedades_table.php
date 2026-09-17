<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            // Elimina la columna de la base de datos
            $table->dropColumn('nombre'); 
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            // Restaura la columna en caso de hacer un rollback
            $table->string('nombre')->nullable();
        });
    }
};