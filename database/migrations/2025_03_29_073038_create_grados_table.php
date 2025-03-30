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
        Schema::create('grados', function (Blueprint $table) {
            $table->smallIncrements('id_grado'); // int2
            $table->string('nombre_grado', 50);
            $table->string('nivel_academico', 20);
            $table->integer('orden'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
