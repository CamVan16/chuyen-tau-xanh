<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TrainRoutes extends Migration
{
    public function up(): void
    {
        Schema::create('train_routes', function (Blueprint $table) {
            $table->integer('train_id');
            $table->integer('route_id');
            $table->integer('train_index');
            $table->timestamps();

            $table->primary(['train_id', 'route_id']);
            $table->foreign('train_id')->references('id')->on('trains')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('train_routes');
    }
};