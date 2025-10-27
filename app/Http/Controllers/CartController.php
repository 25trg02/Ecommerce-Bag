<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart = session()->get('cart', []);
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
        return view('user.cart.index', compact('cart', 'total', 'discount', 'total_after_discount', 'coupon'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1); // Lấy số lượng từ request, mặc định là 1

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity; // Cộng dồn số lượng
        } else {
            $cart[$product->id] = [
                "name"     => $product->name,
                "quantity" => $quantity, // Sử dụng số lượng từ request
                "price"    => $product->price,
                "category" => $product->category->name,
                "image"    => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->input('id');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()
                ->route('user.cart.index')
                ->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        }
        return redirect()
            ->route('user.cart.index')
            ->with('error', 'Sản phẩm không tồn tại trong giỏ hàng!');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');

        if (!isset($cart[$id])) {
            return redirect()
                ->route('user.cart.index')
                ->with('error', 'Sản phẩm không tồn tại trong giỏ hàng!');
        }

        if (!is_numeric($quantity) || $quantity < 1) {
            return redirect()
                ->route('user.cart.index')
                ->with('error', 'Số lượng không hợp lệ!');
        }

        $cart[$id]['quantity'] = (int) $quantity;
        session()->put('cart', $cart);

        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Giỏ hàng đã được cập nhật!');
    }
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}
