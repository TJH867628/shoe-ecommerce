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
        //
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders', 'voucher_id')) {

                $table->dropForeign(['voucher_id']);

                $table->dropColumn('voucher_id');
            }
        });
        Schema::dropIfExists("vounchers");
        Schema::dropIfExists("wishlists");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::create("vounchers", function (Blueprint $table) {
            $table->id();

            $table->string('voucher_code')
                ->unique();

            $table->decimal('discount_value', 10, 2);

            $table->dateTime('expiry_date');

            $table->timestamps();

        });
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shoe_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shoe_id')->references('id')->on('shoes')->onDelete('cascade');

            // Unique constraint to prevent duplicate wishlist entries
            $table->unique(['user_id', 'shoe_id']);
        });
    }
};
