<!-- resources/views/orders/show.blade.php -->
@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🧾 Chi tiết đơn hàng #{{ $order->id }}</h2>

    <!-- Thông tin người nhận -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Thông tin người nhận</h5>
            <p><strong>👤 Họ tên:</strong> {{ $order->name }}</p>
            <p><strong>📍 Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>📞 Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>📅 Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card">
        <div class="card-body">
            <h5>Danh sách sản phẩm</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Tổng đơn hàng:</th>
                        <th>{{ number_format($order->total_price, 0, ',', '.') }} đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Trạng thái giao hàng GHN nếu có -->
    @if(isset($order->order_code) && $order->order_code)
    <div class="card mt-4">
        <div class="card-body">
            <h5>🚚 Trạng thái giao hàng GHN</h5>
            <p><strong>Mã vận đơn:</strong> <b>{{ $order->order_code }}</b></p>
            @if(isset($tracking))
            <p><strong>Trạng thái:</strong> <b>{{ $tracking['status'] ?? '-' }}</b></p>
            <p><strong>Người nhận:</strong> {{ $tracking['to_name'] ?? '-' }} - {{ $tracking['to_phone'] ?? '-' }}</p>
            <p><strong>Địa chỉ nhận:</strong> {{ $tracking['to_address'] ?? '-' }}</p>
            <p><strong>Ngày tạo đơn:</strong> {{ $tracking['created_date'] ?? '-' }}</p>
            <p><strong>Ngày cập nhật:</strong> {{ $tracking['updated_date'] ?? '-' }}</p>
            <p><strong>Ghi chú:</strong> {{ $tracking['note'] ?? '-' }}</p>
            <p><strong>Lịch sử trạng thái:</strong></p>
            @if(isset($tracking['log']) && is_array($tracking['log']))
            <ul>
                @foreach($tracking['log'] as $item)
                <li>{{ $item['updated_date'] ?? '' }} - {{ $item['status'] ?? '' }}</li>
                @endforeach
            </ul>
            @else
            <span class="text-muted">Không có lịch sử vận chuyển.</span>
            @endif
            @else
            <span class="text-muted">Chưa có thông tin vận chuyển GHN.</span>
            @endif
        </div>
    </div>
    @endif

    <!-- Nút quay lại -->
    <a href="{{ route('user.orders.index') }}" class="btn btn-secondary mt-3">
        ← Quay lại lịch sử đơn hàng
    </a>
</div>
@endsection