<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class MomoController extends Controller
{
    /**
     * Xử lý thanh toán qua MoMo
     */
    public function processPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $amount = $request->input('amount');
        $orderInfo = $request->input('order_desc');

        $partnerCode = "MOMOBKUN20180529";
        $accessKey = "klm05TvNBzhg7h7j";
        $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        // $redirectUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        // $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'accessKey' => $accessKey,
            'requestId' => time() . "",
            'amount' => (string) $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => "http://127.0.0.1:8000/", //URL trả về sau khi thanh toán thành công
            'ipnUrl' => "http://127.0.0.1:8000/",
            // 'redirectUrl' => $redirectUrl,
            // 'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => '',
            // 'requestType' => 'captureWallet', //quét mã QR
            'requestType' => 'payWithATM', //atm 
        ];

        $rawHash = "accessKey={$data['accessKey']}&amount={$data['amount']}&extraData={$data['extraData']}&ipnUrl={$data['ipnUrl']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&partnerCode={$data['partnerCode']}&redirectUrl={$data['redirectUrl']}&requestId={$data['requestId']}&requestType={$data['requestType']}";
        $data['signature'] = hash_hmac('sha256', $rawHash, $secretKey);
        $response = Http::withoutVerifying()->post($endpoint, $data);
        if ($response->failed()) {
            return response()->json(['message' => 'Failed to connect to MoMo', 'status' => $response->status(), 'body' => $response->body(), 'data' => $data], 400);
        }

        $responseData = $response->json();

        if (!empty($responseData['payUrl'])) {
            return redirect($responseData['payUrl']);
        }

        return response()->json(['message' => 'Failed to get payment URL', 'data' => $responseData], 400);
    }

    public function completePayment(Request $request)
    {
        $transactionId = $request->input('orderId');
        $resultCode = $request->input('resultCode'); // 0 = thành công
        $message = $request->input('message');

        if ($resultCode == 0) {
            return redirect('http://127.0.0.1:8000/')->with('success', 'Thanh toán thành công!');
        }
        return redirect('http://127.0.0.1:8000/')->with('error', 'Thanh toán thất bại: ' . $message);
    }

    /**
     * Xử lý IPN callback từ MoMo
     */
    public function handleIPN(Request $request)
    {
        $transactionId = $request->input('orderId');
        $resultCode = $request->input('resultCode'); // 0 = thành công
        $message = $request->input('message');

        if ($resultCode == 0) {
            return response()->json(['message' => 'Payment success'], 200);
        }

        return response()->json(['message' => 'Payment failed'], 400);
    }
}
