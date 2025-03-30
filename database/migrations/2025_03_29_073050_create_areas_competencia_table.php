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
        Schema::create('areas_competencia', function (Blueprint $table) {
            $table->id('id_area');
            $table->unsignedBigInteger('id_olimpiada'); // FK a olimpiadas
            $table->string('nombre', 50);
            $table->text('imagen')->nullable(); 

            $table->foreign('id_olimpiada')->references('id_olimpiada')->on('olimpiadas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas_competencia');
    }
};
