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
        Schema::create('niveles_areas_olimpiadas', function (Blueprint $table) {
            $table->unsignedMediumInteger('id_olimpiada');//id_olimpiada
            $table->unsignedMediumInteger('id_area');//id_area
            $table->unsignedMediumInteger('id_niveles');//id_niveles_categoria

            $table->foreign('id_olimpiada')->references('id_olimpiada')->on('olimpiadas')->onDelete('cascade');
            $table->foreign('id_area')->references('id_area')->on('areas_competencia')->onDelete('cascade');
            $table->foreign('id_niveles')->references('id_niveles')->on('niveles_categoria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveles_areas_olimpiadas');
    }
};
