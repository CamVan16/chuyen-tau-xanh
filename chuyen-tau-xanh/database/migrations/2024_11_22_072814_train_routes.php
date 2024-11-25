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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
