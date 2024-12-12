<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Trains extends Migration
{
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('num_of_seats');
            $table->integer('num_of_available_seats');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trains');
    }
}
