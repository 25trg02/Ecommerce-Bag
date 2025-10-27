{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách người dùng</h2>

    {{-- Hiển thị thông báo thành công --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- Nút thêm người dùng --}}
    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">
        + Thêm người dùng
    </a>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">
                        Xem
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                        Sửa
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">
                    Không có người dùng nào.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection