<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;

    /**
     * Các trường có thể gán giá trị hàng loạt
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total_price',
        'status',
        'shipping_status',
        'payment_method',
    ];

    /**
     * Một đơn hàng thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Một đơn hàng có nhiều mục sản phẩm.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Override update method để tự động cộng điểm khi hoàn thành
     */
    public function update(array $attributes = [], array $options = [])
    {
        $oldShipping = $this->shipping_status;

        // Gọi parent update
        $result = parent::update($attributes, $options);

        // Kiểm tra nếu shipping_status thay đổi thành 'completed'
        if (isset($attributes['shipping_status']) && $attributes['shipping_status'] === 'completed' && $oldShipping !== 'completed') {
            $this->awardPointsOnCompletion();
        }

        return $result;
    }

    /**
     * Cộng điểm khi đơn hàng hoàn thành
     */
    protected function awardPointsOnCompletion()
    {
        // Kiểm tra xem đã cộng điểm chưa
        if ($this->user && !$this->user->coupons()->where('code', 'points_earned_' . $this->id)->exists()) {
            // Tính điểm: 1000đ = 1 điểm
            $pointsEarned = floor($this->total_price / 1000);

            if ($pointsEarned > 0) {
                $this->user->addPoints($pointsEarned);

                // Tạo marker để đánh dấu đã cộng điểm
                Coupon::create([
                    'user_id' => $this->user_id,
                    'code' => 'points_earned_' . $this->id,
                    'type' => 'points_marker',
                    'value' => 0,
                    'max_uses' => 1,
                    'used' => 1,
                    'expires_at' => now()->addYears(10),
                ]);

                // Log để theo dõi
                Log::info("Đã tự động cộng {$pointsEarned} điểm cho đơn hàng #{$this->id}, user: {$this->user->name}");
            }
        }
    }
}
