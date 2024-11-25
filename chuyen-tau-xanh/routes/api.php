<?php

use App\Http\Controllers\RefundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;
use App\Http\Controllers\VoucherController;

Route::apiResource('station-areas', StationAreaController::class);
Route::apiResource('vouchers', VoucherController::class);
Route::apiResource('refund', RefundController::class);
// Route::get('/all', [RefundController::class, 'getListAll'])->name('refund.getListAll');
