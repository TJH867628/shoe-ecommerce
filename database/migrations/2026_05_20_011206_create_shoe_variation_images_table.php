<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shoe_variation_images', function (Blueprint $table) {

            $table->id();

            $table->foreignId('shoe_variation_id')
                ->constrained('shoe_variations')
                ->onDelete('cascade');

            $table->string('image_path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'shoe_variation_images'
        );
    }
};