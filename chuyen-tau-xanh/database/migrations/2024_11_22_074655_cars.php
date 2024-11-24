<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cars extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('train_id');
            $table->integer('car_index');
            $table->string('car_code');
            $table->string('car_name');
            $table->integer('car_layout');
            $table->string('car_description');
            $table->integer('num_of_seats');
            $table->integer('num_of_available_seats');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
