<!-- Trong login.blade.php -->
@extends('layouts.app')

@section('content')
<h2 style="text-align:center; margin: 0 0 20px; color: #111827; font-weight:700;">Đăng nhập</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form action="{{ route('login') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>
@endsection