<?php

use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\RefundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;
use App\Http\Controllers\VoucherController;

Route::apiResource('station-areas', StationAreaController::class);
Route::apiResource('vouchers', VoucherController::class);
Route::apiResource('refunds', RefundController::class);
Route::apiResource('exchanges', ExchangeController::class);
Route::apiResource('vouchers', VoucherController::class);
// Route::get('/all', [RefundController::class, 'getListAll'])->name('refund.getListAll');
