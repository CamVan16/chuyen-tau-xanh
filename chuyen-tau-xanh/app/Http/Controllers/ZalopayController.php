<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZaloPayController extends Controller
{
    private $config = [
        "app_id" => 2553,
        "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
        "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
        "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
    ];

    // Xử lý thanh toán ZaloPay
    public function processPayment(Request $request)
    {
        $amount = $request->input('amount');
        $transID = $request->input('order_id');
        $callbackUrl = route('zalopay.response');

        $embeddata = json_encode(['redirecturl' => $callbackUrl]);
        $items = '[]';

        $order = [
            "app_id" => $this->config["app_id"],
            "app_time" => round(microtime(true) * 1000),
            "app_trans_id" => date("ymd") . "_" . $transID,
            "app_user" => "user123",
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => $amount,
            "description" => "Payment for the order #$transID",
            "callback_url" => $callbackUrl
        ];

        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $this->config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $response = file_get_contents($this->config["endpoint"], false, $context);
        $result = json_decode($response, true);

        return redirect($result['order_url']);
    }

    // Xử lý phản hồi từ ZaloPay
    public function handleResponse(Request $request)
    {
        $dataStr = $request->input('data');
        $reqMac = $request->input('mac');

        $mac = hash_hmac("sha256", $dataStr, $this->config["key2"]);

        if ($reqMac !== $mac) {
            return redirect('/')->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
        }

        $dataJson = json_decode($dataStr, true);
        if ($dataJson['return_code'] == 1) {
            return redirect('/tim-cho')->with('success', 'Thanh toán thành công!');
        } else {
            return redirect('/tim-cho')->with('error', 'Thanh toán thất bại!');
        }
    }
}
