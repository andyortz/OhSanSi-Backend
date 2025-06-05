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
        Schema::create('olympiad', function (Blueprint $table) {
            $table->id('id_olympiad');
            $table->smallInteger('year');
            $table->decimal('cost', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('created_at', 6)->useCurrent(); // Usa CURRENT_TIMESTAMP en la DB
            $table->integer('max_olympic_categories');
            $table->string('olympiad_name', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olympiad');
    }
};
