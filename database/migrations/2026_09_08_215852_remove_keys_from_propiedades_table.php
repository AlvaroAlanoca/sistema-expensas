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
        // Elimina las llaves foráneas y las columnas antiguas
        $table->dropForeign(['propietario_id']);
        $table->dropForeign(['inquilino_id']);
        $table->dropColumn(['propietario_id', 'inquilino_id']);
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
