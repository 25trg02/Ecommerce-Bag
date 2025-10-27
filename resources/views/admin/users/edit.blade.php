{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh sửa người dùng</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div class="form-group mb-3">
            <label for="name">Tên</label>
            <input type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                class="form-control"
                required>
        </div>

        {{-- Email --}}
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                class="form-control"
                required>
        </div>

        {{-- Vai trò --}}
        <div class="form-group mb-4">
            <label for="role">Vai trò</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" @selected($user->role === 'user')>Người dùng</option>
                <option value="admin" @selected($user->role === 'admin')>Quản trị</option>
            </select>
        </div>

        {{-- Nút submit --}}
        <button type="submit" class="btn btn-primary">
            💾 Cập nhật
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            ⬅ Quay lại
        </a>
    </form>
</div>
@endsection