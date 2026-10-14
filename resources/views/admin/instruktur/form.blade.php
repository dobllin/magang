@extends('admin.layouts.app')

@section('title', $instruktur ? 'Edit Instruktur' : 'Tambah Instruktur')

@section('content')
<div class="card shadow-sm border-0 p-4" style="max-width:700px;">
    @if ($users->isEmpty())
        <div class="alert alert-warning mb-0">
            Belum ada user ber-role <strong>instruktur</strong> yang belum punya profil.
            Buat dulu usernya di <a href="{{ route('admin.users.create') }}">Kelola User</a>, pilih role Instruktur,
            lalu balik lagi ke sini.
        </div>
    @else
        <form method="POST" action="{{ $instruktur ? route('admin.instruktur.update', $instruktur->id) : route('admin.instruktur.store') }}">
            @csrf
            @if($instruktur) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">User</label>
                <select name="id_user" class="form-select" required>
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ (old('id_user', $instruktur->id_user ?? '') == $u->id) ? 'selected' : '' }}>
                            {{ $u->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Cuma user ber-role instruktur yang belum punya profil yang muncul di sini.</div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $instruktur->jabatan ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi', $instruktur->spesialisasi ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pengalaman</label>
                    <input type="text" name="pengalaman" class="form-control" placeholder="contoh: 5 tahun" value="{{ old('pengalaman', $instruktur->pengalaman ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sertifikasi <span class="text-muted">(opsional)</span></label>
                    <input type="text" name="sertifikasi" class="form-control" value="{{ old('sertifikasi', $instruktur->sertifikasi ?? '') }}">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-nav-accent">{{ $instruktur ? 'Simpan Perubahan' : 'Tambah Instruktur' }}</button>
                <a href="{{ route('admin.instruktur.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    @endif
</div>
@endsection
