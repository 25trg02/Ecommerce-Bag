@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thanh toán đơn hàng</h2>
    <form id="checkoutForm" method="POST" action="{{ route('user.payment.process') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="name">Họ và tên</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="phone">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="col-md-12 mt-3">
                <label for="address">Địa chỉ giao hàng</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>
        </div>

        <div class="mt-4">
            <h5>Giỏ hàng</h5>
            <ul class="list-group">
                @foreach($cart as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <div>{{ $item['name'] }} (x{{ $item['quantity'] }})</div>
                    <div>{{ number_format($item['price'] * $item['quantity']) }} VNĐ</div>
                </li>
                @endforeach
                @if(isset($coupon) && $coupon)
                <li class="list-group-item d-flex justify-content-between">
                    <div>Mã giảm giá: <span class="badge bg-success">{{ $coupon['code'] }}</span></div>
                    <div>-{{ number_format($discount) }} VNĐ</div>
                </li>
                @endif
                <li class="list-group-item text-end">
                    <strong>Tổng cộng: {{ number_format($total_after_discount ?? $total) }} VNĐ</strong>
                </li>
            </ul>
            <input type="hidden" name="total_price" value="{{ $total_after_discount ?? $total }}">
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-success" name="payment_method" value="momo">
                Thanh toán MoMo
            </button>
            <button type="submit" class="btn btn-secondary" name="payment_method" value="cod">
                Thanh toán COD
            </button>
        </div>
    </form>
</div>
@endsection