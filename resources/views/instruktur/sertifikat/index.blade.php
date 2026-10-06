@extends('instruktur.layouts.app')

@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

@section('content')

@include('instruktur.partials.notifikasi')

@php
    $gayaInput = 'padding:10px 12px; border:2px solid #1a2a3a; background:#fff; color:#1a2a3a;';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <div class="p-6" style="background:#7a8f6b; color:#f5f0e1; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
        <div class="text-xs tracking-widest font-bold mb-2">PESERTA LULUS</div>
        <div class="font-serif text-5xl leading-none">{{ $jumlahLulus }}</div>
    </div>

    <div class="p-6" style="background:#1a2a3a; color:#e8a838; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
        <div class="text-xs tracking-widest font-bold mb-2">RATA NILAI KELULUSAN</div>
        <div class="font-serif text-5xl leading-none">{{ $rataNilai }}</div>
    </div>
</div>

{{-- SARINGAN --}}
<form method="GET" action="{{ route('instruktur.sertifikat.index') }}"
      class="flex flex-wrap gap-3 mb-8 p-4"
      style="background:#fff; border:2px solid #1a2a3a;">

    <input type="text" name="cari" value="{{ $kataCari }}"
           placeholder="Cari nama peserta" style="{{ $gayaInput }} min-width:220px;">

    <select name="materi" style="{{ $gayaInput }}">
        <option value="">Semua materi</option>
        @foreach ($daftarMateri as $m)
            <option value="{{ $m->id }}" @selected($filterMateri == $m->id)>{{ $m->judul }}</option>
        @endforeach
    </select>

    <button type="submit" class="px-5 py-2 font-bold"
            style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a;">
        Terapkan
    </button>

    @if ($kataCari || $filterMateri)
        <a href="{{ route('instruktur.sertifikat.index') }}" class="px-5 py-2 font-bold"
           style="background:#f5f0e1; color:#1a2a3a; border:2px solid #1a2a3a;">
            Reset
        </a>
    @endif
</form>

@if ($baris->isEmpty())

    <div class="p-12 text-center" style="background:#fff; border:2px dashed #1a2a3a;">
        <div class="text-5xl mb-4">🏆</div>
        <div class="font-serif text-2xl mb-2" style="color:#1a2a3a;">Belum ada yang lulus</div>
        <p class="text-sm" style="color:#666;">
            Sertifikat terbit otomatis begitu peserta dinyatakan lulus pada postes.
        </p>
    </div>

@else

    <div class="overflow-x-auto" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:#1a2a3a; color:#f5f0e1;">
                    <th class="text-left px-4 py-3 font-bold text-xs tracking-widest">PESERTA</th>
                    <th class="text-left px-4 py-3 font-bold text-xs tracking-widest">MATERI</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">NILAI</th>
                    <th class="text-right px-4 py-3 font-bold text-xs tracking-widest">TANGGAL LULUS</th>
                    <th class="text-right px-4 py-3 font-bold text-xs tracking-widest">SERTIFIKAT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($baris as $b)
                    <tr style="border-top:1px solid #ddd;">
                        <td class="px-4 py-4">
                            <div class="font-bold" style="color:#1a2a3a;">{{ $b->nama_lengkap }}</div>
                            <div class="text-xs" style="color:#888;">
                                {{ $b->nis ? 'NIS ' . $b->nis : $b->email }}
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <div style="color:#1a2a3a;">{{ $b->judul_materi }}</div>
                            <div class="text-xs" style="color:#888;">{{ $b->kategori }}</div>
                        </td>

                        <td class="px-4 py-4 text-center font-serif text-2xl" style="color:#7a8f6b;">
                            {{ $b->nilai !== null ? rtrim(rtrim(number_format($b->nilai, 1), '0'), '.') : '—' }}
                        </td>

                        <td class="px-4 py-4 text-right text-xs whitespace-nowrap" style="color:#888;">
                            {{ $b->lulus_pada ? \Carbon\Carbon::parse($b->lulus_pada)->translatedFormat('d M Y') : '—' }}
                        </td>

                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('instruktur.sertifikat.show', $b->id_progress) }}"
                               target="_blank" rel="noopener"
                               class="text-xs px-4 py-2 font-bold whitespace-nowrap inline-block"
                               style="background:#e8a838; color:#1a2a3a; border:1px solid #1a2a3a;">
                                Lihat & Cetak ↗
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $baris->links() }}
    </div>

@endif

@endsection