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
        Schema::create('level_grade', function (Blueprint $table) {
            $table->unsignedSmallInteger('level_id'); // FK a int8
            $table->unsignedSmallInteger('grade_id'); // FK a int2
            $table->unsignedMediumInteger('olympiad_id') -> nullable();//FK olympiad_id
            
            $table->foreign('level_id')
                ->references('level_id')
                ->on('category_level')
                ->onDelete('cascade');
            $table->foreign('grade_id')
                ->references('grade_id')
                ->on('grade')
                ->onDelete('cascade');
            $table->foreign('olympiad_id')
                ->references('olympiad_id')
                ->on('olympiad')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_grade');
    }
};
