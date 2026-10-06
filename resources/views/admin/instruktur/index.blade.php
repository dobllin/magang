{{-- Simpan file ini di: resources/views/admin/instruktur/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Instruktur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Instruktur ({{ $instruktur->count() }})</h5>
    <a href="{{ route('admin.instruktur.create') }}" class="btn btn-nav-accent">+ Tambah Instruktur</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jabatan</th>
                    <th>Spesialisasi</th>
                    <th>Pengalaman</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($instruktur as $ins)
                    <tr>
                        <td class="fw-semibold">{{ $ins->nama_lengkap }}</td>
                        <td>{{ $ins->email }}</td>
                        <td>{{ $ins->jabatan }}</td>
                        <td>{{ $ins->spesialisasi }}</td>
                        <td>{{ $ins->pengalaman }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.instruktur.edit', $ins->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.instruktur.destroy', $ins->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus instruktur ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data instruktur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection