<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Routes extends Migration
{
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('route_name');
            $table->string('train_mark');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
