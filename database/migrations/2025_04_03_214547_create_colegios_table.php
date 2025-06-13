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
        Schema::create('colegio', function (Blueprint $table) {
            $table->smallIncrements("id_colegio");
            $table->string('nombre_colegio', 100);
            $table ->unsignedSmallInteger('id_provincia');

            // Relación a provincias
            $table->foreign('id_provincia')->references('id_provincia')->on('provincia')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colegio');
    }
};
