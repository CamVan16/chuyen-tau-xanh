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
        Schema::table('exchanges', function (Blueprint $table) {
            $table->foreign('old_ticket_id') -> references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('new_ticket_id') -> references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('booking_id') -> references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('customer_id') -> references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropForeign(['old_ticket_id']);
            $table->dropForeign(['new_ticket_id']);
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['customer_id']);
        });
    }
};
