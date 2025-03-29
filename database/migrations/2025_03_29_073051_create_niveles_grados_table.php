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
        Schema::create('niveles_grados', function (Blueprint $table) {
            $table->id('id_nivel_grado');

            $table->unsignedBigInteger('id_nivel');
            $table->unsignedBigInteger('id_grado');

            // Claves foráneas
            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_categoria')->onDelete('cascade');
            $table->foreign('id_grado')->references('id_grado')->on('grados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveles_grados');
    }
};
