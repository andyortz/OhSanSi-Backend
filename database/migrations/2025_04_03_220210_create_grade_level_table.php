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
        Schema::create('grade_level', function (Blueprint $table) {
            $table->unsignedSmallInteger('id_level'); // FK a int8
            $table->unsignedSmallInteger('id_grade'); // FK a int2
            $table->unsignedMediumInteger('id_olympiad') -> nullable();//FK id_olimpiada
            
            $table->foreign('id_level')->references('id_level')->on('category_level')->onDelete('cascade');
            $table->foreign('id_grade')->references('id_grade')->on('grade')->onDelete('cascade');
            $table->foreign('id_olympiad')->references('id_olympiad')->on('olympiad')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_level');
    }
};
