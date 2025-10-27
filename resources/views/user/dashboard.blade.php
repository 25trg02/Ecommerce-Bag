@extends('layouts.user')

@section('content')
<h1>Chào mừng {{ Auth::user()->name }} đến với trang cá nhân!</h1>
<p>Đây là giao diện dành cho người dùng đã đăng nhập.</p>

<div class="mt-6">
    <a href="{{ route('user.loyalty.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Xem Điểm & Voucher
    </a>
</div>
@endsection