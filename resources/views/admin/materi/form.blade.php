{{-- Simpan file ini di: resources/views/admin/materi/form.blade.php --}}
@extends('admin.layouts.app')

@section('title', $materi ? 'Edit Materi' : 'Tambah Materi')

@section('content')
<div class="card shadow-sm border-0 p-4">
    <form method="POST" action="{{ $materi ? route('admin.materi.update', $materi->id) : route('admin.materi.store') }}">
        @csrf
        @if($materi) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Judul Materi</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $materi->judul ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Instruktur</label>
                <select name="id_instruktur" class="form-select" required>
                    <option value="">-- Pilih Instruktur --</option>
                    @foreach ($instrukturs as $ins)
                        <option value="{{ $ins->id }}" {{ (old('id_instruktur', $materi->id_instruktur ?? '') == $ins->id) ? 'selected' : '' }}>
                            {{ $ins->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $materi->kategori ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Subkategori (opsional)</label>
                <input type="text" name="subkategori" class="form-control" value="{{ old('subkategori', $materi->subkategori ?? '') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $materi->deskripsi ?? '') }}</textarea>
            </div>

            <div class="col-md-3">
                <label class="form-label">Jenis Materi</label>
                <select name="jenis_materi" class="form-select" required>
                    @foreach (['artikel', 'video', 'pdf', 'quiz'] as $jenis)
                        <option value="{{ $jenis }}" {{ (old('jenis_materi', $materi->jenis_materi ?? '') == $jenis) ? 'selected' : '' }}>{{ ucfirst($jenis) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Durasi (menit)</label>
                <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $materi->duration_minutes ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Learning Points</label>
                <input type="number" name="learning_points" class="form-control" value="{{ old('learning_points', $materi->learning_points ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Total Lessons</label>
                <input type="number" name="total_lessons" class="form-control" value="{{ old('total_lessons', $materi->total_lessons ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Bahasa</label>
                <input type="text" name="bahasa" class="form-control" value="{{ old('bahasa', $materi->bahasa ?? 'Indonesia') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    @foreach (['published', 'draft'] as $st)
                        <option value="{{ $st }}" {{ (old('status', $materi->status ?? '') == $st) ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-nav-accent">{{ $materi ? 'Simpan Perubahan' : 'Tambah Materi' }}</button>
            <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection