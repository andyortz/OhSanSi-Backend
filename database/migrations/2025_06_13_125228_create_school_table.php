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
        Schema::create('school', function (Blueprint $table) {
            $table->smallIncrements("school_id");
            $table->string('school_name', 100);
            $table ->unsignedSmallInteger('province_id');

            // Relación a provincias
            $table->foreign('province_id')
                ->references('province_id')
                ->on('province')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school');
    }
};
