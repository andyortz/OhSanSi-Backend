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
            $table->unsignedBigInteger('id_detalle_olimpista');
            $table->unsignedBigInteger('ci_tutor_academico')->nullable();
            $table->unsignedBigInteger('id_nivel');
            $table->unsignedBigInteger('id_lista');
            // Foreign keys
            $table->foreign('id_detalle_olimpista')->references('id_detalle_olimpista')->on('detalle_olimpistas')->onDelete('cascade');
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_categoria')->onDelete('cascade');
            $table->foreign('ci_tutor_academico')->references('ci_persona')->on('personas')->onDelete('set null');
            $table->foreign('id_lista')->references('id_lista')->on('lista_inscripcion')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropForeign(['id_detalle_olimpista']);
            $table->dropForeign(['ci_tutor_academico']);
            $table->dropForeign(['id_nivel']);
            $table->dropForeign(['id_lista']);
        });

        Schema::dropIfExists('inscripciones');
    }
};
