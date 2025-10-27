<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thanh toán đơn hàng</h2>

    <form id="checkoutForm" method="POST" action="{{ route('user.payment.process') }}">
        @csrf

        <!-- Thông tin khách hàng -->
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

        <!-- Giỏ hàng -->
        <div class="mt-4">
            <h5>🛒 Giỏ hàng</h5>
            <ul class="list-group">
                @php $total = 0; @endphp
                @foreach($cart as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        {{ $item['name'] }} (x{{ $item['quantity'] }})
                    </div>
                    <div>
                        {{ number_format($item['price'] * $item['quantity']) }} VNĐ
                    </div>
                    @php $total += $item['price'] * $item['quantity']; @endphp
                </li>
                @endforeach

                <li class="list-group-item text-end">
                    <strong>Tổng cộng: {{ number_format($total) }} VNĐ</strong>
                </li>
            </ul>

            <input type="hidden" name="total_price" value="{{ $total }}">
        </div>

        <!-- Nút thanh toán -->
        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-success" name="payment_method" value="momo">
                📱 Thanh toán MoMo (Mặc định)
            </button>

            <button type="submit" class="btn btn-secondary" name="payment_method" value="cod">
                🚚 Thanh toán COD
            </button>
        </div>
    </form>
</div>
@endsection