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
        Schema::create('grados_niveles', function (Blueprint $table) {
            $table->unsignedSmallInteger('id_nivel'); // FK a int8
            $table->unsignedSmallInteger('id_grado'); // FK a int2

            $table->foreign('id_nivel')->references('id_nivel')->on('niveles_categoria')->onDelete('cascade');
            $table->foreign('id_grado')->references('id_grado')->on('grados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados_niveles');
    }
};
