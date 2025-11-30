@extends('layouts.app')

@section('title', 'Manajemen Users')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Manajemen Users</h1>
        <p class="text-muted mb-0">Admin dapat membuat, mengubah dan menonaktifkan akun.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-3 mt-md-0">
        <i class="fas fa-user-plus me-2"></i>Buat User Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-secondary text-uppercase">{{ $user->role }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
