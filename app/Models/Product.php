<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'features',
        'image',
        'category_id',
    ];

    // Quan hệ 1-1: Product thuộc về Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
}
