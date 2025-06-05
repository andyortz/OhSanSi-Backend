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
        Schema::create('olympic_detail', function (Blueprint $table) {
            $table->id('id_olympic_detail');
            
            $table->unsignedBigInteger('id_olympiad');
            $table->unsignedBigInteger('ci_olympic');
            $table->unsignedBigInteger('id_grade');
            $table->unsignedBigInteger('id_school');
            $table->unsignedBigInteger('ci_legal_guardian');

            // Foreign keys
            $table->foreign('id_olympiad')->references('id_olympiad')->on('olympiad')->onDelete('cascade');
            $table->foreign('ci_olympic')->references('ci_person')->on('person')->onDelete('cascade');
            $table->foreign('id_grade')->references('id_grade')->on('grade')->onDelete('cascade');
            $table->foreign('id_school')->references('id_school')->on('id_school')->onDelete('cascade');
            $table->foreign('ci_legal_guardian')->references('ci_person')->on('person')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olympic_detail');
    }
};
