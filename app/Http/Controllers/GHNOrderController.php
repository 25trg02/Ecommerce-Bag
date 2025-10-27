<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GHNTrackingService;

class GHNOrderController extends Controller
{
    /**
     * Hiển thị chi tiết đơn hàng GHN
     */
    public function showDetail($orderCode)
    {
        $ghnService = new GHNTrackingService();
        $order = $ghnService->getOrderDetail($orderCode);

        if (!$order) {
            return view('order-detail', ['error' => 'Không tìm thấy đơn hàng hoặc token sai!']);
        }

        return view('order-detail', ['order' => $order]);
    }
}
