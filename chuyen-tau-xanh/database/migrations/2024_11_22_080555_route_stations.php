<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RouteStations extends Migration
{
    public function up()
    {
        Schema::create('route_stations', function (Blueprint $table) {
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('station_id');
            $table->string('station_code');
            $table->string('station_name');
            $table->integer('km');
            $table->integer('date_index');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->date('departure_date')->nullable();
            $table->timestamps();

            $table->primary(['route_id', 'station_id']);
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('station_id')->references('id')->on('station_areas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_stations');
    }
}
