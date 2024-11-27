<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeatTypes extends Migration
{
    public function up()
    {
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->integer('train_id');
            $table->string('seat_type_code');
            $table->integer('seat_type_name');
            $table->double('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seat_types');
    }
}