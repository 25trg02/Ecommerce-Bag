<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $code = trim($request->input('coupon'));
        $cart = session('cart', []);
        if (!$code) {
            return back()->with('error', 'Vui lòng nhập mã giảm giá.');
        }
        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        // Kiểm tra nếu là voucher cá nhân
        if ($coupon->user_id && $coupon->user_id !== Auth::id()) {
            return back()->with('error', 'Mã voucher này không thuộc về bạn.');
        }

        if (!$coupon->isValid()) {
            return back()->with('error', 'Mã giảm giá đã hết hạn hoặc đã sử dụng hết.');
        }
        // Tăng số lần đã dùng
        $coupon->increment('used');
        // Lưu coupon vào session
        session(['applied_coupon' => $coupon->only(['id', 'code', 'type', 'value'])]);
        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }
    public function remove()
    {
        // Giảm số lần đã dùng nếu có coupon trong session
        $coupon = session('applied_coupon');
        if ($coupon && isset($coupon['id'])) {
            $model = \App\Models\Coupon::find($coupon['id']);
            if ($model && $model->used > 0) {
                $model->decrement('used');
            }
        }
        session()->forget('applied_coupon');
        return back()->with('success', 'Đã hủy mã giảm giá!');
    }
}
