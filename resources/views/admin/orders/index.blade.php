{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')


@section('content')
<style>
    .order-table {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(116, 101, 220, 0.07);
        overflow: hidden;
    }

    .order-table th {
        background: rgb(116, 101, 220);
        color: #fff;
        vertical-align: middle;
        text-align: center;
        font-size: 1.05rem;
    }

    .order-table td {
        vertical-align: middle;
        text-align: center;
        background: #f8f9fa;
    }

    .order-table tr:hover td {
        background: #ecebfa;
    }

    .order-status {
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 8px;
        display: inline-block;
        font-size: 0.97rem;
    }

    .order-status.cod_ordered {
        background: #fff3cd;
        color: #000000ff;
    }

    .order-status.paid_momo {
        background: #d4edda;
        color: #155724;
    }

    .order-status.completed {
        background: #d4edda;
        color: #155724;
    }

    .order-status.packaged {
        background: #e0e7ff;
        color: rgb(116, 101, 220);
    }

    .order-status.shipping {
        background: #cce5ff;
        color: #004085;
    }

    .order-status.cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-action-btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 6px 16px;
        font-size: 1rem;
        background: rgb(116, 101, 220);
        color: #fff;
        border: none;
        box-shadow: 0 1px 4px rgba(116, 101, 220, 0.08);
        transition: background 0.2s;
    }

    .order-action-btn:hover {
        background: #5f4bb6;
        color: #fff;
    }

    .badge.bg-dark {
        background: rgb(116, 101, 220) !important;
        color: #fff !important;
        font-size: 1em;
        border-radius: 6px;
        padding: 6px 14px;
    }

    .shipping-status-badge {
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.97rem;
        font-weight: 600;
        display: inline-block;
    }

    .shipping-status-badge.packaged {
        background: #e0e7ff;
        color: rgb(116, 101, 220);
    }

    .shipping-status-badge.shipping {
        background: #cce5ff;
        color: #004085;
    }

    .shipping-status-badge.completed {
        background: #d4edda;
        color: #155724;
    }

    .shipping-status-badge.cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .custom-select-arrow {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .custom-select-arrow select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 28px !important;
        background: transparent;
    }

    .custom-select-arrow:after {
        content: '\25BC';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #888;
        font-size: 0.9em;
    }
</style>
<div class="container py-4">
    <h2 class="mb-4 text-center font-weight-bold">📦 Quản lý Đơn hàng</h2>

    {{-- Thông báo thành công --}}
    @if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @endif
    <div class="mb-4" style="max-width:400px;margin:0 auto;">

        @if(session('tracking_error'))
        <div class="alert alert-danger mt-2">{{ session('tracking_error') }}</div>
        @endif
        @if(session('tracking_result'))
        <div class="alert alert-info mt-2">
            <b>Kết quả Tracking:</b><br>
            {!! session('tracking_result') !!}
        </div>
        @endif
    </div>

    @if($orders->count() > 0)
    <div class="table-responsive order-table mb-4">
        <table class="table table-bordered table-hover mb-0" style="border-radius:12px;overflow:hidden;">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <!-- <th>SĐT</th> -->
                    <!-- <th>Địa chỉ</th> -->
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Giao hàng</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-dark">{{ $order->id }}</span></td>
                    <td>{{ $order->name }}</td>
                    <!-- <td>{{ $order->phone }}</td> -->
                    <!-- <td class="text-left">{{ $order->address }}</td> -->
                    <td class="text-right text-danger font-weight-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                    <td>
                        <span class="order-status {{ $order->status }}">
                            {{ $order->status == 'cod_ordered' ? 'Thanh toán (COD)' : ($order->status == 'paid_momo' ? 'Đã thanh toán (Momo)' : $order->status) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="margin:0;display:flex;justify-content:center;">
                            @csrf
                            @method('PATCH')
                            <span class="custom-select-arrow" style="min-width:120px;">
                                <select name="shipping_status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    @foreach([
                                    'processing' => 'Chờ xử lý',
                                    'packaged' => 'Đã đóng gói',
                                    'shipping' => 'Đang vận chuyển',
                                    'completed' => 'Thành công',
                                    'cancelled' => 'Hủy'

                                    ] as $key => $label)
                                    <option value="{{ $key }}" {{ $order->shipping_status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </span>
                        </form>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="order-action-btn">
                            🔍 Chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Input mã vận đơn SPX khi chọn Đã đóng gói --}}
    @if(request('shipping_status', $order->shipping_status) == 'packaged')

    @endif
    @else
    <p class="text-center text-muted">Không có đơn hàng nào.</p>
    @endif
</div>
@endsection