<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;
use App\Http\Controllers\VoucherController;

Route::get('/', [StationAreaController::class, 'showStations']);
Route::get('/', [VoucherController::class, 'showVouchers']);


















































































































use App\Http\Controllers\RefundController;
Route::get('/tra-ve', [RefundController::class, 'getPageRefund'])->name('refund.getPageRefund');
Route::get('/tra-ve/quen-ma', [RefundController::class, 'showBookingCodeForm'])->name('refund.showBookingCodeForm');
Route::post('/tra-ve/quen-ma', [RefundController::class, 'sendBookingCode'])->name('refund.sendBookingCode');
Route::post('/tra-ve/step-1', [RefundController::class, 'findBooking'])->name('refund.findBooking');
Route::post('/tra-ve/step-2', [RefundController::class, 'createRefund'])->name('refund.createRefund');
Route::post('/tra-ve/step-2/xac-nhan', [RefundController::class, 'verifyConfirmation'])->name('refund.verifyConfirmation');
Route::get('/tra-ve/step-2', [RefundController::class, 'getPageRefundStep2'])->name('refund.getPageRefundStep2');
Route::get('/tra-ve/thanh-cong/{refund_id}', [RefundController::class, 'success'])->name('refund.success');
Route::get('/admin/get-list-refund', [RefundController::class, 'getListAll'])->name('refund.getListAll');
