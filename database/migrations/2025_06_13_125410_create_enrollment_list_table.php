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
            $table->unsignedBigInteger('olympiad_id');
            $table->id('list_id');
            $table->enum('status', ['PAGADO', 'PENDIENTE'])->default('PENDIENTE');
            $table->unsignedBigInteger('enrollment_responsible_ci');
            $table->timestamp('list_creation_date', 6)->useCurrent();
            
            $table->foreign('enrollment_responsible_ci')
                ->references('person_ci')
                ->on('person')
                ->onDelete('cascade');
            $table->foreign('olympiad_id')
                ->references('olympiad_id') 
                ->on('olimpiad')
                ->onDelete('cascade');
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
