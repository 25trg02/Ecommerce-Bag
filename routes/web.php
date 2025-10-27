
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Import controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Coupon routes
use App\Http\Controllers\CouponController;

Route::post('/cart/apply-coupon', [CouponController::class, 'apply'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CouponController::class, 'remove'])->name('cart.removeCoupon');

// Đăng ký và đăng nhập người dùng
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

// ✅ Hiển thị thông báo xác thực email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// ✅ Xử lý link xác nhận (từ email)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('welcome'); // Redirect về trang chủ
})->middleware(['auth', 'signed'])->name('verification.verify');

// ✅ Gửi lại email xác nhận
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route dành cho admin (có middleware kiểm tra quyền)
use App\Http\Controllers\Admin\CouponAdminController;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Quản lý sản phẩm
    Route::post('/admin/orders/tracking', [\App\Http\Controllers\Admin\OrderController::class, 'tracking'])->name('admin.orders.tracking');
    Route::resource('/admin/products', ProductController::class, [
        'as' => 'admin' // prefix 'admin' cho tất cả route của products
    ]);

    // Quản lý danh mục
    Route::resource('/admin/categories', CategoryController::class, [
        'as' => 'admin' // prefix 'admin' cho tất cả route của categories
    ]);

    // Quản lý mã giảm giá
    Route::resource('/admin/coupons', CouponAdminController::class, [
        'as' => 'admin'
    ]);
});

// Route cho người dùng bình thường
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/{product}', [ProductController::class, 'showNormal'])
        ->name('products.show');
});


// Route demo tra cứu trạng thái GHN
use App\Http\Controllers\GHNTrackingController;

Route::get('/ghn-tracking', [GHNTrackingController::class, 'track'])->name('ghn.tracking');

use App\Http\Controllers\CartController;

Route::middleware(['auth'])->post(
    '/cart/add/{product}',
    [CartController::class, 'add']
)->name('cart.add');

// Route để hiển thị giỏ hàng
Route::middleware(['auth'])->get(
    '/cart',
    [CartController::class, 'index']
)->name('cart.index');

// Route cho user.cart.index (alias cho cart.index)
Route::middleware(['auth'])->get(
    '/user/cart',
    [CartController::class, 'index']
)->name('user.cart.index');

// Route để xóa sản phẩm khỏi giỏ hàng
Route::middleware(['auth'])->delete(
    '/cart/remove/{product}',
    [CartController::class, 'remove']
)->name('cart.remove');

// Route để cập nhật số lượng sản phẩm trong giỏ hàng
Route::middleware(['auth'])->patch(
    '/cart/{id}',
    [CartController::class, 'update']
)->name('cart.update');

// Đặt hàng (chỉ cho user đã đăng nhập)


use App\Http\Controllers\User\OrderController as OrderController;

// ================== Thanh toán (OrderController xử lý cả COD & MoMo) ==================
Route::get('/payment', [OrderController::class, 'index'])
    ->name('payment.index');

Route::post('/payment/process', [OrderController::class, 'processPayment'])
    ->name('payment.process');

// ================== Thanh toán lại MoMo cho một đơn đã tạo ==================
Route::get('/orders/{order}/pay/momo', [OrderController::class, 'payAgain'])
    ->name('orders.momo.pay');

// ================== Tạo request thanh toán MoMo (ít dùng trực tiếp) ==================
Route::post('/payment/momo', [OrderController::class, 'momo_payment'])
    ->name('payment.momo');

// ================== Callback: user được MoMo redirect về sau khi thanh toán ==================
Route::get('/payment/momo/callback', [OrderController::class, 'callback'])
    ->name('payment.momo.callback');

// ================== IPN: MoMo gọi server-to-server để cập nhật trạng thái đơn ==================
Route::post('/payment/momo/ipn', [OrderController::class, 'ipn'])
    ->name('payment.momo.ipn');

