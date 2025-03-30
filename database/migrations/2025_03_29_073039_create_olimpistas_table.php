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
            $table->integer('numero_celular');
            $table->string('correo_electronico', 100);
            $table->date('fecha_nacimiento');
            $table->string('unidad_educativa', 100);

            // Foreign keys
            $table->unsignedBigInteger('id_grado');
            $table->unsignedSmallInteger('id_provincia');
            $table->unsignedBigInteger('id_tutor');

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
