@extends('instruktur.layouts.app')

@section('title', 'Preview: ' . $materi->judul)
@section('page-title', 'Preview Materi')

@section('content')

<div class="mb-6 px-5 py-3 text-sm font-semibold"
     style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a;">
    Ini tampilan yang dilihat peserta. Kalau ada video atau file yang nggak muncul,
    cek dulu izin berbaginya di Google Drive.
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

        {{-- JUDUL --}}
        <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
            <div class="text-xs tracking-widest font-bold mb-2" style="color:#c8562f;">
                {{ strtoupper($materi->kategori) }}
                @if ($materi->subkategori) · {{ strtoupper($materi->subkategori) }} @endif
            </div>
            <h1 class="font-serif text-4xl mb-4" style="color:#1a2a3a;">{{ $materi->judul }}</h1>

            @if ($materi->deskripsi)
                <p class="text-sm leading-relaxed" style="color:#444;">{{ $materi->deskripsi }}</p>
            @endif

            @if ($materi->objectives)
                <div class="mt-6 pt-6" style="border-top:1px solid #ddd;">
                    <div class="text-xs tracking-widest font-bold mb-2" style="color:#c8562f;">TUJUAN PEMBELAJARAN</div>
                    <p class="text-sm leading-relaxed whitespace-pre-line" style="color:#444;">{{ $materi->objectives }}</p>
                </div>
            @endif
        </div>

        @include('partials.materi-konten', ['materi' => $materi])
    </div>

    {{-- SAMPING --}}
    <div class="space-y-6">
        <div class="p-6" style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #e8a838;">
            <div class="font-serif text-2xl mb-6" style="color:#e8a838;">Ringkasan</div>

            <dl class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <dt class="opacity-70">Jenis</dt>
                    <dd class="font-bold">{{ strtoupper($materi->jenis_materi ?? '—') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="opacity-70">Status</dt>
                    <dd class="font-bold">{{ strtoupper($materi->status ?? '—') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="opacity-70">Durasi</dt>
                    <dd class="font-bold">{{ $materi->duration_minutes ?? 0 }} menit</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="opacity-70">Lesson</dt>
                    <dd class="font-bold">{{ $materi->total_lessons ?? 0 }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="opacity-70">Learning point</dt>
                    <dd class="font-bold">{{ $materi->learning_points ?? 0 }} LP</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="opacity-70">Bahasa</dt>
                    <dd class="font-bold">{{ $materi->bahasa ?? '—' }}</dd>
                </div>
            </dl>

            <div class="mt-6 pt-6 space-y-3" style="border-top:1px solid rgba(245,240,225,0.2);">
                <a href="{{ route('instruktur.materi.edit', $materi->id) }}"
                   class="block w-full text-center px-4 py-3 font-bold"
                   style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a;">
                    Edit Materi
                </a>
                <a href="{{ route('instruktur.soal.index', ['materi' => $materi->id]) }}"
                   class="block w-full text-center px-4 py-3 font-bold"
                   style="background:#f5f0e1; color:#1a2a3a; border:2px solid #1a2a3a;">
                    Kelola Soal
                </a>
                <a href="{{ route('instruktur.materi.index') }}"
                   class="block w-full text-center px-4 py-2 text-sm font-semibold opacity-80">
                    ← Kembali ke daftar
                </a>
            </div>
        </div>
    </div>
</div>

@endsection