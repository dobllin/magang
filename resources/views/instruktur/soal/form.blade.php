@extends('instruktur.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Soal' : 'Tambah Soal')
@section('page-title', $mode === 'edit' ? 'Edit Soal' : 'Tambah Soal')

@section('content')

@include('instruktur.partials.notifikasi')

@php
    $gayaInput = 'width:100%; padding:10px 12px; border:2px solid #1a2a3a; background:#fff; color:#1a2a3a;';
    $gayaLabel = 'display:block; font-size:11px; letter-spacing:0.1em; font-weight:700; color:#c8562f; margin-bottom:6px;';
@endphp

<form method="POST"
      action="{{ $mode === 'edit' ? route('instruktur.soal.update', $soal->id) : route('instruktur.soal.store') }}">
    @csrf
    @if ($mode === 'edit')
        @method('PUT')
    @endif

    <div class="max-w-3xl">
        <div class="p-6 mb-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label style="{{ $gayaLabel }}">MATERI *</label>
                    <select name="id_materi" style="{{ $gayaInput }}">
                        <option value="">— pilih materi —</option>
                        @foreach ($daftarMateri as $m)
                            <option value="{{ $m->id }}" @selected(old('id_materi', $soal->id_materi) == $m->id)>
                                {{ $m->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="{{ $gayaLabel }}">JENIS SOAL *</label>
                    @if ($pilihanJenis)
                        <select name="jenis_soal" style="{{ $gayaInput }}">
                            <option value="">— pilih —</option>
                            @foreach ($pilihanJenis as $p)
                                <option value="{{ $p }}" @selected(old('jenis_soal', $soal->jenis_soal) === $p)>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" name="jenis_soal"
                               value="{{ old('jenis_soal', $soal->jenis_soal) }}" style="{{ $gayaInput }}">
                    @endif
                </div>
            </div>

            <div class="mb-5">
                <label style="{{ $gayaLabel }}">PERTANYAAN *</label>
                <textarea name="pertanyaan" rows="5" style="{{ $gayaInput }}"
                          placeholder="Apa fungsi utama Material Requirement Planning dalam proses produksi?">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
            </div>

            <div>
                <label style="{{ $gayaLabel }}">KUNCI JAWABAN *</label>
                <textarea name="kunci_jawaban" rows="4" style="{{ $gayaInput }}"
                          placeholder="Tulis jawaban benarnya di sini.">{{ old('kunci_jawaban', $soal->kunci_jawaban) }}</textarea>
                <p class="text-xs mt-2" style="color:#888;">
                    Kunci jawaban cuma kelihatan oleh instruktur, nggak ditampilkan ke peserta.
                </p>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-8 py-4 font-bold"
                    style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">
                {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Soal' }}
            </button>

            <a href="{{ route('instruktur.soal.index') }}" class="px-8 py-4 font-bold"
               style="background:#f5f0e1; color:#1a2a3a; border:2px solid #1a2a3a;">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection