<!-- resources/views/cart/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>🛒 Giỏ hàng của bạn</h2>

    {{-- Thông báo thành công / lỗi --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $id => $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ number_format($item['price']) }} VNĐ</td>
                <td>
                    {{-- Form cập nhật số lượng --}}
                    <form action="{{ route('user.cart.update', $id) }}" method="POST" class="form-inline">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                            min="1" class="form-control form-control-sm w-50 d-inline">
                        <button type="submit" class="btn btn-sm btn-primary ml-1">Cập nhật</button>
                    </form>
                </td>
                <td>{{ number_format($item['price'] * $item['quantity']) }} VNĐ</td>
                <td>
                    {{-- Xóa sản phẩm --}}
                    <form action="{{ route('user.cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tổng cộng --}}

    {{-- Mã giảm giá --}}
    <div class="row justify-content-end mb-2">
        <div class="col-md-6">
            @if($coupon)
            <div class="alert alert-success p-2 mb-2">
                <strong>Đã áp dụng mã:</strong> <span class="badge badge-success">{{ $coupon['code'] }}</span>
                <form action="{{ route('cart.removeCoupon') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-link btn-sm text-danger p-0 ml-2">Hủy</button>
                </form>
            </div>
            <div class="text-right mb-2">
                <span>Giảm giá: <strong class="text-success">-{{ number_format($discount) }} VNĐ</strong></span>
            </div>
            <h5 class="text-right">
                Tổng cộng: <strong>{{ number_format($total_after_discount) }} VNĐ</strong>
            </h5>
            @else
            <form action="{{ route('cart.applyCoupon') }}" method="POST" class="form-inline justify-content-end mb-2">
                @csrf
                <input type="text" name="coupon" class="form-control mr-2" placeholder="Nhập mã giảm giá..." required style="max-width: 180px;">
                <button class="btn btn-info">Áp dụng</button>
            </form>
            <h5 class="text-right">
                Tổng cộng: <strong>{{ number_format($total) }} VNĐ</strong>
            </h5>
            @endif
        </div>
    </div>

    {{-- Nút xóa toàn bộ --}}
    <form action="{{ route('user.cart.clear') }}" method="POST" class="text-right mt-3">
        @csrf
        <button class="btn btn-warning"
            onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
            🗑️ Xóa toàn bộ giỏ hàng
        </button>
    </form>

    {{-- Nút thanh toán --}}
    <div class="text-end mt-2">
        <a href="{{ route('user.payment.index') }}" class="btn btn-success d-block text-right mt-2">
            💳 Thanh toán
        </a>
    </div>
    @else
    <p class="text-muted">Không có sản phẩm nào trong giỏ hàng.</p>
    @endif
</div>
@endsection