<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the wishlist of the current user.
     */
    public function index(): \Illuminate\View\View
    {
        $user = User::findOrFail(Auth::id());
        $wishlists = $user->wishlistProducts()->with('category')->get();
        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = (int) $request->input('product_id');
        $user = User::findOrFail(Auth::id());

        // Atomically attach without creating duplicates
        $result = $user->wishlistProducts()->syncWithoutDetaching([$productId]);

        if (!empty($result['attached'])) {
            return response()->json(['success' => true, 'message' => 'Đã thêm vào wishlist']);
        }

        return response()->json(['success' => false, 'message' => 'Sản phẩm đã có trong wishlist']);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy(int $productId): \Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail(Auth::id());
        // Gỡ sản phẩm khỏi wishlist qua quan hệ many-to-many
        $user->wishlistProducts()->detach((int) $productId);
        return redirect()->back()->with('success', 'Đã xóa khỏi wishlist!');
    }
}
