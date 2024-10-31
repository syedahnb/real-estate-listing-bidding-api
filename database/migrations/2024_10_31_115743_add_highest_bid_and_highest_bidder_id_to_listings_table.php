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
        Schema::table('listings', function (Blueprint $table) {
            $table->decimal('highest_bid', 10, 2)->nullable()->after('base_price'); // Add highest_bid with default null
            $table->unsignedBigInteger('highest_bidder_id')->nullable()->after('highest_bid'); // Add highest_bidder_id with default null

            // Optionally, add foreign key constraint if users table exists and you want a relationship
            $table->foreign('highest_bidder_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['highest_bidder_id']); // Drop foreign key if applied
            $table->dropColumn(['highest_bid', 'highest_bidder_id']);
        });
    }
};
