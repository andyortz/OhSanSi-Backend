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
        Schema::create('province', function (Blueprint $table) {
            $table->smallIncrements('id_province'); // int2
            $table->string('province_name', 50);

            $table->unsignedSmallInteger('id_departament'); // FK a int2

            // Foreign key constraint
            $table->foreign('id_departament')->references('id_departament')->on('departament')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province');
    }
};
