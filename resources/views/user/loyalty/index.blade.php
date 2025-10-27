@extends('layouts.app')

@section('title', 'Tích Điểm & Voucher - Lady Bag')

@section('content')
<style>
    .loyalty-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .points-card {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(255, 154, 158, 0.3);
        transition: transform 0.3s ease;
    }

    .points-card:hover {
        transform: translateY(-5px);
    }

    .points-number {
        font-size: 3.5rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .redeem-card {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(168, 237, 234, 0.3);
        transition: transform 0.3s ease;
    }

    .redeem-card:hover {
        transform: translateY(-5px);
    }

    .voucher-card {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(255, 236, 210, 0.3);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }

    .voucher-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 236, 210, 0.4);
    }

    .voucher-code {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #d63384;
        border: 2px dashed #d63384;
    }

    .btn-redeem {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-redeem:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        color: white;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-valid {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #2d3748;
    }

    .status-expired {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #742a2a;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .info-icon {
        color: #667eea;
        margin-right: 0.5rem;
    }

    .exchange-rate {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1rem;
        margin: 1rem 0;
        border: 2px solid #667eea;
    }

    @media (max-width: 768px) {
        .loyalty-hero {
            padding: 2rem 0;
        }

        .points-number {
            font-size: 2.5rem;
        }

        .voucher-card {
            margin-bottom: 0.75rem;
        }
    }
</style>

<div class="loyalty-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 font-weight-bold mb-3">
                    <i class="fas fa-star text-warning mr-3"></i>
                    Chương Trình Tích Điểm
                </h1>
                <p class="lead mb-0">Mua sắm để nhận điểm thưởng và đổi voucher hấp dẫn!</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-gift fa-5x text-white opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Điểm hiện tại -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card points-card h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-coins fa-3x text-white mb-3"></i>
                    </div>
                    <h3 class="card-title text-white mb-3">Điểm Hiện Tại</h3>
                    <div class="points-number">{{ number_format($points) }}</div>
                    <p class="text-white-50 mb-0 mt-2">
                        <i class="fas fa-info-circle info-icon"></i>
                        Mỗi 100,000đ mua hàng = 100 điểm
                    </p>
                    <p class="text-white-50 small mt-1">
                        <i class="fas fa-check-circle info-icon"></i>
                        Điểm được cộng khi đơn hàng hoàn thành
                    </p>
                </div>
            </div>
        </div>

        <!-- Đổi điểm -->
        <div class="col-lg-6">
            <div class="card redeem-card h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-exchange-alt fa-3x text-primary mb-3"></i>
                        <h3 class="card-title">Đổi Điểm Thành Voucher</h3>
                    </div>

                    <div class="exchange-rate text-center">
                        <i class="fas fa-arrow-right fa-2x text-primary mb-2"></i>
                        <h5 class="mb-0">1000 điểm = Voucher 10,000đ</h5>
                    </div>

                    <form action="{{ route('user.loyalty.redeem') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="form-group">
                            <label for="points" class="font-weight-bold">
                                <i class="fas fa-calculator info-icon"></i>
                                Số điểm muốn đổi
                            </label>
                            <input type="number" id="points" name="points" min="1000" step="1000" required
                                class="form-control form-control-lg" placeholder="Ví dụ: 1000">
                            <small class="form-text text-muted">Tối thiểu 1000 điểm</small>
                        </div>
                        <button type="submit" class="btn btn-redeem btn-block btn-lg">
                            <i class="fas fa-gift mr-2"></i>
                            Đổi Điểm Ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Voucher của bạn -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-primary text-black">
            <h3 class="card-title mb-0">
                <i class="fas fa-ticket-alt mr-2"></i>
                Voucher Của Bạn
            </h3>
        </div>
        <div class="card-body">
            @if($coupons->count() > 0)
            <div class="row">
                @foreach($coupons as $coupon)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card voucher-card h-100">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <i class="fas fa-ticket-alt fa-2x text-primary mb-2"></i>
                                <div class="voucher-code">{{ $coupon->code }}</div>
                            </div>

                            <div class="text-center mb-3">
                                <h5 class="text-success font-weight-bold mb-1">
                                    {{ number_format($coupon->value) }}đ
                                </h5>
                                <small class="text-muted">Giá trị voucher</small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Hết hạn: {{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') : 'Không giới hạn' }}
                                </small>
                            </div>

                            <div class="text-center">
                                @if($coupon->isValid())
                                <span class="status-badge status-valid">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Có thể sử dụng
                                </span>
                                @else
                                <span class="status-badge status-expired">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Đã hết hạn
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Chưa có voucher nào</h4>
                <p class="text-muted">Mua hàng để tích điểm và đổi voucher hấp dẫn!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Mua sắm ngay
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection