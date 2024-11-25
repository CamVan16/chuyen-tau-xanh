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
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('id',6);
            $table->unsignedBigInteger('customer_id');
            $table->int('total_price');
            $table->int('discount_price');
            $table->date('booked_time');
            $table->int('booking_status');
            $table->timestamps();
            $table->foreign('customer_id') -> references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
