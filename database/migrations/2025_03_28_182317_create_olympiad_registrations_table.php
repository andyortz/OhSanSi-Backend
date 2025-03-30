<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('olympiad_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('school')->nullable();
            $table->string('grade');
            $table->string('event_category');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('olympiad_registrations');
    }
};
