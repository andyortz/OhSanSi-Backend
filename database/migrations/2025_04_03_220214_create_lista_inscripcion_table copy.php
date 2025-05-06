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
        Schema::create('lista_inscripcion', function (Blueprint $table) {
            
            $table->unsignedBigInteger('id_olimpiada');
            $table->id('id_lista');
            $table->string('estado', 50)->default('PENDIENTE');
            $table->unsignedBigInteger('ci_responsable_inscripcion');
            $table->timestamp('fecha_creacion_lista', 6)->useCurrent();
           
            $table->foreign('ci_responsable_inscripcion')->references('ci_persona')->on('personas')->onDelete('cascade');
            $table->foreign('id_olimpiada')->references('id_olimpiada')->on('olimpiadas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista');
    }
};
