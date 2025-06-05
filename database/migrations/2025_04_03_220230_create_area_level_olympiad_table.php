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
        Schema::create('area_level_olympiad', function (Blueprint $table) {
            $table->unsignedMediumInteger('id_olympiad');//id_olimpiada
            $table->unsignedMediumInteger('id_area');//id_area
            $table->unsignedMediumInteger('id_level');//id_niveles_categoria

            $table->foreign('id_olympiad')->references('id_olympiad')->on('olympiad')->onDelete('cascade');
            $table->foreign('id_area')->references('id_area')->on('area')->onDelete('cascade');
            $table->foreign('id_level')->references('id_level')->on('category_level')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_level_olympiad');
    }
};
