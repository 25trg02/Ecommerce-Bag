@extends('layouts.admin')
@section('title', 'Quản lý mã giảm giá')
@section('content')
<style>
    /* Đơn giản & tương phản cao */
    .coupon-admin {
        color: #212529;
    }

    .coupon-admin .page-title {
        font-weight: 600;
        margin-bottom: .75rem;
    }

    .coupon-admin .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #212529;
    }

    .coupon-admin .table tbody td {
        vertical-align: middle;
    }

    .coupon-admin .badge-soft {
        display: inline-block;
        padding: .25rem .5rem;
        border-radius: .375rem;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        color: #212529;
        font-weight: 600;
    }

    .coupon-admin .status {
        font-weight: 600;
    }

    .status-expired {
        color: #c92a2a;
    }

    .status-full {
        color: #ad6800;
    }

    .status-unused {
        color: #495057;
    }

    .status-active {
        color: #2b8a3e;
    }
</style>

<div class="container mt-4 coupon-admin">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <h2 class="page-title mb-2">Quản lý mã giảm giá</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mb-2">+ Thêm mã giảm giá</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm mb-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Đã dùng</th>
                    <th>Giới hạn</th>
                    <th>Hết hạn</th>
                    <th>Trạng thái</th>
                    <th style="width:112px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                @php
                $now = now();
                $expiresAt = $coupon->expires_at ? \Illuminate\Support\Carbon::parse($coupon->expires_at) : null;
                $isExpired = $expiresAt && $now->gt($expiresAt);
                $isFull = $coupon->max_uses !== null && $coupon->used >= $coupon->max_uses;
                $statusText = $isExpired ? 'Hết hạn' : ($isFull ? 'Hết lượt' : ($coupon->used == 0 ? 'Chưa dùng' : 'Còn lượt'));
                $statusClass = $isExpired ? 'status-expired' : ($isFull ? 'status-full' : ($coupon->used == 0 ? 'status-unused' : 'status-active'));
                @endphp
                <tr>
                    <td>{{ $coupon->id }}</td>
                    <td><span class="badge-soft">{{ $coupon->code }}</span></td>
                    <td>{{ $coupon->type === 'percent' ? 'Phần trăm' : 'Tiền mặt' }}</td>
                    <td>
                        <span class="badge-soft">
                            {{ $coupon->type === 'percent' ? ($coupon->value.'%') : (number_format($coupon->value).' VNĐ') }}
                        </span>
                    </td>
                    <td>{{ $coupon->used }}</td>
                    <td>{{ $coupon->max_uses ?? 'Không giới hạn' }}</td>
                    <td>
                        @if($expiresAt)
                        {{ $expiresAt->format('d/m/Y H:i') }}
                        @else
                        Không
                        @endif
                    </td>
                    <td class="status {{ $statusClass }}">{{ $statusText }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-secondary btn-sm">Sửa</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xóa mã này?')" class="ml-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Chưa có mã giảm giá nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-end">
        {{ $coupons->links() }}
    </div>
    @endif
</div>
@endsection