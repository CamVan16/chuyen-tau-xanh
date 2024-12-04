<?php

use App\Http\Controllers\ExchangeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationAreaController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\CheckTicketController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingLookupController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\RouteStationController;

Route::get('/giotau-giave', [StationAreaController::class, 'showStations']);
Route::get('/khuyen-mai', [VoucherController::class, 'showVouchers']);

Route::get('/tra-ve', [RefundController::class, 'getPageRefund'])->name('refund.getPageRefund');
Route::get('/tra-ve/quen-ma', [RefundController::class, 'showBookingCodeForm'])->name('refund.showBookingCodeForm');
Route::post('/tra-ve/quen-ma', [RefundController::class, 'sendBookingCode'])->name('refund.sendBookingCode');
Route::match(['get', 'post'], '/tra-ve/chon-ve', [RefundController::class, 'findBooking'])->name('refund.findBooking');
Route::match(['get', 'post'], '/tra-ve/chon-ve-tra', [RefundController::class, 'createRefund'])->name('refund.createRefund');
Route::post('/tra-ve/xac-nhan/thanh-cong', [RefundController::class, 'verifyConfirmation'])->name('refund.verifyConfirmation');
Route::get('/tra-ve/xac-nhan', [RefundController::class, 'getPageRefundStep2'])->name('refund.getPageRefundStep2');
Route::get('/tra-ve/thanh-cong/{refund_id}', [RefundController::class, 'success'])->name('refund.success');
Route::get('/tra-ve/thanh-cong/{refund_id}/details', [RefundController::class, 'showTransactionDetails'])->name('refund.showTransactionDetails');
Route::get('/doi-ve', [ExchangeController::class, 'getPageExchange'])->name('refund.getPageExchange');
Route::get('/doi-ve/quen-ma', [ExchangeController::class, 'showBookingCodeForm'])->name('exchange.showBookingCodeForm');
Route::post('/doi-ve/quen-ma', [ExchangeController::class, 'sendBookingCode'])->name('exchange.sendBookingCode');
Route::match(['get', 'post'], '/doi-ve/chon-ve', [ExchangeController::class, 'findBooking'])->name('exchange.findBooking');
Route::match(['get', 'post'], '/doi-ve/chon-ve-doi', [ExchangeController::class, 'findTicket'])->name('exchange.findTicket');
Route::get('/doi-ve/chon-ve-doi/{selectedTicketId}', [ExchangeController::class, 'search'])->name('exchange.search');
Route::match(['get', 'post'], '/doi-ve/chon-ve-doi/xac-nhan', [ExchangeController::class, 'createExchange'])->name('exchange.createExchange');
Route::post('/doi-ve/xac-nhan', [ExchangeController::class, 'verifyConfirmation'])->name('exchange.verifyConfirmation');
Route::get('/doi-ve/step-2', [ExchangeController::class, 'getPageExchangeStep2'])->name('exchange.getPageExchangeStep2');
Route::get('/doi-ve/thanh-cong/{exchange_id}', [ExchangeController::class, 'success'])->name('exchange.success');
Route::get('/quy-dinh', function () {
    return view('pages.regulations');
});
Route::get('/huong-dan', function () {
    return view('pages.guides');
});
Route::get('/lien-he', function () {
    return view('pages.contact');
});
Route::post('/lien-he/gui', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/admin/get-list-refund', [RefundController::class, 'getListAll'])->name('refund.getListAll');

Route::get('/kiem-tra-ve', [CheckTicketController::class, 'showForm'])->name('check-ticket.form');
Route::post('/kiem-tra-ve', [CheckTicketController::class, 'checkTicket'])->name('check-ticket.process');

Route::get('/thong-tin-dat-cho', [BookingLookupController::class, 'showForm'])->name('booking.lookup.form');
Route::post('/thong-tin-dat-cho', [BookingLookupController::class, 'processLookup'])->name('booking.lookup.process');
Route::get('/thong-tin-dat-cho/quen-ma-dat-cho', [BookingLookupController::class, 'showForgotCodeForm'])->name('booking.forgot');
Route::post('/thong-tin-dat-cho/quen-ma-dat-cho', [BookingLookupController::class, 'sendBookingCode'])->name('booking.forgot.process');
Route::controller(RouteStationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('routes.index');
        Route::post('/timkiem', 'search')->name('routes.search');
    });
