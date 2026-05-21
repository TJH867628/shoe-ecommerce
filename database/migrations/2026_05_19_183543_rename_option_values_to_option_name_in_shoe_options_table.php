<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shoe_options', function (Blueprint $table) {

            $table->renameColumn(
                'option_values',
                'option_name'
            );

        });
    }

    public function down(): void
    {
        Schema::table('shoe_options', function (Blueprint $table) {

            $table->renameColumn(
                'option_name',
                'option_values'
            );

        });
    }
};