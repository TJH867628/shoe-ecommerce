<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cart_id')
                ->constrained('shopping_carts')
                ->onDelete('cascade');

            $table->foreignId('shoe_variation_id')
                ->constrained('shoe_variations')
                ->onDelete('cascade');

            $table->integer('quantity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};