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
        Schema::create('detalle_olimpista', function (Blueprint $table) {
            $table->id('id_detalle_olimpista');
            
            $table->unsignedBigInteger('id_olimpiada');
            $table->unsignedBigInteger('ci_olimpista');
            $table->unsignedBigInteger('id_grado');
            $table->unsignedBigInteger('unidad_educativa');
            $table->unsignedBigInteger('ci_tutor_legal');

            // Foreign keys
            $table->foreign('id_olimpiada')->references('id_olimpiada')->on('olimpiadas')->onDelete('cascade');
            $table->foreign('ci_olimpista')->references('ci_persona')->on('personas')->onDelete('cascade');
            $table->foreign('id_grado')->references('id_grado')->on('grados')->onDelete('cascade');
            $table->foreign('unidad_educativa')->references('id_colegio')->on('colegios')->onDelete('cascade');
            $table->foreign('ci_tutor_legal')->references('ci_persona')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_olimpistas', function (Blueprint $table) {
            $table->dropForeign(['id_olimpiada']);
            $table->dropForeign(['ci_olimpista']);
            $table->dropForeign(['id_grado']);
            $table->dropForeign(['unidad_educativa']);
            $table->dropForeign(['ci_tutor_legal']);
        });

        Schema::dropIfExists('detalle_olimpistas');
    }
};
