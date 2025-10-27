@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Chi tiết sản phẩm</h1>
    <table class="table table-bordered w-50 mx-auto">
        <tr>
            <th style="width: 30%">Tên sản phẩm</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>Mô tả</th>
            <td>{{ $product->description }}</td>
        </tr>
        <tr>
            <th>Số lượng</th>
            <td>{{ $product->quantity }}</td>
        </tr>
        <tr>
            <th>Giá</th>
            <td>{{ number_format($product->price, 0, ',', ' ') }} VND</td>
        </tr>
        <tr>
            <th>Đặc điểm</th>
            <td>{{ $product->features }}</td>
        </tr>
        <tr>
            <th>Danh mục</th>
            <td>{{ $product->category->name ?? '' }}</td>
        </tr>
        <tr>
            <th>Hình ảnh</th>
            <td>
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="120" />
                @endif
            </td>
        </tr>
    </table>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection