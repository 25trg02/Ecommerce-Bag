@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Thêm sản phẩm mới</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-bold">Danh mục</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label fw-bold">Số lượng</label>
                                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-bold">Giá</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="features" class="form-label fw-bold">Đặc điểm</label>
                            <input type="text" name="features" class="form-control" value="{{ old('features') }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-4">Lưu</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection