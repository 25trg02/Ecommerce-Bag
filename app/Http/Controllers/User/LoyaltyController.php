<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\User;

class LoyaltyController extends Controller
{
    /**
     * Hiển thị trang tích điểm và voucher
     */
    public function index()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user) {
            return redirect()->route('login');
        }

        $points = (int) $user->points;
        $coupons = $user->coupons()->where('type', 'voucher')->get();

        return view('user.loyalty.index', compact('points', 'coupons'));
    }

    /**
     * Đổi điểm thành voucher
     */
    public function redeem(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1000',
        ]);

        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user) {
            return redirect()->route('login');
        }

        $points = (int) $request->input('points');

        if (!$user->canRedeem($points)) {
            return back()->with('error', 'Bạn không đủ điểm để đổi.');
        }

        // Tính giá trị voucher: 1000 điểm = 10,000đ
        $voucherValue = (int) (($points / 1000) * 10000);

        try {
            DB::beginTransaction();

            // Tạo voucher
            $coupon = Coupon::create([
                'user_id' => $user->id,
                'code' => 'VOUCHER_' . strtoupper(uniqid()),
                'type' => 'voucher',
                'value' => $voucherValue,
                'max_uses' => 1,
                'used' => 0,
                'expires_at' => Carbon::now()->addMonths(6), // Hết hạn sau 6 tháng
            ]);

            // Trừ điểm
            $user->redeemPoints($points);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi đổi điểm thành voucher: ' . $e->getMessage(), ['user_id' => $user->id ?? null]);
            return back()->with('error', 'Đổi điểm thất bại. Vui lòng thử lại.');
        }

        return back()->with('success', 'Đổi điểm thành công! Voucher: ' . $coupon->code . ' trị giá ' . number_format($voucherValue) . 'đ');
    }
}
