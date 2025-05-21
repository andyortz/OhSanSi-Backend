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
        Schema::create('olimpiada', function (Blueprint $table) {
            $table->id('id_olimpiada');
            $table->smallInteger('gestion');
            $table->decimal('costo', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamp('creado_en', 6)->useCurrent(); // Usa CURRENT_TIMESTAMP en la DB
            $table->integer('max_categorias_olimpista');
            $table->string('nombre_olimpiada', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olimpiada');
    }
};
