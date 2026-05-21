<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shoe_options', function (Blueprint $table) {

            $table->id();

            $table->foreignId('shoe_id')
                ->constrained('shoes')
                ->onDelete('cascade');

            $table->string('option_values');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shoe_options');
    }
};