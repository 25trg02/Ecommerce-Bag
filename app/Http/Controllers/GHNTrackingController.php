<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GHNTrackingController extends Controller
{
    public function track(Request $request)
    {
        $orderCode = $request->input('order_code', 'SAMPLE_ORDER_CODE'); // Thay bằng mã vận đơn test
        $response = Http::withHeaders([
            'Token' => '1fd3ef25-9438-11f0-bdaf-ae7fa045a771',
            'Content-Type' => 'application/json',
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail', [
            'order_code' => $orderCode,
        ]);

        $data = $response->json();
        return view('user.payment.order', [
            'tracking' => $data,
            'order_code' => $orderCode,
        ]);
    }
}
