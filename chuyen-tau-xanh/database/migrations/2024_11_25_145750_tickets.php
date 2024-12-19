<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tickets extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->string('booking_id',8)->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('refund_id')->nullable();
            $table->unsignedBigInteger('exchange_id')->nullable();
            $table->unsignedBigInteger('schedule_id');
            $table->double('price');
            $table->double('discount_price')->nullable();
            $table->integer('ticket_status');
            // 1: có hiệu lực, -1: ko
            $table->timestamps();

            // $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            // $table->foreign('refund_id')->references('id')->on('refunds')->onDelete('cascade');
            // $table->foreign('exchange_id')->references('id')->on('exchanges')->onDelete('cascade');
            // $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
