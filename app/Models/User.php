<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Coupon;
use App\Models\Product;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Các trường có thể gán giá trị hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Phân quyền
        'points', // Điểm tích lũy
    ];

    /**
     * Thêm điểm cho người dùng
     */
    public function addPoints(int $points)
    {
        $this->increment('points', $points);
    }

    /**
     * Trừ điểm khi đổi voucher
     */
    public function redeemPoints(int $points)
    {
        if ($this->points >= $points) {
            $this->decrement('points', $points);
            return true;
        }
        return false;
    }

    /**
     * Kiểm tra có đủ điểm để đổi không
     */
    public function canRedeem(int $points)
    {
        return $this->points >= $points;
    }

    /**
     * Relationship với coupons
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    // Wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the wishlist products for the user.
     */
    public function wishlistProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id');
    }
}
