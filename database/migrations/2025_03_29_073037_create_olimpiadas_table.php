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
        Schema::create('olimpiadas', function (Blueprint $table) {
            $table->id('id_olimpiada');
            $table->smallInteger('gestion'); // int2
            $table->decimal('costo', 10, 2); // numeric(10,2)
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamp('creado_en', 6)->useCurrent();
            $table->integer('max_categorias_olimpista');
            $table->string('nombre_olimpiada', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olimpiadas');
    }
};
