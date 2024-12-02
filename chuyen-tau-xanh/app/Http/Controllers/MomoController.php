<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function payment_momo(Request $request)
    {
        $request->validate([
            'transactionId' => 'required',
        ]);

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $transactionId = $request->transactionId;
        $transacion = Transaction::where('Transaction_ID', $transactionId)->first();
        if (!$transacion) return response()->json(['message' => 'Not found'], 404);

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $transacion->Total_money;
        $orderId = time() . "";
        $redirectUrl = route('payment.complete', ['transactionId' => $transactionId]);
        $ipnUrl = route('payment.complete', ['transactionId' => $transactionId]);
        $extraData = "";

        if ($request->isMethod('post')) {
            $partnerCode =  $partnerCode;
            $accessKey = $accessKey;
            $secretKey = $secretKey;
            $orderId = $orderId;
            $orderInfo = $orderInfo;
            $amount = $amount;
            $ipnUrl = $ipnUrl;
            $redirectUrl = $redirectUrl;
            $extraData = $extraData;

            $requestId = time() . "";
            $requestType = "payWithATM";
            $extraData = $request->extraData ?? "";

            // before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'transactionId' => $transactionId,
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];

            $result = Http::withoutVerifying()->post($endpoint, $data);
            $jsonResult = $result->json();

            // Just an example, please check more in there
            return response()->json($jsonResult);
            //return redirect($jsonResult['payUrl']);
        }

        // Handle the case where it's not a POST request
        return view('your_view_name');
    }

    public function payment_cod(Request $request)
    {
        $request->validate([
            'transactionId' => 'required',
        ]);
        $transactionId = $request->transactionId;
        $transacion = Transaction::where('Transaction_ID', $transactionId)->first();
        if (!$transacion) return response()->json(['message' => 'Not found'], 404);
        $transacion->Status = 1; // Status khi đã thanh toán cod
        $transacion->save();
        return response()->json(['message' => 'http://localhost:3000/checkout'], 200);     //page muốn hiện sau khi thanh toán
    }
    public function complete_momo($transactionId)
    {
        $transaction = Transaction::where('Transaction_ID', $transactionId)->first();
        if ($transaction) {
            $transaction->Status = 9;   // Status khi đã thanh toán momo
            $transaction->save();
            return redirect('http://localhost:3000/checkout');     //page muốn hiện sau khi thanh toán
        } else return response()->json(['message' => 'Not found'], 404);
    }
}
