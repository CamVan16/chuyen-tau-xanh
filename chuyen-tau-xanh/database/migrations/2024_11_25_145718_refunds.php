<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class Refunds extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->string('refund_status')->default('pending');
            $table->date('refund_date');
            $table->unsignedBigInteger('customer_id');
            $table->string('payment_method')->nullable();
            $table->double('refund_amount');
            $table->date('refund_date_processed')->nullable();
            $table->timestamps();

            $table->foreign('booking_id') -> references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('customer_id') -> references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
