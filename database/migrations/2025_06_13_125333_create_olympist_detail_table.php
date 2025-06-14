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
        Schema::create('olympist_detail', function (Blueprint $table) {
            $table->id('olympist_detail_id');
            
            $table->unsignedBigInteger('olympiad_id');
            $table->unsignedBigInteger('olympist_ci');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('school');
            $table->unsignedBigInteger('guardian_legal_ci');

            // Foreign keys
            $table->foreign('olympiad_id')
                ->references('olympiad_id')
                ->on('olympiad')
                ->onDelete('cascade');
            $table->foreign('olympist_ci')
                ->references('person_ci')
                ->on('person')
                ->onDelete('cascade');
            $table->foreign('grade_id')
                ->references('grade_id')
                ->on('grade')
                ->onDelete('cascade');
            $table->foreign('school')
                ->references('school_id')
                ->on('school')
                ->onDelete('cascade');
            $table->foreign('guardian_legal_ci')
                ->references('person_ci')
                ->on('person')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olympist_detail', function (Blueprint $table) {
            $table->dropForeign(['olympiad_id']);
            $table->dropForeign(['olympist_ci']);
            $table->dropForeign(['grade_id']);
            $table->dropForeign(['school']);
            $table->dropForeign(['guardian_legal_ci']);
        });

        Schema::dropIfExists('olympist_detail');
    }
};
