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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->unsignedBigInteger('olympist_detail_id');
            $table->unsignedBigInteger('academic_tutor_ci')->nullable();
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('list_id');
            // Foreign keys
            $table->foreign('olympist_detail_id')
                ->references('olympist_detail_id')
                ->on('olympist_details')
                ->onDelete('cascade');
            $table->foreign('level_id')
                ->references('level_id')
                ->on('category_levels')
                ->onDelete('cascade');
            $table->foreign('academic_tutor_ci')
                ->references('person_ci')
                ->on('persons')
                ->onDelete('set null');
            $table->foreign('list_id')
                ->references('list_id')
                ->on('enrollment_lists')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['olympist_detail_id']);
            $table->dropForeign(['academic_tutor_ci']);
            $table->dropForeign(['level_id']);
            $table->dropForeign(['list_id']);
        });
        Schema::dropIfExists('enrollments');
    }
};
