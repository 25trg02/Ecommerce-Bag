<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TrackingMoreService
{
    protected $apiKey = '78sefm45-evh8-062f-huzf-geyrwq9omhyr';
    protected $baseUrl = 'https://api.trackingmore.com/v4/trackings';

    public function createTracking($trackingNumber, $courierCode = 'jtexpress-vn')
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Tracking-Api-Key' => $this->apiKey,
        ])->post($this->baseUrl . '/create', [
            'tracking_number' => $trackingNumber,
            'courier_code' => $courierCode,
        ]);
        return $response->json();
    }

    public function getTracking($trackingNumber, $courierCode = 'jtexpress-vn')
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Tracking-Api-Key' => $this->apiKey,
        ])->get($this->baseUrl . "/$courierCode/$trackingNumber");
        return $response->json();
    }
}
