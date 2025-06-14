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
            $table->smallIncrements('province_id'); // int2
            $table->string('province_name', 50);

            $table->unsignedSmallInteger('department_id'); // FK a int2

            $table ->foreign(department_id)
                ->references('department_id')
                ->on('department')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
