<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tickets extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->integer('customer_id');
            $table->integer('refund_id');
            $table->integer('exchange_id');
            $table->integer('schedule_id');
            $table->double('price');
            $table->double('discount_price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
