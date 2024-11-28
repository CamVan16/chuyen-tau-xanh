<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Exchanges extends Migration
{
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_ticket_id');
            $table->unsignedBigInteger('new_ticket_id');
            $table->string('booking_id');
            $table->unsignedBigInteger('customer_id');
            $table->double('old_price');
            $table->double('new_price');
            $table->double('additional_price');
            $table->string('payment_method')->nullable();
            $table->string('exchange_status')->default('pending');
            $table->date('exchange_date');
            $table->date('refund_date_processed')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('exchanges');
    }
};
