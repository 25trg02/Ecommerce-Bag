@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách sản phẩm yêu thích</h2>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($wishlists->isEmpty())
    <p>Bạn chưa có sản phẩm nào trong wishlist.</p>
    @else
    <div class="row">
        @foreach($wishlists as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <!-- <p class="card-text">{{ $product->description }}</p> -->
                    <p class="card-text"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p>
                    <form action="{{ route('wishlist.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa khỏi wishlist</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection