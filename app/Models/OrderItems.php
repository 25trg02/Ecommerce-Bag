<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use App\Models\Product;

// OrderItems Model
class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Một mục sản phẩm thuộc về một đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    /**
     * Một mục sản phẩm liên kết với một sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