// ================== Lịch sử đơn hàng và xem chi tiết ==================
Route::get('/orders', [OrderController::class, 'orderHistory'])
    ->name('orders.index');

Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->name('orders.show');

// Route để cập nhật thông tin giỏ hàng (sử dụng cho việc cập nhật số lượng sản phẩm)
// Chuẩn RESTful: PATCH cho update, POST để hỗ trợ legacy
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('user.cart.update');
Route::post('/cart/update/{id}', [CartController::class, 'update']);
Route::post('/cart/remove', [CartController::class, 'remove'])->name('user.cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('user.cart.clear');

// Route cho trang thanh toán của user (fix lỗi user.payment.index)
use App\Http\Controllers\User\OrderController as UserOrderController;

Route::get('/user/payment', [UserOrderController::class, 'index'])->name('user.payment.index');
// Route xử lý thanh toán cho user (fix lỗi user.payment.process)
Route::post('/user/payment/process', [UserOrderController::class, 'processPayment'])->name('user.payment.process');

// Route cho lịch sử đơn hàng của user (fix lỗi user.orders.index)
Route::get('/user/orders', [UserOrderController::class, 'orderHistory'])->name('user.orders.index');

// Route callback MoMo cho user (fix lỗi user.payment.momo.callback)
Route::get('/user/payment/momo/callback', [UserOrderController::class, 'callback'])->name('user.payment.momo.callback');
// Route callback MoMo cho user (fix lỗi user.payment.momo.callback)
Route::get('/user/payment/momo/callback', [UserOrderController::class, 'callback'])->name('user.payment.momo.callback');

// Route IPN MoMo cho user (fix lỗi user.payment.momo.ipn)
Route::post('/user/payment/momo/ipn', [UserOrderController::class, 'ipn'])->name('user.payment.momo.ipn');


// Cho ADMIN
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
// Route dành cho admin (sử dụng middleware để kiểm tra quyền)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Các route khác đã có...

        // Route quản lý đơn hàng
        Route::resource('orders', AdminOrderController::class);
        // Route cộng điểm khi hoàn thành đơn hàng
        Route::post('orders/{order}/award-points', [AdminOrderController::class, 'awardPoints'])->name('orders.award-points');
    });


use App\Http\Controllers\Admin\ReportController;
// Route quản lý báo cáo
Route::get('/admin/reports', [ReportController::class, 'index'])
    ->name('admin.reports.index');
Route::get('/admin/reports/charts', [ReportController::class, 'charts'])
    ->name('admin.reports.charts');


// ================== Trang tĩnh ==================
Route::view('/bo-suu-tap', 'bo-suu-tap')->name('bo-suu-tap');
Route::view('/ve-lady-bag', 've-lady-bag')->name('ve-lady-bag');
Route::view('/chinh-sach-doi-tra', 'chinh-sach-doi-tra')->name('chinh-sach-doi-tra');
Route::view('/lien-he', 'lien-he')->name('lien-he');

// Đăng ký nhận ưu đãi qua email
use App\Http\Controllers\SubscriberController;

Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscribe');

use App\Http\Controllers\Admin\UserController as AdminUserController;
// Route dành cho admin (sử dụng middleware để kiểm tra quyền)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Các route khác đã có...

        // Route quản lý người dùng
        Route::resource('users', AdminUserController::class);
    });

// Route cho loyalty
use App\Http\Controllers\User\LoyaltyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('user.loyalty.index');
    Route::post('/loyalty/redeem', [LoyaltyController::class, 'redeem'])->name('user.loyalty.redeem');
});

// ================== Route thêm sản phẩm vào wishlist ==================
Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
// Route wishlist cho user
Route::middleware(['auth'])->get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

// Xóa sản phẩm khỏi wishlist
Route::middleware(['auth'])->delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

// GHN Order Detail
use App\Http\Controllers\GHNOrderController;

Route::get('/ghn-order/{orderCode}', [GHNOrderController::class, 'showDetail'])->name('ghn.order.detail');
