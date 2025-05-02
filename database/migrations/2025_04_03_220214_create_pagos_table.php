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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->string('comprobante', 255);
            $table->date('fecha_pago');
            $table->unsignedBigInteger('ci_responsable_inscripcion');
            $table->decimal('monto_pagado', 10, 2);
            $table->boolean('verificado')->default(false);
            $table->timestamp('verificado_en', 6)->nullable();
            $table->string('verificado_por', 100)->nullable();

            $table->foreign('ci_responsable_inscripcion')->references('ci_persona')->on('personas')->onDelete('cascade');            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
