<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingControllerTest extends Controller
{
    public function showBooking()
    {
        return view('pages.booking_test');
    }

    public function processPayment(Request $request)
    {
        $method = $request->input('payment_method');

        if ($method === 'vnpay') {
            return app(VNPayController::class)->processPayment($request);
        } elseif ($method === 'zalopay') {
            return app(ZaloPayController::class)->processPayment($request);
        } else {
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ!');
        }
    }
}
