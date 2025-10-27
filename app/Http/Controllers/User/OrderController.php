<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TrackingMoreService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Exception;

class OrderController extends Controller
{
    // Đặt cấu hình thông số MoMo test
    private $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey = 'klm05TvNBzhg7h7j';
    private $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

    // giới hạn thao tác: nếu có sản phẩm => truyền đến trang checkout, nếu không có => ở lại giỏ hàng
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $discount = 0;
        $coupon = session('applied_coupon');
        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $discount = $total * $coupon['value'] / 100;
            } else {
                $discount = $coupon['value'];
            }
            if ($discount > $total) $discount = $total;
        }
        $total_after_discount = $total - $discount;
        if ($total_after_discount < 0) $total_after_discount = 0;
        return view('user.payment.index', compact('cart', 'total', 'discount', 'total_after_discount', 'coupon'));
    }

    // nhận thao tác thanh toán từ form rồi điều hướng kết quả momo hay COD
    public function processPayment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,momo',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Không thể thanh toán vì giỏ hàng trống.');
        }

        // Lấy tổng tiền đã giảm giá từ form (đã validate min:0)
        $total = $request->total_price;

        // Tạo đơn
        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'total_price' => $total,
            'status' => 'Chờ thanh toán',
            'payment_method' => $request->payment_method === 'momo' ? 'online' : 'COD',
        ]);

        // Lưu chi tiết đơn
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            // Trừ số lượng sản phẩm trong kho
            $product = \App\Models\Product::find($id);
            if ($product) {
                $product->quantity = max(0, $product->quantity - $item['quantity']);
                $product->save();
            }
        }

        // XÓA GIỎ HÀNG NGAY KHI NHẤN THANH TOÁN (kể cả MoMo chưa thành công)
        session()->forget('cart');

        // Rẽ nhánh phương thức
        if ($request->payment_method === 'momo') {
            // (tuỳ chọn) nếu muốn phản ánh trạng thái đang thanh toán:
            // $order->update(['status' => 'đang thanh toán (MoMo)']);
            return $this->redirectToMoMo($order);
        }

        // COD
        $order->update([
            'status' => 'cod_ordered', // Đồng bộ với hệ thống báo cáo và migration
            // Nếu có cột payment_status:
            // 'payment_status' => 'unpaid',
        ]);

        return redirect()->route('user.orders.index')->with('success', 'Đặt hàng thành công! Thanh toán khi nhận hàng.');
    }

    /**
     * Tạo giao dịch MoMo và chuyển hướng người dùng
     */
    protected function redirectToMoMo(Order $order)
    {
        $redirectUrl = route('user.payment.momo.callback');
        $ipnUrl = route('user.payment.momo.ipn');
        $orderId = time() . '_' . $order->id;
        $requestId = uniqid();
        $orderInfo = "Thanh toán đơn hàng #{$order->id}";
        $amount = (string) max(1000, (int) $order->total_price); // test nên >= 1000
        $extraData = ''; // có thể base64_encode(json_encode(...))
        $requestType = 'payWithATM';

        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}"
            . "&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}"
            . "&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";

        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => "YourStore",
            'storeId' => "Store_01",
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

        Log::info('MoMo request payload: ', $payload);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
                ->withoutVerifying()
                ->post($this->endpoint, $payload);

            if (!$response->successful()) {
                Log::error('MoMo create payment failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return redirect()->route('user.orders.index')->with('error', 'Không thể kết nối MoMo (' . $response->status() . '). Vui lòng thử lại.');
            }

            $json = $response->json();
            Log::info('MoMo response:', $json);

            if (!empty($json['payUrl'])) {
                // $order->update([
                //     'momo_request_id' => $requestId,
                //     'momo_order_id' => $orderId,
                // ]);
                return redirect()->away($json['payUrl']);
            }

            // Không có payUrl báo lỗi rõ
            $msg = $json['message'] ?? 'MoMo không trả về payUrl.';
            Log::error('MoMo payUrl missing', ['response' => $json]);
            return redirect()->route('user.orders.index')->with('error', 'Không tạo được link thanh toán MoMo: ' . $msg);
        } catch (Exception $e) {
            Log::error('MoMo request exception', ['error' => $e->getMessage()]);
            return redirect()->route('user.orders.index')->with('error', 'Lỗi khi tạo thanh toán MoMo: ' . $e->getMessage());
        }
    }

    /**
     * Callback: người dùng được MoMo chuyển về sau thanh toán
     */
    public function callback(Request $request)
    {
        $resultCode = $request->input('resultCode'); // 0 = success
        $order = null;

        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);
            $order = Order::find($orderId);
        }

        if ($resultCode === '0' || $resultCode === 0) {
            // Thành công: cập nhật trạng thái đơn
            if ($order) {
                $order->update([
                    'status' => 'paid_momo',
                    'payment_method' => 'online',
                ]);
            }
            return redirect()->route('user.orders.index')->with('success', 'Thanh toán MoMo thành công!');
        }

        // Thất bại/hủy
        if ($order) {
            $order->update([
                'status' => 'Thanh toán MoMo không thành công',
                'payment_method' => 'online',
            ]);
        }

        return redirect()->route('user.payment.index')->with('error', 'Thanh toán MoMo thất bại hoặc bị hủy. Vui lòng thử lại.');
    }

    /**
     * IPN: MoMo gọi ngầm (server-to-server) báo trạng thái
     */
    public function ipn(Request $request)
    {
        Log::info('MoMo IPN payload:', $request->all());
        // TODO: bạn nên xác thực chữ ký ở đây

        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);
            if ($order = Order::find($orderId)) {
                if ((string)($request->resultCode) === '0') {
                    $order->update([
                        'status' => 'paid_momo',
                        'payment_method' => 'online',
                    ]);
                } else {
                    $order->update([
                        'status' => 'Thanh toán thất bại (MoMo)',
                        'payment_method' => 'online',
                    ]);
                }
            }
        }
        return response()->json(['resultCode' => 0, 'message' => 'Received']);
    }

    // Cho phép user kéo lại đơn chưa thanh toán đi MoMo lần nữa
    public function payAgain(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thanh toán lại đơn này.');
        }
        if ($order->status === 'paid_momo') {
            return redirect()->route('user.orders.index')->with('info', 'Đơn này đã thanh toán.');
        }

        $order->update(['status' => 'Chờ thanh toán']);
        return $this->redirectToMoMo($order);
    }

    // gọi lịch sử các đơn hàng theo người dùng
    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Fake tracking GHN cho từng đơn hàng nếu là mã test
        foreach ($orders as $order) {
            if (!empty($order->order_code) && $order->order_code === '5ENLKKHD') {
                $order->tracking = [
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
                $order->tracking = null;
            }
        }
        return view('orders.history', compact('orders'));
    }

    // gọi chi tiết sản phẩm từng đơn hàng
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }
        $order->load('items.product');

        // Fake tracking GHN nếu là mã test
        $tracking = null;
        if (!empty($order->order_code)) {
            if ($order->order_code === '5ENLKKHD') {
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
        return view('user.payment.show', compact('order', 'tracking'));
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
}
