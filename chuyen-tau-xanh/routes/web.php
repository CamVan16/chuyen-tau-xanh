<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;

Route::get('/', [StationAreaController::class, 'showStations']);
