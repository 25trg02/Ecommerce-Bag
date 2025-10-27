<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used',
        'expires_at',
        'user_id'
    ];
    public $timestamps = true;

    public function isValid()
    {
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }
        if ($this->max_uses !== null && $this->used >= $this->max_uses) {
            return false;
        }
        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
