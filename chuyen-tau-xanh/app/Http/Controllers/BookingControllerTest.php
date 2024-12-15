<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MomoController;
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\ZaloPayController;
use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;

class BookingControllerTest extends Controller
{
    private $vnpayController;
    private $zalopayController;
    private $momoController;
    private $voucherController;
    
    public function __construct(
        VNPayController $vnpayController,
        ZaloPayController $zalopayController,
        MomoController $momoController,
        VoucherController $voucherController
    ) {
        $this->vnpayController = $vnpayController;
        $this->zalopayController = $zalopayController;
        $this->momoController = $momoController;
        $this->voucherController = $voucherController;
    }

    public function showBooking(Request $request)
    {
        $vouchers = $this->voucherController->showVouchersForBooking();
        
        return view('pages.booking', compact('vouchers'));
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
