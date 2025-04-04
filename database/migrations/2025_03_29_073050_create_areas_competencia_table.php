<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas_competencia', function (Blueprint $table) {
            $table->id('id_area');
            $table->string('nombre', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas_competencia');
    }
};
