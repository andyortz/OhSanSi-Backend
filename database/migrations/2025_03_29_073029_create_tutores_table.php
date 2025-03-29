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
        Schema::create('tutores', function (Blueprint $table) {
            $table->id('id_tutor'); // int4 PK
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->integer('ci');
            $table->integer('celular');
            $table->string('correo_electronico', 100);
            $table->enum('rol_parentesco', ['padre', 'madre', 'tutor_legal']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutores');
    }
};
