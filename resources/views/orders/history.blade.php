@extends('layouts.app')

@section('content')
@if(isset($tracking))
<div class="alert alert-info">
    <strong>Demo trạng thái GHN:</strong><br>
    Mã vận đơn: <b>{{ $order_code }}</b><br>
    Trạng thái: <b>{{ $tracking['data']['status'] ?? 'Không lấy được trạng thái' }}</b><br>
    @if(isset($tracking['data']['status_name']))
    Mô tả: {{ $tracking['data']['status_name'] }}
    @endif
</div>
@endif
@if(isset($orders))
<div class="container mt-4">
    <h2 class="mb-4">Lịch sử đơn hàng của bạn</h2>
    @forelse($orders as $order)
    <div class="card mb-3 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Mã đơn hàng #{{ $order->id }}</strong> <br>
                <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <div class="text-end">
                <span class="badge
                    @if ($order->shipping_status == 'packaged') bg-warning text-dark
                    @elseif ($order->shipping_status == 'shipping') bg-primary
                    @elseif ($order->shipping_status == 'completed') bg-success
                    @elseif ($order->shipping_status == 'cancelled') bg-danger
                    @else bg-dark text-warning
                    @endif">
                    @switch($order->shipping_status)

                    @case('processing') Chờ xử lý @break
                    @case('packaged') Đã đóng gói @break
                    @case('shipping') Đang giao @break
                    @case('completed') Đã giao @break
                    @case('cancelled') Đã hủy @break
                    @default Chờ xử lý @break
                    @endswitch
                </span><br>

                {{-- Chỉ hiện khi đơn có trạng thái "thanh toán MoMo không thành công" --}}
                @if($order->status === 'Thanh toán MoMo không thành công')
                <a href="{{ route('orders.momo.pay', $order) }}" class="btn btn-sm btn-success mt-2">
                    Thanh toán Momo không thành công. Thanh toán lại
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $order->name }}</p>
            <p><strong>SĐT:</strong> {{ $order->phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} đ</p>
            <p><strong>Phương thức thanh toán:</strong>
                @if($order->payment_method === 'online')
                <span class="badge bg-info text-dark">MoMo</span>
                @else
                <span class="badge bg-warning text-dark border border-1 border-warning">COD</span>
                @endif
            </p>


            {{-- Trạng thái GHN nếu có --}}
            @if(isset($order->order_code) && $order->order_code)
            <div class="mt-2">
                <strong>🚚 Trạng thái giao hàng GHN:</strong>
                <span> <b>{{ $order->tracking['status'] ?? '-' }}</b></span>
            </div>
            @endif

            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                Xem chi tiết
            </a>
        </div>
        <div class="collapse" id="orderDetail{{ $order->id }}">
            <div class="card-body border-top bg-light">
                <h6>Danh sách sản phẩm:</h6>
                <table class="table table-sm table-bordered mt-2">
                    <thead class="table-light">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
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
                            <td>{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        Bạn chưa có đơn hàng nào.
    </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif
@endsection