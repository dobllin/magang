{{-- Simpan file ini di: resources/views/admin/users/form.blade.php --}}
@extends('admin.layouts.app')

@section('title', $user ? 'Edit User' : 'Tambah User')

@section('content')
<div class="card shadow-sm border-0 p-4" style="max-width:600px;">
    <form method="POST" action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
        @csrf
        @if($user) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                @foreach (['peserta', 'instruktur', 'admin'] as $r)
                    <option value="{{ $r }}" {{ (old('role', $user->role ?? '') == $r) ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Password {{ $user ? '(kosongkan kalau tidak diganti)' : '' }}</label>
            <input type="password" name="password" class="form-control" {{ $user ? '' : 'required' }}>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-nav-accent">{{ $user ? 'Simpan Perubahan' : 'Tambah User' }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection