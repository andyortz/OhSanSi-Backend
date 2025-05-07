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
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->string('comprobante', 255);
            $table->timestamp('fecha_pago',6)->useCurrent();
            $table->unsignedBigInteger('id_lista');
            $table->decimal('monto_total', 10, 2);
            $table->boolean('verificado')->default(false);
            $table->timestamp('verificado_en', 6)->nullable();
            $table->string('verificado_por', 100)->nullable();

            $table->foreign('id_lista')->references('id_lista')->on('lista_inscripcion')->onDelete('cascade');            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
