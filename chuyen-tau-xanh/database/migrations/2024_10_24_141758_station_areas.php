<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StationAreas extends Migration
{
    public function up()
    {
        Schema::create('station_areas', function (Blueprint $table) {
            $table->id(); // Tạo trường Id kiểu int tự động tăng
            $table->string('station_code'); // Tạo trường stationCode kiểu string
            $table->string('station_name'); // Tạo trường stationName kiểu string
            $table->integer('km'); // Tạo trường km kiểu int
            $table->timestamps(); // Tạo trường created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('station_areas');
    }
}
