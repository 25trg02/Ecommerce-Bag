<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Xử lý lọc sản phẩm
        $query = \App\Models\Product::with('category');

        // Lọc theo danh mục
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Tìm kiếm gần đúng theo tên sản phẩm
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        // Sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            // Mặc định sắp xếp theo tên
            $query->orderBy('name', 'asc');
        }

        // Lấy danh sách sản phẩm, phân trang 8 sản phẩm mỗi trang
        $products = $query->paginate(8)->appends($request->all());

        return view('welcome', compact('products'));
    }
}
