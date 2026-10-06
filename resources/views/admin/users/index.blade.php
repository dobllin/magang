{{-- Simpan file ini di: resources/views/admin/users/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Users ({{ $users->count() }})</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-nav-accent">+ Tambah User</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Role</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td class="fw-semibold">{{ $u->nama_lengkap }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->no_hp }}</td>
                        <td>
                            <span class="badge bg-{{ $u->role == 'admin' ? 'danger' : ($u->role == 'instruktur' ? 'success' : 'primary') }}">{{ $u->role }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection