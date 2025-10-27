<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GHNTrackingService
{
    protected $token;
    protected $apiUrl;

    public function __construct()
    {
        // Hardcode token và URL test GHN
        $this->token = '637170d5-942b-11ea-9821-0281a26fb5d4';
        $this->apiUrl = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail';
    }

    /**
     * Lấy chi tiết đơn hàng từ GHN
     * @param string $orderCode
     * @return array|null
     */
    public function getOrderDetail($orderCode)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $this->token,
        ])->post($this->apiUrl, [
            'order_code' => $orderCode,
        ]);

        if ($response->successful() && $response->json('code') == 200) {
            return $response->json('data')[0] ?? null;
        }
        return null;
    }
}
