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
        Schema::create('olympiad_area_levels', function (Blueprint $table) {
            $table->unsignedMediumInteger('olympiad_id');//olympiad_id
            $table->unsignedMediumInteger('area_id');//area_id
            $table->unsignedMediumInteger('level_id');//level_ides_categoria

            $table->foreign('olympiad_id')
                ->references('olympiad_id')
                ->on('olympiads')
                ->onDelete('cascade');
            $table->foreign('area_id')
                ->references('area_id')
                ->on('areas')
                ->onDelete('cascade');
            $table->foreign('level_id')
                ->references('level_id')
                ->on('category_levels')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olympiad_area_levels');
    }
};
