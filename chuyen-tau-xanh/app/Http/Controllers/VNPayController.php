<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VNPayController extends Controller
{
    public function processPayment(Request $request)
    {
        $vnp_TxnRef = $request->input('order_id');
        $vnp_OrderInfo = $request->input('order_desc');
        $vnp_OrderType = $request->input('order_type');
        $vnp_Amount = $request->input('amount') * 100;
        $vnp_Locale = $request->input('language');
        $vnp_BankCode = $request->input('bank_code');
        $vnp_IpAddr = $request->ip();

        $vnp_TmnCode = "5037O4BA";
        $vnp_HashSecret = "LQLTG6DHBF6Z0ZAVE5XXA9ZNE8WV7PL7";
        $vnp_Returnurl = "http://127.0.0.1:8000/tim-cho";

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
    }

    public function handleVNPayResponse(Request $request)
    {
        if ($request->get('vnp_ResponseCode') === "00") {
            $response['message'] = 'Thanh toán thành công!';
        } else {
            $response['error'] = 'Thanh toán thất bại!';
        }

        return view('pages.vnpay_return', compact('response'));
    }
}
