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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id',6);
            $table->string('refund_status',20)->default('pending');
            $table->date('refund_date');
            $table->unsignedBigInteger('customer_id');
            $table->string('payment_method',20)->nullable();
            $table->decimal('refund_amount', 10, 2);
            $table->date('refund_date_processed')->nullable();
            $table->timestamps();

            $table->foreign('booking_id') -> references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('customer_id') -> references('id')->on('customers')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
