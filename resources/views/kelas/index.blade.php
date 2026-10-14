@extends('layouts.app')

@section('title', 'Kelas Saya — PPKPI')

@section('content')
@php
    $tahap = [
        'pretes' => ['Pretes', '#e8a838'],
        'view'   => ['Belajar', '#7a8f6b'],
        'postes' => ['Postes', '#c8562f'],
        'sertif' => ['Lulus', '#1a2a3a'],
    ];
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="color:#1a2a3a;">
    @include('partials.flash')

    <h1 class="font-serif text-4xl mb-8">Kelas Saya</h1>

    @if ($kelas->isEmpty())
        <div class="p-8 text-center" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
            <div class="font-serif text-2xl mb-2">Belum ada materi yang kamu ikuti</div>
            <p class="text-sm mb-6" style="color:#555;">Pilih materi di halaman depan, lalu klik "Ikuti Materi Ini".</p>
            <a href="{{ route('landing') }}#courses" class="inline-block px-6 py-3 font-bold"
               style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">Lihat Materi</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($kelas as $k)
                @continue(! $k->materi)
                @php [$label, $warna] = $tahap[$k->progres] ?? [$k->progres, '#888']; @endphp
                <div class="p-6 flex flex-col" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs tracking-widest font-bold" style="color:#c8562f;">{{ strtoupper($k->materi->kategori) }}</span>
                        <span class="text-xs px-2 py-1 font-bold" style="background:{{ $warna }}; color:#f5f0e1;">{{ strtoupper($label) }}</span>
                    </div>
                    <div class="font-serif text-2xl mb-1">{{ $k->materi->judul }}</div>
                    <div class="text-sm mb-4" style="color:#555;">
                        {{ $k->materi->instruktur?->user?->nama_lengkap ?? '—' }}
                        @if ($k->penilaian?->nilai !== null)
                            · Nilai postes {{ rtrim(rtrim(number_format($k->penilaian->nilai, 2), '0'), '.') }}
                        @endif
                    </div>

                    <div class="mt-auto">
                        @if ($k->progres === 'sertif')
                            <a href="{{ route('kelas.sertifikat', $k->id) }}" class="font-bold underline">Lihat Sertifikat →</a>
                        @else
                            <a href="{{ route('materi.show', $k->materi) }}" class="font-bold underline">Lanjutkan →</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
