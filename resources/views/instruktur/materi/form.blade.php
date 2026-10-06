@extends('instruktur.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Materi' : 'Tambah Materi')
@section('page-title', $mode === 'edit' ? 'Edit Materi' : 'Tambah Materi')

@section('content')

@include('instruktur.partials.notifikasi')

@php
    // Kalau validasi gagal, tampilkan lagi yang barusan diketik.
    // Kalau nggak, tampilkan yang tersimpan di database.
    $videoAwal  = old('video',  $videoLama  ?: ['']);
    $pdfAwal    = old('pdf',    $pdfLama    ?: ['']);
    $gambarAwal = old('gambar', $gambarLama ?: ['']);

    $gayaInput = 'width:100%; padding:10px 12px; border:2px solid #1a2a3a; background:#fff; color:#1a2a3a;';
    $gayaLabel = 'display:block; font-size:11px; letter-spacing:0.1em; font-weight:700; color:#c8562f; margin-bottom:6px;';
@endphp

<form method="POST"
      action="{{ $mode === 'edit' ? route('instruktur.materi.update', $materi->id) : route('instruktur.materi.store') }}">
    @csrf
    @if ($mode === 'edit')
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ============================================ --}}
        {{-- KOLOM KIRI: IDENTITAS MATERI                 --}}
        {{-- ============================================ --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                <div class="font-serif text-2xl mb-6" style="color:#1a2a3a;">Identitas Materi</div>

                <div class="mb-5">
                    <label style="{{ $gayaLabel }}">JUDUL MATERI *</label>
                    <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}"
                           style="{{ $gayaInput }}" placeholder="Perencanaan Produksi dan Material Requirement Planning">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label style="{{ $gayaLabel }}">KATEGORI *</label>
                        <input type="text" name="kategori" value="{{ old('kategori', $materi->kategori) }}"
                               style="{{ $gayaInput }}" placeholder="Manufaktur">
                    </div>
                    <div>
                        <label style="{{ $gayaLabel }}">SUBKATEGORI</label>
                        <input type="text" name="subkategori" value="{{ old('subkategori', $materi->subkategori) }}"
                               style="{{ $gayaInput }}" placeholder="Perencanaan Produksi">
                    </div>
                </div>

                <div class="mb-5">
                    <label style="{{ $gayaLabel }}">DESKRIPSI</label>
                    <textarea name="deskripsi" rows="4" style="{{ $gayaInput }}"
                              placeholder="Ceritakan singkat isi materi ini buat peserta.">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                </div>

                <div>
                    <label style="{{ $gayaLabel }}">TUJUAN PEMBELAJARAN</label>
                    <textarea name="objectives" rows="4" style="{{ $gayaInput }}"
                              placeholder="Setelah menyelesaikan materi ini, peserta mampu...">{{ old('objectives', $materi->objectives) }}</textarea>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- VIDEO YOUTUBE                                --}}
            {{-- ============================================ --}}
            <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                <div class="flex items-start justify-between mb-2">
                    <div class="font-serif text-2xl" style="color:#1a2a3a;">🎬 Video YouTube</div>
                    <button type="button" onclick="tambahBaris('kotak-video')"
                            class="text-xs px-3 py-2 font-bold"
                            style="background:#e8a838; color:#1a2a3a; border:1px solid #1a2a3a;">
                        + Baris
                    </button>
                </div>
                <p class="text-xs mb-5" style="color:#888;">
                    Tempel link tontonannya langsung, nggak usah diubah apa-apa.
                    Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ
                </p>

                <div id="kotak-video" class="space-y-3">
                    @foreach ($videoAwal as $url)
                        <div class="flex gap-2">
                            <input type="text" name="video[]" value="{{ $url }}"
                                   style="{{ $gayaInput }}" placeholder="https://www.youtube.com/watch?v=...">
                            <button type="button" onclick="hapusBaris(this)"
                                    class="px-3 font-bold"
                                    style="background:#c8562f; color:#f5f0e1; border:2px solid #1a2a3a;">×</button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- PDF GOOGLE DRIVE                             --}}
            {{-- ============================================ --}}
            <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                <div class="flex items-start justify-between mb-2">
                    <div class="font-serif text-2xl" style="color:#1a2a3a;">📄 PDF dari Google Drive</div>
                    <button type="button" onclick="tambahBaris('kotak-pdf')"
                            class="text-xs px-3 py-2 font-bold"
                            style="background:#e8a838; color:#1a2a3a; border:1px solid #1a2a3a;">
                        + Baris
                    </button>
                </div>
                <p class="text-xs mb-5" style="color:#888;">
                    Di Google Drive: klik kanan file → Bagikan → ubah jadi
                    <strong>Siapa saja yang memiliki link</strong> → Salin link → tempel di sini.
                </p>

                <div id="kotak-pdf" class="space-y-3">
                    @foreach ($pdfAwal as $url)
                        <div class="flex gap-2">
                            <input type="text" name="pdf[]" value="{{ $url }}"
                                   style="{{ $gayaInput }}" placeholder="https://drive.google.com/file/d/.../view">
                            <button type="button" onclick="hapusBaris(this)"
                                    class="px-3 font-bold"
                                    style="background:#c8562f; color:#f5f0e1; border:2px solid #1a2a3a;">×</button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- GAMBAR / GIF GOOGLE DRIVE                    --}}
            {{-- ============================================ --}}
            <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                <div class="flex items-start justify-between mb-2">
                    <div class="font-serif text-2xl" style="color:#1a2a3a;">🖼️ Gambar / GIF</div>
                    <button type="button" onclick="tambahBaris('kotak-gambar')"
                            class="text-xs px-3 py-2 font-bold"
                            style="background:#e8a838; color:#1a2a3a; border:1px solid #1a2a3a;">
                        + Baris
                    </button>
                </div>
                <p class="text-xs mb-5" style="color:#888;">
                    Sama seperti PDF, ambil link berbagi dari Google Drive.
                </p>

                <div id="kotak-gambar" class="space-y-3">
                    @foreach ($gambarAwal as $url)
                        <div class="flex gap-2">
                            <input type="text" name="gambar[]" value="{{ $url }}"
                                   style="{{ $gayaInput }}" placeholder="https://drive.google.com/file/d/.../view">
                            <button type="button" onclick="hapusBaris(this)"
                                    class="px-3 font-bold"
                                    style="background:#c8562f; color:#f5f0e1; border:2px solid #1a2a3a;">×</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- KOLOM KANAN: PENGATURAN                      --}}
        {{-- ============================================ --}}
        <div class="space-y-6">

            <div class="p-6" style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #e8a838;">
                <div class="font-serif text-2xl mb-6" style="color:#e8a838;">Pengaturan</div>

                <div class="mb-5">
                    <label style="{{ $gayaLabel }}">JENIS MATERI *</label>
                    @if ($pilihanJenis)
                        <select name="jenis_materi" style="{{ $gayaInput }}">
                            <option value="">— pilih —</option>
                            @foreach ($pilihanJenis as $p)
                                <option value="{{ $p }}" @selected(old('jenis_materi', $materi->jenis_materi) === $p)>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" name="jenis_materi"
                               value="{{ old('jenis_materi', $materi->jenis_materi) }}" style="{{ $gayaInput }}">
                    @endif
                </div>

                <div class="mb-5">
                    <label style="{{ $gayaLabel }}">STATUS *</label>
                    @if ($pilihanStatus)
                        <select name="status" style="{{ $gayaInput }}">
                            <option value="">— pilih —</option>
                            @foreach ($pilihanStatus as $p)
                                <option value="{{ $p }}" @selected(old('status', $materi->status) === $p)>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" name="status"
                               value="{{ old('status', $materi->status) }}" style="{{ $gayaInput }}">
                    @endif
                    <p class="text-xs mt-2" style="color:#9bb;">
                        Cuma materi berstatus terbit yang kelihatan di halaman depan.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label style="{{ $gayaLabel }}">DURASI (MENIT)</label>
                        <input type="number" name="duration_minutes" min="0"
                               value="{{ old('duration_minutes', $materi->duration_minutes) }}" style="{{ $gayaInput }}">
                    </div>
                    <div>
                        <label style="{{ $gayaLabel }}">JUMLAH LESSON</label>
                        <input type="number" name="total_lessons" min="0"
                               value="{{ old('total_lessons', $materi->total_lessons) }}" style="{{ $gayaInput }}">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label style="{{ $gayaLabel }}">LEARNING POINT</label>
                        <input type="number" name="learning_points" min="0"
                               value="{{ old('learning_points', $materi->learning_points) }}" style="{{ $gayaInput }}">
                    </div>
                    <div>
                        <label style="{{ $gayaLabel }}">BAHASA</label>
                        <input type="text" name="bahasa"
                               value="{{ old('bahasa', $materi->bahasa ?? 'Indonesia') }}" style="{{ $gayaInput }}">
                    </div>
                </div>

                <div>
                    <label style="{{ $gayaLabel }}">THUMBNAIL</label>
                    <input type="text" name="thumbnail" value="{{ old('thumbnail', $materi->thumbnail) }}"
                           style="{{ $gayaInput }}" placeholder="Link Drive / YouTube / URL gambar">
                    <p class="text-xs mt-2" style="color:#9bb;">
                        Boleh dikosongkan. Kalau kosong, sampul video pertama yang dipakai.
                    </p>
                </div>
            </div>

            <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                <button type="submit"
                        class="w-full px-6 py-4 font-bold mb-3"
                        style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">
                    {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Materi' }}
                </button>

                <a href="{{ route('instruktur.materi.index') }}"
                   class="block w-full text-center px-6 py-3 font-bold"
                   style="background:#f5f0e1; color:#1a2a3a; border:2px solid #1a2a3a;">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>

<script>
    // Nambah dan ngurangin baris link, tanpa perlu reload halaman.
    function tambahBaris(idKotak) {
        const kotak = document.getElementById(idKotak);
        const contoh = kotak.querySelector('.flex');
        const baru = contoh.cloneNode(true);
        baru.querySelector('input').value = '';
        kotak.appendChild(baru);
        baru.querySelector('input').focus();
    }

    function hapusBaris(tombol) {
        const kotak = tombol.closest('div[id]') || tombol.parentElement.parentElement;
        const baris = tombol.parentElement;

        // Sisakan minimal satu baris, biar tombol "+ Baris" masih punya
        // contoh buat digandakan.
        if (kotak.querySelectorAll('.flex').length > 1) {
            baris.remove();
        } else {
            baris.querySelector('input').value = '';
        }
    }
</script>

@endsection