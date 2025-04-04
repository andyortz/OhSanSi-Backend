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

            $table->unsignedBigInteger('id_olimpista');
            $table->unsignedBigInteger('id_pago');
            $table->unsignedBigInteger('id_nivel');
            

            $table->timestamp('fecha_inscripcion', 6)->useCurrent();
            $table->string('estado', 50)->default('pendiente');

            // Foreign keys
            $table->foreign('id_olimpista')->references('id_olimpista')->on('olimpistas')->onDelete('cascade');
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_categoria')->onDelete('cascade');
            $table->foreign('id_pago')->references('id_pago')->on('pagos')->onDelete('cascade');
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
