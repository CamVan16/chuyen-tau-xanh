<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;

Route::apiResource('station-areas', StationAreaController::class);
