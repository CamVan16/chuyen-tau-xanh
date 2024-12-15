<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Bookings extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->unsignedBigInteger('customer_id');
            // $table->double('discount_price')->nullable();
            $table->double('total_price')->nullable();
            $table->dateTime('booked_time');
            $table->integer('booking_status');
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->foreign('customer_id') -> references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
