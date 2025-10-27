@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h2>Thêm mã giảm giá</h2>
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Mã giảm giá</label>
            <input type="text" name="code" class="form-control" required maxlength="50" value="{{ old('code') }}">
        </div>
        <div class="form-group">
            <label>Loại</label>
            <select name="type" class="form-control">
                <option value="percent">Phần trăm (%)</option>
                <option value="fixed">Tiền mặt (VNĐ)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Giá trị</label>
            <input type="number" name="value" class="form-control" required min="1" value="{{ old('value') }}">
        </div>
        <div class="form-group">
            <label>Giới hạn lượt dùng</label>
            <input type="number" name="max_uses" class="form-control" min="1" value="{{ old('max_uses') }}">
            <small class="form-text text-muted">Để trống nếu không giới hạn</small>
        </div>
        <div class="form-group">
            <label>Ngày hết hạn</label>
            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
        </div>
        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection