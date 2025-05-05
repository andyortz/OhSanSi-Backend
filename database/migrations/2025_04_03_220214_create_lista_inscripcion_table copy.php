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
            $table->id('id_lista');
            $table->string('estado', 50)->default('PENDIENTE');
            $table->unsignedBigInteger('ci_responsable_inscripcion');
            $table->timestamp('fecha_creacion_lista', 6)->useCurrent();
            $table->unsignedBigInteger('cantidad');
            $table->decimal('monto_total', 10, 2); // numeric(10,2)
            $table->foreign('ci_responsable_inscripcion')->references('ci_persona')->on('personas')->onDelete('cascade');            
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
