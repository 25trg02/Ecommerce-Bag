<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShop</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .nav-link-btn {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            color: #007bff;
            cursor: pointer;
        }

        .nav-link-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('welcome') }}">MyShop</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                {{-- Trang chủ --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('welcome') }}">Trang chủ</a>
                </li>



            </ul>

            <ul class="navbar-nav">
                @auth
                {{-- Lịch sử đơn hàng --}}
                @if(Auth::user()->role === 'user')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.orders.index') }}">📦 Lịch sử đơn</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wishlist.index') }}">❤️ Wishlist</a>
                </li>
                @endif

                <li class="nav-item">
                    <span class="nav-link">👤 Xin chào, {{ Auth::user()->name }}</span>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="form-inline">
                        @csrf
                        <button type="submit" class="nav-link-btn nav-link">🚪 Đăng xuất</button>
                    </form>
                </li>
                @endauth

                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>