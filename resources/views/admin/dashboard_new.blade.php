@extends('layouts.admin')

@section('title', 'Dashboard Quản Lý Túi Xách Nữ')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-3">Xin chào, {{ Auth::user()->name }}!</h4>
                            <p>Chào mừng bạn đến với Hệ thống Quản lý Túi Xách Nữ. Hãy chọn một mục từ menu bên trái để bắt đầu quản lý.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Tổng Sản Phẩm</p>
                                <h5 class="font-weight-bolder">
                                    {{ $productCount ?? 0 }}
                                </h5>
                                <p class="mb-0">
                                    <a href="{{ route('admin.products.index') }}" class="text-primary text-sm font-weight-bolder">Quản lý sản phẩm</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="fas fa-shopping-bag text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Tổng Danh Mục</p>
                                <h5 class="font-weight-bolder">
                                    {{ $categoryCount ?? 0 }}
                                </h5>
                                <p class="mb-0">
                                    <a href="{{ route('admin.categories.index') }}" class="text-success text-sm font-weight-bolder">Quản lý danh mục</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="fas fa-list text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Tổng Người Dùng</p>
                                <h5 class="font-weight-bolder">
                                    {{ $userCount ?? 0 }}
                                </h5>
                                <p class="mb-0">
                                    <a href="#" class="text-info text-sm font-weight-bolder">Quản lý người dùng</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Hướng dẫn sử dụng</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-shopping-bag text-primary me-2"></i> Quản lý sản phẩm</h5>
                                    <p class="card-text">Thêm, sửa, xóa và quản lý tất cả sản phẩm túi xách nữ trong hệ thống.</p>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">Đi đến</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-list text-success me-2"></i> Quản lý danh mục</h5>
                                    <p class="card-text">Tổ chức và phân loại sản phẩm bằng cách thêm, sửa, xóa các danh mục.</p>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-success">Đi đến</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-users text-info me-2"></i> Quản lý người dùng</h5>
                                    <p class="card-text">Xem và quản lý thông tin tài khoản người dùng trong hệ thống.</p>
                                    <a href="#" class="btn btn-sm btn-info">Đi đến</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection