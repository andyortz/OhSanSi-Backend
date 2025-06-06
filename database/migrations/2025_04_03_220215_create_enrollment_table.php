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
        Schema::create('enrollment', function (Blueprint $table) {
            $table->id('id_enrollment');
            $table->unsignedBigInteger('id_olympist_detail');
            $table->unsignedBigInteger('ci_academic_advisor')->nullable();
            $table->unsignedBigInteger('id_level');
            $table->unsignedBigInteger('id_list');
            // Foreign keys
            $table->foreign('id_olympist_detail')->references('id_olympist_detail')->on('olympist_detail')->onDelete('cascade');
            $table->foreign('id_level')->references('id_level')->on('category_level')->onDelete('cascade');
            $table->foreign('ci_academic_advisor')->references('ci_person')->on('person')->onDelete('set null');
            $table->foreign('id_list')->references('id_list')->on('enrollment_list')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment');
    }
};
