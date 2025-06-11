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
        Schema::create('persona', function (Blueprint $table) {
            $table->id('ci_persona');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('correo_electronico',100);
            $table->date('fecha_nacimiento')->nullable();
            $table->string('celular', 15)->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
