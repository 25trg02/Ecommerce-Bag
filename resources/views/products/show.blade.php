<!-- resources/views/products/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4 shadow">
            <div class="row no-gutters">
                <div class="col-md-5 d-flex align-items-center justify-content-center bg-light">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded p-3" style="max-height:300px;">
                    @else
                    <img src="https://via.placeholder.com/300x300?text=No+Image" alt="No Image" class="img-fluid rounded p-3" style="max-height:300px;">
                    @endif
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <h2 class="card-title mb-3">{{ $product->name }}</h2>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>Số lượng:</strong> {{ $product->quantity }}</p>
                        <p class="card-text text-danger"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
                        <p class="card-text"><strong>Danh mục:</strong> {{ $product->category->name }}</p>

                        @auth
                        <!-- Form để thêm sản phẩm vào giỏ hàng với số lượng -->
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="d-flex align-items-center">
                                <label for="quantity" class="me-2">Số lượng:</label>
                                <div class="input-group" style="width: 120px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(-1)">-</button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}" class="form-control text-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeQuantity(1)">+</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 w-100">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </form>

                        <!-- Nút thêm vào wishlist -->
                        <form id="wishlist-form" action="{{ route('wishlist.store') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" id="wishlist-btn" class="btn btn-outline-danger w-100">
                                <i class="fas fa-heart"></i> <span id="wishlist-btn-text">Thêm vào wishlist</span>
                            </button>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const wishlistForm = document.getElementById('wishlist-form');
                                const wishlistBtn = document.getElementById('wishlist-btn');
                                const wishlistBtnText = document.getElementById('wishlist-btn-text');
                                if (wishlistForm) {
                                    wishlistForm.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        wishlistBtn.disabled = true;
                                        wishlistBtnText.textContent = 'Đang thêm...';
                                        fetch(wishlistForm.action, {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': wishlistForm.querySelector('input[name="_token"]').value,
                                                    'Accept': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                },
                                                body: new FormData(wishlistForm)
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    wishlistBtn.classList.remove('btn-outline-danger');
                                                    wishlistBtn.classList.add('btn-success');
                                                    wishlistBtnText.textContent = 'Đã thêm vào wishlist';
                                                } else {
                                                    wishlistBtn.disabled = false;
                                                    wishlistBtnText.textContent = data.message || 'Thêm vào wishlist';
                                                    alert(data.message || 'Có lỗi xảy ra!');
                                                }
                                            })
                                            .catch(() => {
                                                wishlistBtn.disabled = false;
                                                wishlistBtnText.textContent = 'Thêm vào wishlist';
                                                alert('Có lỗi xảy ra!');
                                            });
                                    });
                                }
                            });
                        </script>
                        @else
                        <p class="mt-3">
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a>
                            để thêm sản phẩm vào giỏ hàng.
                        </p>
                        @endauth

                        <a href="{{ route('welcome') }}" class="btn btn-secondary mt-4">
                            Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection