@extends('layouts.app')

@section('title', 'Belajar: ' . $materi->judul)

@section('content')
@php
    $tombol = 'background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;';
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="color:#1a2a3a;">
    @include('partials.flash')

    <a href="{{ route('materi.show', $materi) }}" class="text-sm font-semibold">← {{ $materi->judul }}</a>
    <h1 class="font-serif text-4xl mt-2 mb-8">{{ $materi->judul }}</h1>

    @if ($materi->videos->isEmpty() && $materi->pdfs->isEmpty() && $materi->gambars->isEmpty())
        <div class="p-6 mb-6" style="background:#fff; border:2px solid #1a2a3a;">
            Instruktur belum menambahkan video, dokumen, atau gambar untuk materi ini.
        </div>
    @endif

    @include('partials.materi-konten', ['materi' => $materi])

    <div class="mt-10 p-6" style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #e8a838;">
        @if ($progress->progres === 'sertif')
            <p class="mb-4">Kamu sudah lulus materi ini.</p>
            <a href="{{ route('kelas.sertifikat', $progress->id) }}" class="inline-block px-6 py-3 font-bold" style="{{ $tombol }}">Lihat Sertifikat</a>
        @elseif (! $adaPostes)
            <p>Instruktur belum menyiapkan soal postes untuk materi ini. Silakan cek lagi nanti.</p>
        @elseif ($progress->progres === 'postes')
            <p class="mb-4">Sudah siap mencoba postes lagi? Nilai minimal {{ config('pelatihan.kkm') }} untuk lulus.</p>
            <a href="{{ route('kelas.tes', [$materi, 'postes']) }}" class="inline-block px-6 py-3 font-bold" style="{{ $tombol }}">Kerjakan Postes</a>
        @else
            <p class="mb-4">Sudah selesai mempelajari semuanya? Lanjut ke postes. Nilai minimal {{ config('pelatihan.kkm') }} untuk lulus.</p>
            <form method="POST" action="{{ route('kelas.selesai', $materi) }}">
                @csrf
                <button type="submit" class="px-6 py-3 font-bold" style="{{ $tombol }}">Selesai Belajar, Lanjut Postes</button>
            </form>
        @endif
    </div>
</div>
@endsection
