<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;
use App\Http\Controllers\VoucherController;

Route::get('/', [StationAreaController::class, 'showStations']);
Route::get('/', [VoucherController::class, 'showVouchers']);
