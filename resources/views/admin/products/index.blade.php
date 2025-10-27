@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1>Danh sách sản phẩm</h1>
    <style>
        .table-responsive {
            overflow-x: visible;
        }

        .table td {
            word-wrap: break-word;
            max-width: 300px;
            vertical-align: middle;
        }

        .description-cell {
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 100px;
        }

        .features-cell {
            white-space: normal;
        }
    </style>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 15%">Tên</th>
                    <th style="width: 20%">Mô tả</th>
                    <th style="width: 8%">Số lượng</th>
                    <th style="width: 15%">Giá</th>
                    <th style="width: 15%">Đặc điểm</th>
                    <th style="width: 8%">Hình ảnh</th>
                    <th style="width: 8%">Danh mục</th>
                    <th style="width: 13%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="description-cell">{{ $product->description }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ number_format($product->price, 0, ',', ' ') }} VND</td>
                    <td class="features-cell">{{ $product->features }}</td>
                    <td>
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="80" />
                        @endif
                    </td>
                    <td>{{ $product->category->name ?? '' }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info btn-sm mb-1">Xem</a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm mb-1">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection