@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<h3>Edit User</h3>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password (kosongkan untuk tidak mengganti)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="student" @if($user->role === 'student') selected @endif>Student</option>
                    <option value="teacher" @if($user->role === 'teacher') selected @endif>Teacher</option>
                    <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
                </select>
            </div>

            <div>
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
 </div>
@endsection
