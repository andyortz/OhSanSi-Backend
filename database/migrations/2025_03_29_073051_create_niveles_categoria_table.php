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
        Schema::create('niveles_categoria', function (Blueprint $table) {
            $table->id('id_nivel');
            $table->string('nombre', 50);

            $table->unsignedBigInteger('id_area');
            $table->foreign('id_area')->references('id_area')->on('areas_competencia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveles_categoria');
    }
};
