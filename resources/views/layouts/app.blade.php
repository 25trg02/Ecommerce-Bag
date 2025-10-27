<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LADY BAG')</title>

    <!-- Bootstrap 4 CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: linear-gradient(90deg, #f8f9fa 0%, #e3e6ea 100%);
            border-radius: 0 0 16px 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .navbar-custom .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #d63384;
            letter-spacing: .5px;
        }

        .navbar-custom .navbar-nav {
            margin: 0 auto;
        }

        .navbar-custom .nav-item {
            display: flex;
            align-items: center;
        }

        .navbar-custom .navbar-nav .nav-link {
            color: #495057;
            margin: 0 8px;
            border-radius: 8px;
            transition: background .2s, color .2s;
        }

        .navbar-custom .navbar-nav .nav-link:hover,
        .navbar-custom .navbar-nav .nav-link.active {
            background: #d63384;
            color: #fff;
        }

        /* ===== FOOTER ===== */
        .site-footer {
            position: relative;
            background: radial-gradient(1200px 600px at 10% -10%, #ffe6f2 0%, rgba(255, 230, 242, 0) 35%),
                radial-gradient(1000px 500px at 110% 10%, #e6f0ff 0%, rgba(230, 240, 255, 0) 40%),
                #0f1220;
            color: #e9ecf1;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .site-footer .footer-glass {
            backdrop-filter: blur(6px);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.25);
        }

        .site-footer h5,
        .site-footer h6 {
            color: #ffffff;
            letter-spacing: .5px;
            margin-bottom: .75rem;
            font-weight: 700;
        }

        .footer-brand {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: 1px;
            color: #ff5ca8;
        }

        .footer-link,
        .site-footer a {
            color: #cfd6e4;
            transition: color .2s ease, opacity .2s ease;
            text-decoration: none;
        }

        .footer-link:hover,
        .site-footer a:hover {
            color: #ffffff;
        }

        .footer-underline a {
            position: relative;
        }

        .footer-underline a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0;
            height: 2px;
            background: #ff5ca8;
            transition: width .25s ease;
        }

        .footer-underline a:hover::after {
            width: 100%;
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
            margin-right: .5rem;
            transition: transform .15s ease, background .2s ease;
            color: #e9ecf1;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .footer-input {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e9ecf1;
        }

        .footer-input::placeholder {
            color: #aeb7c8;
        }

        .footer-btn {
            background: linear-gradient(90deg, #ff5ca8, #a855f7);
            border: none;
            color: #fff;
            font-weight: 600;
        }

        .footer-btn:hover {
            filter: brightness(1.05);
        }

        .footer-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .payment-icons i {
            opacity: .75;
            margin-right: .5rem;
            font-size: 1.25rem;
        }

        .payment-icons i:hover {
            opacity: 1;
        }

        @media (max-width: 576px) {
            .footer-grid {
                padding: 1rem !important;
            }
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <a class="navbar-brand" href="{{ url('/') }}">LADY BAG</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                {{-- Trang chủ (ẩn với admin nếu muốn) --}}
                @if(Auth::guest() || Auth::user()->role !== 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        Home <span class="sr-only">(current)</span>
                    </a>
                </li>
                @endif

                {{-- Dashboard (Admin) --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                @endif

                {{-- Giỏ hàng (User) --}}
                @if(Auth::guest() || Auth::user()->role !== 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}"
                        href="{{ route('cart.index') }}">Giỏ hàng</a>
                </li>
                @endif


                {{-- Quản lý sản phẩm (Admin page link) --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                        href="{{ route('admin.products.index') }}">Sản phẩm</a>
                </li>

                {{-- Quản lý mã giảm giá (Admin) --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
                        href="{{ route('admin.coupons.index') }}">Mã giảm giá</a>
                </li>
                @endif

                {{-- Lịch sử đơn (User) --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}"
                        href="{{ route('user.orders.index') }}">Lịch sử đơn</a>
                </li>

                {{-- Tích điểm (User đã đăng nhập) --}}
                @if(Auth::check() && Auth::user()->role !== 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.loyalty.*') ? 'active' : '' }}"
                        href="{{ route('user.loyalty.index') }}">
                        <i class="fas fa-star text-warning mr-1"></i>Tích điểm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wishlist.index') }}">❤️ Wishlist</a>
                </li>
                @endif

                {{-- Auth links --}}
                @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @else
                <li class="nav-item"><span class="nav-link">Hello, {{ Auth::user()->name }}</span></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="site-footer mt-5 pt-4 pb-3">
        <div class="container">
            <div class="footer-glass p-4 p-md-5">
                <div class="row footer-grid">
                    <!-- Cột 1: Thương hiệu + đăng ký nhận tin -->
                    <div class="col-md-5 mb-4">
                        <div class="footer-brand mb-2">LADY BAG</div>
                        <p class="mb-3 small">
                            Khám phá thế giới túi xách nữ cao cấp: thanh lịch, tối giản và thời thượng.
                        </p>
                        <form action="{{ route('subscribe') }}" method="post" class="form-inline">
                            @csrf
                            <div class="form-group mb-2 mr-sm-2 flex-grow-1" style="min-width:220px">
                                <input type="email" class="form-control w-100 footer-input"
                                    placeholder="Nhập email để nhận ưu đãi">
                            </div>
                            <button type="submit" class="btn footer-btn mb-2">Đăng ký</button>
                        </form>
                        @if(session('message'))
                        <div class="alert alert-success mt-2">
                            {{ session('message') }}
                        </div>
                        @endif
                        <div class="mt-3 small text-muted">
                            <i class="fa-solid fa-location-dot mr-2"></i>QUỐC OAI, HÀ NỘI<br>
                            <i class="fa-solid fa-phone mr-2"></i>Hotline:
                            <a href="tel:0123456789" class="footer-link">0123 456 789</a><br>
                            <i class="fa-solid fa-envelope mr-2"></i>Email:
                            <a href="mailto:ladybag@example.com" class="footer-link">ladybag@example.com</a>
                        </div>
                    </div>

                    <!-- Cột 2: Liên kết nhanh -->
                    <div class="col-md-4 mb-4 footer-underline">
                        <h6>Liên kết nhanh</h6>
                        <div class="row">
                            <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="{{ url('/') }}">Trang chủ</a></li>
                                    <li class="mb-2"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                                    <li class="mb-2"><a href="{{ route('user.orders.index') }}">Lịch sử đơn</a></li>
                                    <li class="mb-2"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="{{ route('bo-suu-tap') }}">Bộ sưu tập</a></li>
                                    <li class="mb-2"><a href="{{ route('ve-lady-bag') }}">Về Lady Bag</a></li>
                                    <li class="mb-2"><a href="{{ route('chinh-sach-doi-tra') }}">Chính sách đổi trả</a></li>
                                    <li class="mb-2"><a href="{{ route('lien-he') }}">Liên hệ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Cột 3: Mạng xã hội & thanh toán -->
                    <div class="col-md-3 mb-4">
                        <h6>Theo dõi chúng tôi</h6>
                        <div class="mb-3">
                            <a class="social-btn" href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a class="social-btn" href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a class="social-btn" href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                        <h6 class="mt-4">Thanh toán</h6>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa" aria-hidden="true"></i>
                            <i class="fab fa-cc-mastercard" aria-hidden="true"></i>
                            <i class="fab fa-cc-paypal" aria-hidden="true"></i>
                            <i class="fa-solid fa-qrcode" title="QR/MoMo"></i>
                        </div>
                    </div>
                </div>

                <hr class="footer-divider my-4">

                <!-- Bản quyền -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="small text-muted order-2 order-md-1 mt-2 mt-md-0">
                        &copy; {{ date('Y') }} Lady Bag. All rights reserved.
                    </div>
                    <div class="small footer-underline order-1 order-md-2">
                        <a href="#" class="mr-3">Điều khoản</a>
                        <a href="#" class="mr-3">Bảo mật</a>
                        <a href="#">Cookie</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JS: jQuery, Popper, Bootstrap 4 -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>