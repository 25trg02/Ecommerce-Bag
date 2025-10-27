@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Xác thực địa chỉ email') }}</div>

                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif

                    <p>
                        {{ __('Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh email của bạn bằng cách nhấp vào liên kết chúng tôi vừa gửi cho bạn qua email.') }}
                    </p>
                    <p>
                        {{ __('Nếu bạn không nhận được email, chúng tôi sẽ gửi cho bạn một email khác.') }}
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                        @csrf
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Gửi lại email xác thực') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection