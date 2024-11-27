<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RouteStations extends Migration
{
    public function up()
    {
        Schema::create('route_stations', function (Blueprint $table) {
            $table->integer('route_id');
            $table->string('station_code');
            $table->string('station_name');
            $table->integer('km');
            $table->integer('date_index');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->date('departure_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_stations');
    }
}
