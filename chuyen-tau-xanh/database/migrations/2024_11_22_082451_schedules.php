<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Schedules extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('train_id');
            $table->string('train_mark');
            $table->date('day_start');
            $table->time('time_start');
            $table->date('day_end');
            $table->time('time_end');
            $table->string('station_start');
            $table->string('station_end');
            $table->integer('seat_number');
            $table->string('car_name');
            $table->timestamps();

            $table->foreign('train_id')->references('id')->on('trains')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
