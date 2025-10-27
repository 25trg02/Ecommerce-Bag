<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Hiển thị trang dashboard của Admin
     */
    public function dashboard()
    {
        // Lấy số lượng thống kê cho dashboard
        $productCount = Product::count();
        $categoryCount = Category::count();
        $userCount = User::count();

        // Có thể thêm dữ liệu về sản phẩm bán chạy, doanh thu, v.v. ở đây

        return view('admin.dashboard', [
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'userCount' => $userCount,
        ]);
    }

    /**
     * Quản lý sản phẩm
     */
    public function products()
    {
        return app(ProductController::class)->index();
    }

    /**
     * Quản lý danh mục
     */
    public function categories()
    {
        return app(CategoryController::class)->index();
    }
}
