@extends('layouts.admin')

@section('content')
<style>
    .order-detail-container {
        max-width: 800px;
        margin: 32px auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
        padding: 32px 28px 24px 28px;
        position: relative;
    }

    .order-detail-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .order-detail-header .icon-bag {
        font-size: 2.2rem;
        color: rgb(116, 101, 220);
    }

    .order-detail-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: rgb(116, 101, 220);
        letter-spacing: 1px;
    }

    .order-info-table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(116, 101, 220, 0.04);
    }

    .order-info-table th,
    .order-info-table td {
        padding: 10px 16px;
        border: 1px solid #e0e0e0;
        background: #f8f9fa;
        font-size: 1.05rem;
        text-align: left;
    }

    .order-info-table th {
        width: 180px;
        color: #6c757d;
        font-weight: 600;
        background: #f1f1f7;
    }

    .order-status-badge {
        border-radius: 8px;
        padding: 3px 12px;
        font-size: 0.98rem;
        font-weight: 600;
        display: inline-block;
    }

    .order-status-badge.completed {
        background: #d4edda;
        color: #155724;
    }

    .order-status-badge.packaged {
        background: #fff3cd;
        color: #856404;
    }

    .order-status-badge.shipping {
        background: #cce5ff;
        color: #004085;
    }

    .order-status-badge.cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-products-table {
        margin-top: 18px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(116, 101, 220, 0.07);
    }

    .order-products-table th {
        background: rgb(116, 101, 220);
        color: #fff;
        text-align: center;
        font-size: 1.05rem;
    }

    .order-products-table td {
        text-align: center;
        vertical-align: middle;
        background: #f8f9fa;
    }

    .order-products-table .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        background: #fff;
        box-shadow: 0 1px 4px rgba(116, 101, 220, 0.07);
    }

    .order-back-btn {
        margin-top: 24px;
        border-radius: 8px;
        background: rgb(116, 101, 220);
        color: #fff;
        font-weight: 600;
        padding: 8px 24px;
        font-size: 1.08rem;
        box-shadow: 0 2px 8px rgba(116, 101, 220, 0.10);
        transition: background 0.2s;
    }

    .order-back-btn:hover {
        background: #5f4bb6;
        color: #fff;
    }
</style>
<div class="order-detail-container">
    <div class="order-detail-header">
        <span class="icon-bag">👜</span>
        <span class="order-detail-title">Chi tiết đơn hàng #{{ $order->id }}</span>
    </div>
    <table class="order-info-table">
        <tr>
            <th>Khách hàng</th>
            <td>{{ $order->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td>{{ $order->address }}</td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>{{ $order->phone }}</td>
        </tr>
        <tr>
            <th>Tổng tiền</th>
            <td><span style="color:rgb(116,101,220);font-weight:700">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</span></td>
        </tr>


        <tr>
            <th>Giao hàng</th>
            <td>
                <span class="order-status-badge {{ $order->shipping_status }}">
                    @php
                    $shippingMap = [
                    'packaged' => 'Đã đóng gói',
                    'shipping' => 'Đang vận chuyển',
                    'completed' => 'Thành công',
                    'cancelled' => 'Hủy'
                    ];
                    @endphp
                    {{ $shippingMap[$order->shipping_status] ?? $order->shipping_status }}
                </span>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="mt-2 d-flex align-items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="shipping_status" value="{{ $order->shipping_status }}">
                    <input type="text" name="order_code" class="form-control form-control-sm" placeholder="Cập nhật mã vận đơn SPX" value="{{ $order->order_code }}" style="max-width:200px;display:inline-block;" required>
                    <button type="submit" class="btn btn-sm btn-primary">Cập nhật mã vận đơn</button>
                </form>
                {{-- Trạng thái tracking GHN nếu có --}}
                @if(isset($order->order_code) && $order->order_code)
                <div class="mt-2">
                    <strong>Thông tin tracking GHN:</strong><br>
                    <span>Mã vận đơn: <b>{{ $order->order_code }}</b></span><br>
                    @if(isset($tracking) && !empty($tracking))
                    <span>Trạng thái: <b>{{ $tracking['status'] ?? '-' }}</b></span><br>
                    <span>Người nhận: {{ $tracking['to_name'] ?? '-' }} - {{ $tracking['to_phone'] ?? '-' }}</span><br>
                    <span>Địa chỉ nhận: {{ $tracking['to_address'] ?? '-' }}</span><br>
                    <span>Ngày tạo đơn: {{ $tracking['created_date'] ?? '-' }}</span><br>
                    <span>Ngày cập nhật: {{ $tracking['updated_date'] ?? '-' }}</span><br>
                    <span>Ghi chú: {{ $tracking['note'] ?? '-' }}</span><br>
                    <span>Lịch sử trạng thái:</span>
                    @if(isset($tracking['log']) && is_array($tracking['log']) && count($tracking['log']))
                    <ul style="padding-left:18px;">
                        @foreach($tracking['log'] as $item)
                        <li>
                            {{ $item['updated_date'] ?? '' }} -
                            {{ $item['status'] ?? '' }}
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <span class="text-muted">Không có lịch sử vận chuyển.</span>
                    @endif
                    @else
                    <span class="text-muted">Chưa có thông tin vận chuyển GHN.</span>
                    @endif
                </div>
                @endif
            </td>
        </tr>
    </table>
    <h5 class="mt-4 mb-2" style="color:rgb(116,101,220);font-weight:600;">🛍️ Sản phẩm trong đơn hàng</h5>
    <div class="order-products-table">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        @if(isset($item->product->image))
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-img">
                        @else
                        <img src="https://via.placeholder.com/60x60?text=No+Image" alt="No image" class="product-img">
                        @endif
                    </td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="color:rgb(116,101,220);font-weight:600">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="order-back-btn d-inline-block"><span style="font-size:1.1em;">←</span> Quay lại danh sách</a>
</div>
@endsection