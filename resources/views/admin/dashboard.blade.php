@extends('layouts.admin')

@section('title', 'Dashboard Quản Lý Túi Xách Nữ')

@section('content')
<style>
    :root {
        --primary: #7465DD;
        /* Màu chủ đạo */
        --primary-600: #5f50c5;
        --primary-light: #ecebff;
        --text: #2b2b2b;
        --bg-soft: #f9f9fc;
    }

    .soft-card {
        border: 0;
        border-radius: 1.25rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
        background: white;
    }

    .kpi {
        transition: .2s transform ease;
    }

    .kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(116, 101, 221, 0.15);
    }

    .kpi .icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--primary-light);
        color: var(--primary);
    }

    .kpi .value {
        font-weight: 800;
    }

    .link-action {
        font-weight: 600;
        text-decoration: none;
        color: var(--primary);
    }

    .link-action:hover {
        text-decoration: underline;
    }

    .btn-primary-custom {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: .75rem;
        padding: .45rem .9rem;
        font-weight: 700;
    }

    .btn-primary-custom:hover {
        background: var(--primary-600);
        color: #fff;
    }

    .welcome-band {
        background: linear-gradient(135deg, var(--primary-light), #ffffff);
        border-radius: 1.25rem;
    }
</style>

<div class="container-fluid py-4">
    {{-- Welcome --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card soft-card welcome-band">
                <div class="card-body p-4">
                    <h4 class="mb-1 fw-bold" style="color:var(--primary)">
                        Xin chào, {{ Auth::user()->name }} 👋
                    </h4>
                    <p class="mb-0 text-muted">
                        Chào mừng đến hệ thống Quản lý Túi Xách Nữ. Hãy chọn một mục trong menu hoặc dùng tác vụ nhanh bên dưới.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI --}}
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card soft-card kpi">
                <div class="card-body p-3 d-flex justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted mb-1 fw-bold">Tổng Sản Phẩm</p>
                        <div class="value h4 mb-2">{{ number_format($productCount ?? 0) }}</div>
                        <a href="{{ route('admin.products.index') }}" class="link-action">
                            <i class="fas fa-arrow-right me-1"></i> Quản lý sản phẩm
                        </a>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card soft-card kpi">
                <div class="card-body p-3 d-flex justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted mb-1 fw-bold">Tổng Danh Mục</p>
                        <div class="value h4 mb-2">{{ number_format($categoryCount ?? 0) }}</div>
                        <a href="{{ route('admin.categories.index') }}" class="link-action">
                            <i class="fas fa-arrow-right me-1"></i> Quản lý danh mục
                        </a>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card soft-card kpi">
                <div class="card-body p-3 d-flex justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted mb-1 fw-bold">Tổng Người Dùng</p>
                        <div class="value h4 mb-2">{{ number_format($userCount ?? 0) }}</div>
                        <a href="{{ route('admin.users.index') }}" class="link-action">
                            <i class="fas fa-arrow-right me-1"></i> Quản lý người dùng
                        </a>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card soft-card kpi">
                <div class="card-body p-3 d-flex justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted mb-1 fw-bold">Ngày hiện tại</p>
                        <div class="value h4 mb-2">{{ date('d/m/Y') }}</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tác vụ nhanh --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="card soft-card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color:var(--primary)">Tác vụ nhanh</h6>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card soft-card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-tags fa-2x mb-2 text-primary"></i>
                                    <h6 class="fw-bold">Quản lý Danh mục</h6>
                                    <p class="small text-muted">Thêm, sửa, xóa danh mục sản phẩm.</p>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-primary rounded-3">Quản lý</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card soft-card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-star fa-2x mb-2 text-warning"></i>
                                    <h6 class="fw-bold">Sản phẩm nổi bật</h6>
                                    <p class="small text-muted">Ghim sản phẩm hot trend.</p>
                                    <a href="{{ route('admin.products.index', ['highlight' => 1]) }}" class="btn btn-sm btn-outline-warning rounded-3">Quản lý</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card soft-card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-pie fa-2x mb-2 text-info"></i>
                                    <h6 class="fw-bold">Báo cáo</h6>
                                    <p class="small text-muted">Xem báo cáo tổng quan hệ thống.</p>
                                    <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-info rounded-3">Xem báo cáo</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card soft-card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-2x mb-2 text-primary"></i>
                                    <h6 class="fw-bold">Thống kê</h6>
                                    <p class="small text-muted">Xem biểu đồ, thống kê doanh thu.</p>
                                    <a href="{{ route('admin.reports.charts') }}" class="btn btn-sm btn-outline-primary rounded-3">Xem thống kê</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card soft-card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-receipt fa-2x mb-2 text-success"></i>
                                    <h6 class="fw-bold">Quản lý Đơn hàng</h6>
                                    <p class="small text-muted">Xem, duyệt, xử lý đơn hàng.</p>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-success rounded-3">Quản lý đơn hàng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="small text-muted mt-2">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection