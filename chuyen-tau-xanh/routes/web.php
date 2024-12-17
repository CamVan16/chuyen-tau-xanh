<?php

use App\Http\Controllers\BookingControllerTest;
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
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\ZaloPayController;
use App\Http\Controllers\MomoController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TransactionController;

Route::get('/giotau-giave', [StationAreaController::class, 'showStations']);
Route::get('/trains/results', [StationAreaController::class, 'search']);
Route::get('/ga', [StationAreaController::class, 'getAllStationName'])->name('stations.name');
Route::get('/train/{id}/details', [TrainController::class, 'showDetails'])->name('train.details');


Route::get('/tra-ve', [RefundController::class, 'getPageRefund'])->name('refund.getPageRefund');
Route::get('/tra-ve/quen-ma', [RefundController::class, 'showBookingCodeForm'])->name('refund.showBookingCodeForm');
Route::post('/tra-ve/quen-ma', [RefundController::class, 'sendBookingCode'])->name('refund.sendBookingCode');
Route::match(['get', 'post'], '/tra-ve/chon-ve', [RefundController::class, 'findBooking'])->name('refund.findBooking');
Route::match(['get', 'post'], '/tra-ve/chon-ve-tra', [RefundController::class, 'createRefund'])->name('refund.createRefund');
Route::get('/tra-ve/xac-nhan', [RefundController::class, 'getPageRefundStep2'])->name('refund.getPageRefundStep2');
Route::match(['get', 'post'],'/tra-ve/xac-nhan/thanh-cong', [RefundController::class, 'verifyConfirmation'])->name('refund.verifyConfirmation');
Route::get('/tra-ve/thanh-cong/{refund_id}', [RefundController::class, 'success'])->name('refund.success');
Route::get('/tra-ve/thanh-cong/{refund_id}/details', [RefundController::class, 'showTransactionDetails'])->name('refund.showTransactionDetails');
Route::get('/doi-ve', [ExchangeController::class, 'getPageExchange'])->name('refund.getPageExchange');
Route::get('/doi-ve/quen-ma', [ExchangeController::class, 'showBookingCodeForm'])->name('exchange.showBookingCodeForm');
Route::post('/doi-ve/quen-ma', [ExchangeController::class, 'sendBookingCode'])->name('exchange.sendBookingCode');
Route::match(['get', 'post'], '/doi-ve/chon-ve', [ExchangeController::class, 'findBooking'])->name('exchange.findBooking');
Route::match(['get', 'post'], '/doi-ve/chon-ve-doi', [ExchangeController::class, 'findTicket'])->name('exchange.findTicket');
Route::get('/doi-ve/chon-ve-doi/{selectedTicketId}', [ExchangeController::class, 'search'])->name('exchange.search');
Route::match(['get', 'post'], '/doi-ve/chon-ve-doi/xac-nhan', [ExchangeController::class, 'createExchange'])->name('exchange.createExchange');
Route::post('/exchange-ticket', [ExchangeController::class, 'exchangeTicket'])->name('exchange.exchangeTicket');
Route::post('/doi-ve/xac-nhan', [ExchangeController::class, 'verifyConfirmation'])->name('exchange.verifyConfirmation');
Route::get('/doi-ve/step-2/{selectedTicketId}', [ExchangeController::class, 'getPageExchangeStep2'])->name('exchange.getPageExchangeStep2');
Route::get('/doi-ve/thanh-cong/{exchange_id}', [ExchangeController::class, 'success'])->name('exchange.success');
Route::post('/doi-ve/thanh-toan', [BookingControllerTest::class, 'processPaymentExchange'])->name('booking.processPaymentExchange');
Route::get('/exchange-success', [ExchangeController::class, 'showSuccess'])->name('exchange.success');
Route::get('/quy-dinh', [RegulationController::class, 'index'])->name('regulations.index');
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
// Route::controller(RouteStationController::class);
Route::controller(TrainController::class)
    ->group(function () {
        //
    });
Route::controller(SeatController::class)
    ->group(function () {
        Route::post('timkiem/ketqua', 'getSeatsByCarId')->name('seat.getSeatsByCarId');
    });
Route::controller(RouteController::class)
    ->group(function () {
        Route::get('/', 'index')->name('routes.index');
        Route::post('/timkiem', 'search')->name('routes.search');
    });

// Booking Routes
Route::post('/booking', [BookingControllerTest::class, 'showBooking'])->name('booking.form');
Route::post('/booking/payment', [BookingControllerTest::class, 'processPayment'])->name('booking.processPayment');

// VNPay Routes
Route::post('/payment/vnpay', [VNPayController::class, 'processPayment'])->name('vnpay.process');
Route::get('/payment/vnpay/callback', [VNPayController::class, 'handleVNPayResponse'])->name('vnpay.response'); // điều chỉnh lại route cho phù hợp
Route::post('/payment/vnpay/exchange', [VNPayController::class, 'processPaymentExchange'])->name('vnpay.processExchange');
Route::get('/payment/vnpay/callback/exchange', [VNPayController::class, 'handleVNPayResponseExchange'])->name('vnpay.responseExchange');
// ZaloPay Routes
Route::post('/payment/zalopay', [ZaloPayController::class, 'processPayment'])->name('zalopay.process');
Route::get('/payment/zalopay/callback', [ZaloPayController::class, 'handleResponse'])->name('zalopay.response');
Route::post('/payment/zalopay/exchange', [ZaloPayController::class, 'processPaymentExchange'])->name('zalopay.processExchange');
Route::get('/payment/zalopay/callback/exchange', [ZaloPayController::class, 'handleResponseExchange'])->name('zalopay.responseExchange');

// Momo Routes
Route::post('/payment/momo', [MomoController::class, 'processPayment'])->name('payment.momo');
Route::get('/payment/momo/complete', [MomoController::class, 'completePayment'])->name('payment.momoComplete');
Route::post('/payment/momo/ipn', [MomoController::class, 'handleIPN'])->name('payment.momoIPN');

Route::get('/khuyen-mai', [VoucherController::class, 'showVouchers'])->name('vouchers.index');
Route::get('/khuyen-mai/{id}', [VoucherController::class, 'show'])->name('vouchers.show');

Route::get('/thongtingiaodich/doi-ve', [TransactionController::class, 'showInfoExchange'])->name('transaction.showInfoExchange');
Route::get('/thongtingiaodich', [TransactionController::class, 'showInfo'])->name('transaction.showInfo');
