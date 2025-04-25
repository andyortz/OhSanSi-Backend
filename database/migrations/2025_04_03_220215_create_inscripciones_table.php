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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id('id_inscripcion');
            $table->unsignedBigInteger('id_olimpiada');
            $table->unsignedBigInteger('id_detalle_olimpista');
            $table->unsignedBigInteger('ci_tutor_academico')->nullable();
            $table->unsignedBigInteger('id_pago');
            $table->unsignedBigInteger('id_nivel');
            $table->string('estado', 50)->default('PENDIENTE');
            $table->timestamp('fecha_inscripcion', 6)->useCurrent();

            // Foreign keys
            $table->foreign('id_olimpiada')->references('id_olimpiada')->on('olimpiadas')->onDelete('cascade');
            $table->foreign('id_detalle_olimpista')->references('id_detalle_olimpista')->on('detalle_olimpistas')->onDelete('cascade');
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_categoria')->onDelete('cascade');
            $table->foreign('id_pago')->references('id_pago')->on('pagos')->onDelete('cascade');
            $table->foreign('ci_tutor_academico')->references('ci_persona')->on('personas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
