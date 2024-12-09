<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MomoController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\ZaloPayController;
use Illuminate\Http\Request;

class BookingControllerTest extends Controller
{
    private $vnpayController;
    private $zalopayController;
    private $momoController;

    public function __construct(
        VNPayController $vnpayController,
        ZaloPayController $zalopayController,
        MomoController $momoController
    ) {
        $this->vnpayController = $vnpayController;
        $this->zalopayController = $zalopayController;
        $this->momoController = $momoController;
    }

    public function showBooking(Request $request)
    {
        // dd($request);
        // return view('pages.booking_test');
        return view('pages.booking');
    }

    public function processPayment(Request $request)
    {
        $method = $request->input('payment_method');

        try {
            if ($method === 'vnpay') {
                return $this->vnpayController->processPayment($request);
            } elseif ($method === 'zalopay') {
                return $this->zalopayController->processPayment($request);
            } elseif ($method === 'momo') {
                return $this->momoController->processPayment($request);
            } else {
                return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}
