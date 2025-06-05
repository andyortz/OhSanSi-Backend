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
        Schema::create('payment', function (Blueprint $table) {
            $table->id('id_payment');
            $table->string('receipt', 255);
            $table->timestamp('payment_date',6)->useCurrent();
            $table->unsignedBigInteger('id_list');
            $table->decimal('total_amount', 10, 2);
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at', 6)->nullable();
            $table->string('verified_by', 100)->nullable();

            $table->foreign('id_list')->references('id_list')->on('enrollment_list')->onDelete('cascade');            

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
