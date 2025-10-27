@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Thông tin người nhận</h5>
            <p><strong>Họ tên:</strong> {{ $order->name }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Danh sách sản phẩm</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            @if(isset($item->product) && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="50" height="50" style="object-fit:cover;">
                            @else
                            <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Tổng đơn hàng:</th>
                        <th>{{ number_format($order->total_price, 0, ',', '.') }} đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- Tracking J&T Express --}}
    @if(isset($order->order_code) && $order->order_code)
    <div class="card mt-4">
        <div class="card-body">
            <h5>Thông tin tracking GHN</h5>
            <p><strong>Mã vận đơn:</strong> {{ $order->order_code }}</p>
            @if(isset($tracking))
            <p><strong>Trạng thái:</strong> {{ $tracking['status'] ?? '-' }}</p>
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
    <a href="{{ route('user.orders.index') }}" class="btn btn-secondary mt-3">Quay lại lịch sử đơn</a>
</div>
@endsection