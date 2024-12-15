<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Seats extends Migration
{
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            // $table->integer('id')->primary();
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('seat_type_id');
            $table->string('seat_type');
            $table->integer('seat_index');
            $table->integer('seat_status');
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('seat_type_id')->references('id')->on('seat_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
