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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('refund_id')->references('id')->on('refunds')->onDelete('cascade');
            $table->foreign('exchange_id')->references('id')->on('exchanges')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['refund_id']);
            $table->dropForeign(['exchange_id']);
            $table->dropForeign(['schedule_id']);
        });
    }
};
