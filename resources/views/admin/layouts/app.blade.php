<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'LadyBag')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/admin/dashboard">LadyBag Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="/admin/products">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/categories">Danh mục</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/coupons">Mã giảm giá</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="/">Về trang bán hàng</a></li>
            </ul>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>
</body>

</html>