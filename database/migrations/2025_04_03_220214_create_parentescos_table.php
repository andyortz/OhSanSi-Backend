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
        Schema::create('parentescos', function (Blueprint $table) {
            $table->unsignedMediumInteger('id_olimpista');
            $table->unsignedMediumInteger('id_tutor');
            $table->enum('rol_parentesco', ['Tutor Legal', 'Tutor Academico']);


            $table->foreign('id_olimpista')->references('id_olimpista')->on('olimpistas')->onDelete('cascade');
            $table->foreign('id_tutor')->references('id_tutor')->on('tutores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parentescos');
    }
};
