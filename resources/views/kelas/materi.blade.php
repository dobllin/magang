@extends('layouts.app')

@section('title', $materi->judul . ' — PPKPI')

@section('content')
@php
    $kartu = 'background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;';
    $tombol = 'background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;';
    $tahap = [
        'pretes' => 'Pretes',
        'view'   => 'Belajar',
        'postes' => 'Postes',
        'sertif' => 'Lulus',
    ];
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="color:#1a2a3a;">
    @include('partials.flash')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 p-8" style="{{ $kartu }}">
            <div class="text-xs tracking-widest font-bold mb-2" style="color:#c8562f;">
                {{ strtoupper($materi->kategori) }}
                @if ($materi->subkategori) · {{ strtoupper($materi->subkategori) }} @endif
            </div>
            <h1 class="font-serif text-4xl mb-4">{{ $materi->judul }}</h1>

            @if ($materi->deskripsi)
                <p class="leading-relaxed mb-6" style="color:#444;">{{ $materi->deskripsi }}</p>
            @endif

            @if ($materi->objectives)
                <div class="pt-6" style="border-top:1px solid #ddd;">
                    <div class="text-xs tracking-widest font-bold mb-2" style="color:#c8562f;">TUJUAN PEMBELAJARAN</div>
                    <p class="text-sm leading-relaxed whitespace-pre-line" style="color:#444;">{{ $materi->objectives }}</p>
                </div>
            @endif

            <div class="pt-6 mt-6" style="border-top:1px solid #ddd;">
                <div class="text-xs tracking-widest font-bold mb-3" style="color:#c8562f;">ALUR BELAJAR</div>
                <ol class="text-sm space-y-2" style="color:#444;">
                    @if ($materi->jumlah_pretes)
                        <li>1. Pretes ({{ $materi->jumlah_pretes }} soal) untuk mengukur kemampuan awal.</li>
                    @endif
                    <li>{{ $materi->jumlah_pretes ? 2 : 1 }}. Pelajari {{ $materi->videos_count }} video, {{ $materi->pdfs_count }} dokumen, dan {{ $materi->gambars_count }} gambar.</li>
                    <li>{{ $materi->jumlah_pretes ? 3 : 2 }}. Postes ({{ $materi->jumlah_postes }} soal). Nilai minimal {{ config('pelatihan.kkm') }} untuk lulus.</li>
                    <li>{{ $materi->jumlah_pretes ? 4 : 3 }}. Sertifikat terbit otomatis begitu lulus.</li>
                </ol>
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-6" style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #e8a838;">
                <dl class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between"><dt class="opacity-70">Instruktur</dt><dd class="font-bold text-right">{{ $materi->instruktur?->user?->nama_lengkap ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="opacity-70">Durasi</dt><dd class="font-bold">{{ $materi->duration_minutes ?? 0 }} menit</dd></div>
                    <div class="flex justify-between"><dt class="opacity-70">Lesson</dt><dd class="font-bold">{{ $materi->total_lessons ?? 0 }}</dd></div>
                    <div class="flex justify-between"><dt class="opacity-70">Learning point</dt><dd class="font-bold">{{ $materi->learning_points ?? 0 }} LP</dd></div>
                    <div class="flex justify-between"><dt class="opacity-70">Bahasa</dt><dd class="font-bold">{{ $materi->bahasa ?? '—' }}</dd></div>
                </dl>

                @guest
                    <a href="{{ route('login') }}" class="block text-center px-4 py-3 font-bold" style="{{ $tombol }}">Masuk untuk Mulai Belajar</a>
                    <a href="{{ route('register') }}" class="block text-center mt-3 text-sm underline">Belum punya akun? Daftar</a>
                @else
                    @if (auth()->user()->role !== 'peserta')
                        <p class="text-sm opacity-80">Fitur belajar khusus akun peserta.</p>
                    @elseif (! $progress)
                        <form method="POST" action="{{ route('kelas.ikuti', $materi) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 font-bold" style="{{ $tombol }}">Ikuti Materi Ini</button>
                        </form>
                    @else
                        <div class="text-xs tracking-widest font-bold mb-2" style="color:#e8a838;">TAHAP KAMU</div>
                        <div class="font-serif text-2xl mb-4">{{ $tahap[$progress->progres] ?? $progress->progres }}</div>

                        @if ($progress->progres === 'sertif')
                            <a href="{{ route('kelas.sertifikat', $progress->id) }}" class="block text-center px-4 py-3 font-bold" style="{{ $tombol }}">Lihat Sertifikat</a>
                        @elseif ($progress->progres === 'pretes')
                            <a href="{{ route('kelas.tes', [$materi, 'pretes']) }}" class="block text-center px-4 py-3 font-bold" style="{{ $tombol }}">Kerjakan Pretes</a>
                        @else
                            <a href="{{ route('kelas.belajar', $materi) }}" class="block text-center px-4 py-3 font-bold" style="{{ $tombol }}">Lanjut Belajar</a>
                        @endif
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
