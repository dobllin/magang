{{-- Simpan file ini di: resources/views/admin/materi/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Materi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Materi ({{ $materi->count() }})</h5>
    <a href="{{ route('admin.materi.create') }}" class="btn btn-nav-accent">+ Tambah Materi</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Durasi</th>
                    <th>LP</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materi as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->judul }}</td>
                        <td><span class="badge" style="background:#eef1ff; color:#2f5bff;">{{ $item->kategori }}</span></td>
                        <td>{{ $item->duration_minutes }} menit</td>
                        <td>{{ $item->learning_points }} LP</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'published' ? 'success' : 'secondary' }}">{{ $item->status }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.materi.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.materi.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data materi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection