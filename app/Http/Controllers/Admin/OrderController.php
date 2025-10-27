<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\GHNTrackingService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đơn hàng cho admin.
     */
    public function index()
    {
        // Lấy tất cả đơn hàng để hiển thị
        $orders = Order::all();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Cập nhật trạng thái giao hàng của một đơn hàng.
     */
    public function update(Request $request, $id)
    {
        // Tìm đơn hàng theo ID, 404 nếu không thấy
        $order = Order::findOrFail($id);

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'shipping_status' => 'required|in:packaged,shipping,completed,cancelled',
            'order_code' => 'nullable|string',
            'status' => 'nullable|in:Chờ thanh toán,cod_ordered,paid_momo,completed,cancelled', // Thêm validation cho status
        ]);

        $updateData = [
            'shipping_status' => $validated['shipping_status'],
        ];

        // Cập nhật status chính nếu được cung cấp
        if ($request->filled('status')) {
            $updateData['status'] = $validated['status'];
        }

        // Cho phép cập nhật mã vận đơn bất kỳ lúc nào nếu có nhập
        if ($request->filled('order_code')) {
            $updateData['order_code'] = $request->input('order_code');
        }
        $order->update($updateData);

        return redirect()
            ->back()
            ->with('success', 'Cập nhật trạng thái/mã vận đơn thành công!');
    }

    /**
     * Cộng điểm cho đơn hàng khi hoàn thành
     */
    public function awardPoints(Order $order)
    {
        // Kiểm tra quyền admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        // Kiểm tra trạng thái đơn hàng
        if ($order->status !== 'completed') {
            return back()->with('error', 'Chỉ có thể cộng điểm cho đơn hàng đã hoàn thành.');
        }

        // Kiểm tra xem đã cộng điểm chưa
        if ($order->user->coupons()->where('code', 'points_earned_' . $order->id)->exists()) {
            return back()->with('warning', 'Đơn hàng này đã được cộng điểm rồi.');
        }

        // Tính điểm và cộng
        $pointsEarned = floor($order->total_price / 1000);
        if ($pointsEarned > 0) {
            $order->user->addPoints($pointsEarned);

            // Tạo coupon để đánh dấu đã cộng điểm (để tránh trùng)
            \App\Models\Coupon::create([
                'user_id' => $order->user_id,
                'code' => 'points_earned_' . $order->id,
                'type' => 'points_marker', // marker để đánh dấu đã cộng điểm
                'value' => 0,
                'max_uses' => 1,
                'used' => 1, // đánh dấu đã sử dụng
                'expires_at' => now()->addYears(10), // lâu dài
            ]);

            return back()->with('success', "Đã cộng {$pointsEarned} điểm cho đơn hàng #{$order->id}");
        }

        return back()->with('info', 'Đơn hàng không đủ điều kiện để cộng điểm.');
    }
    /**
     * Hiển thị chi tiết một đơn hàng cho admin.
     */
    public function show($id)
    {
        $order = Order::with('items', 'user')->findOrFail($id);
        $tracking = null;
        if (!empty($order->order_code)) {
            if ($order->order_code === '5ENLKKHD') {
                // Fake dữ liệu tracking GHN
                $tracking = [
                    'order_code' => '5ENLKKHD',
                    'client_order_code' => 'Tin1234567',
                    'status' => 'return',
                    'to_name' => 'Tindeptrai',
                    'to_phone' => '0987654321',
                    'to_address' => '48 Bùi Thị Xuân',
                    'created_date' => '2021-11-11T03:04:23.928Z',
                    'updated_date' => '2021-11-11T03:07:56.882Z',
                    'note' => '',
                    'log' => [
                        ['status' => 'picking', 'updated_date' => '2021-11-11T03:04:48.053Z'],
                        ['status' => 'picked', 'updated_date' => '2021-11-11T03:04:48.053Z'],
                        ['status' => 'delivering', 'updated_date' => '2021-11-11T03:07:47.245Z'],
                        ['status' => 'delivery_fail', 'updated_date' => '2021-11-11T03:07:53.554Z'],
                        ['status' => 'waiting_to_return', 'updated_date' => '2021-11-11T03:07:53.64Z'],
                        ['status' => 'return', 'updated_date' => '2021-11-11T03:07:56.882Z'],
                    ],
                ];
            } else {
                $tracking = null;
            }
        }
        return view('admin.orders.show', compact('order', 'tracking'));
    }

    /**
     * Tra cứu vận đơn qua TrackingMore từ form nhập mã vận đơn.
     */
    public function tracking(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
        ]);
        $orderCode = $request->input('order_code');
        $apiKey = '78sefm45-evh8-062f-huzf-geyrwq9omhyr';
        $courierCode = 'spx-vn';
        try {
            // Bước 1: Đăng ký tracking nếu chưa có (API v4)
            $postResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Tracking-Api-Key' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.trackingmore.com/v4/trackings/create', [
                'tracking_number' => $orderCode,
                'courier_code' => $courierCode,
            ]);
            // Bước 2: Lấy trạng thái tracking (API v4)
            $getResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Tracking-Api-Key' => $apiKey,
                'Accept' => 'application/json',
            ])->get('https://api.trackingmore.com/v4/trackings/get', [
                'tracking_number' => $orderCode,
                'courier_code' => $courierCode,
            ]);
            $data = $getResponse->json();
            if (isset($data['data']['delivery_status'])) {
                $html = '<b>Mã vận đơn:</b> ' . $orderCode . '<br>';
                $html .= '<b>Trạng thái:</b> ' . ($data['data']['delivery_status'] ?? '-') . '<br>';
                $html .= '<b>Sự kiện mới nhất:</b> ' . ($data['data']['latest_event'] ?? '-') . '<br>';
                if (!empty($data['data']['origin_info']['trackinfo'])) {
                    $html .= '<hr><b>Lịch sử:</b><ul style="padding-left:18px;">';
                    foreach ($data['data']['origin_info']['trackinfo'] as $item) {
                        $html .= '<li>' . ($item['checkpoint_date'] ?? '') . ': ' . ($item['tracking_detail'] ?? '') . '</li>';
                    }
                    $html .= '</ul>';
                }
                return redirect()->back()->with('tracking_result', $html);
            } else {
                return redirect()->back()->with('tracking_error', 'Không tìm thấy thông tin vận đơn hoặc mã không hợp lệ.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('tracking_error', 'Lỗi khi tra cứu vận đơn: ' . $e->getMessage());
        }
    }
}
