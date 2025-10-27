<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của user hiện tại
    public function index()
    {
        $orders = Orders::where('user_id', Auth::id())->get();
        return view('orders.index', compact('orders'));
    }

    // Lưu đơn hàng từ giỏ hàng
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (count($cart) === 0) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn trống.');
        }

        // Tạo đơn hàng mới
        $order = Orders::create([
            'user_id'        => Auth::id(),
            'total'          => $request->total,
            'status'         => 'processing',
            'payment_method' => $request->payment_method,
        ]);

        // Lưu các mặt hàng trong đơn hàng
        foreach ($cart as $id => $details) {
            OrderItems::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $details['quantity'],
                'price'      => $details['price'],
            ]);
        }

        // Xóa giỏ hàng sau khi đặt hàng
        session()->forget('cart');

        return redirect()
            ->route('order.index')
            ->with('success', 'Đặt hàng thành công!');
    }
}
