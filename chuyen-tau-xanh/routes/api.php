<!-- Thiết lập api ở đây -->
<?php

use App\Http\Controllers\RefundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;

Route::apiResource('station-areas', StationAreaController::class);
Route::apiResource('refund', RefundController::class);
// Route::get('/all', [RefundController::class, 'getListAll'])->name('refund.getListAll');
