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
        Schema::create('grado_nivel', function (Blueprint $table) {
            $table->unsignedSmallInteger('id_nivel'); // FK a int8
            $table->unsignedSmallInteger('id_grado'); // FK a int2

            $table->foreign('id_nivel')->references('id_nivel')->on('nivel_categoria')->onDelete('cascade');
            $table->foreign('id_grado')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grado_nivel');
    }
};
