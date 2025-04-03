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
        Schema::create('olimpistas', function (Blueprint $table) {
            $table->id('id_olimpista');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->integer('cedula_identidad');
            $table->date('fecha_nacimiento');
            

            // Foreign keys
            $table->unsignedBigInteger('unidad_educativa');
            $table->unsignedBigInteger('id_grado');
            $table->unsignedSmallInteger('id_provincia');
            $table->unsignedBigInteger('id_tutor');

            //Relación a unidad educativa
            $table->foreign('unidad_educativa')->references('id_colegio')->on('colegios')->onDelete('restrict');
            // Relación a grados
            $table->foreign('id_grado')->references('id_grado')->on('grados')->onDelete('restrict');

            // Relación a provincias
            $table->foreign('id_provincia')->references('id_provincia')->on('provincias')->onDelete('restrict');

            // Relación a tutores
            $table->foreign('id_tutor')->references('id_tutor')->on('tutores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olimpistas');
    }
};
