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
        Schema::create('enrollment_list', function (Blueprint $table) {
            
            $table->id('id_list');
            $table->unsignedBigInteger('id_olympiad');
            $table->enum('status', ['PAGADO', 'PENDIENTE'])->default('PENDIENTE');
            $table->unsignedBigInteger('ci_enrollment_responsible');
            $table->timestamp('list_creation_date', 6)->useCurrent();
           
            $table->foreign('ci_enrollment_responsible')->references('ci_person')->on('person')->onDelete('cascade');
            $table->foreign('id_olympiad')->references('id_olympiad')->on('olimpiad')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_list');
    }
};
