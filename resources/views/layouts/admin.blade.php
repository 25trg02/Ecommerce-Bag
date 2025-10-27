<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Quản Lý Túi Xách Nữ')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #5e72e4;
            --secondary-color: #f5f5f5;
            --text-color: #344767;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-weight: 700;
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
            margin-top: 20px;
        }

        .sidebar-menu li {
            padding: 10px 20px;
            margin-bottom: 5px;
        }

        .sidebar-menu li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li.active a {
            color: white;
            transform: translateX(5px);
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--text-color);
        }

        .nav-link {
            color: var(--text-color);
            margin-right: 15px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            font-weight: 700;
        }

        .icon-shape {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        /* Tables */
        .table thead th {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            padding: 15px 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody td {
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.65rem;
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 15px 20px;
            text-align: center;
            margin-top: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content.active {
                margin-left: var(--sidebar-width);
            }
        }

        /* Chart */
        .chart-canvas {
            min-height: 300px;
        }

        /* List Group */
        .list-group-item {
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>LADY BAGS</h3>
            <p class="text-white-50 mb-0">Admin Dashboard</p>
        </div>
        <ul class="sidebar-menu">
            <li class="active">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Quản Lý Sản Phẩm</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-list"></i>
                    <span>Quản Lý Danh Mục</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-receipt"></i>
                    <span>Quản Lý Đơn Hàng</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.coupons.index') }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Quản Lý Mã Giảm Giá</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>Báo Cáo</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.charts') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Biểu đồ</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Quản Lý Người Dùng</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng Xuất</span>
                </a>
            </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn btn-sm btn-outline-primary d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-handbag me-2"></i> Quản Lý Túi Xách Nữ
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="user-profile me-2">
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=5E72E4&color=fff" alt="User">
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Tài khoản</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12">
                        <p class="mb-0">
                            &copy; {{ date('Y') }} <strong>Lady Bags</strong>. Thiết kế bởi <a href="#" class="text-primary">NGUYỄN VĂN ĐẠT</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>

    @yield('scripts')
</body>

</html>