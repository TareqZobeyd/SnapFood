<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_party_food', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('food_party_id')->unsigned();
            $table->unsignedBigInteger('food_id')->unsigned();
            $table->timestamps();
            $table->foreign('food_party_id')->references('id')
                ->on('food_parties')->onDelete('cascade');
            $table->foreign('food_id')->references('id')
                ->on('food')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_party_food');
    }
};
