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
        Schema::create('food_discounts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->decimal('discount_percentage', 5, 2);
            $table->string('food_party')->nullable();
            $table->timestamps();
            $table->foreign('restaurant_id')->references('id')
                ->on('restaurants')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_discounts');
    }
};
