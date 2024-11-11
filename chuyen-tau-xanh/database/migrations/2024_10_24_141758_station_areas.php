<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StationAreas extends Migration
{
    public function up()
    {
        Schema::create('station_areas', function (Blueprint $table) {
            $table->id();
            $table->string('station_code');
            $table->string('station_name');
            $table->integer('km');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('station_areas');
    }
}
