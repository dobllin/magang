@extends('instruktur.layouts.app')

@section('title', 'Peserta')
@section('page-title', 'Peserta')

@section('content')

@include('instruktur.partials.notifikasi')

@php
    $gayaInput = 'padding:10px 12px; border:2px solid #1a2a3a; background:#fff; color:#1a2a3a;';

    // Urutan tahapan sesuai alur belajar di sistem ini.
    $tahapan = [
        'pretes' => ['label' => 'Pretes',     'warna' => '#e8a838', 'teks' => '#1a2a3a'],
        'view'   => ['label' => 'Baca Materi','warna' => '#7a8f6b', 'teks' => '#f5f0e1'],
        'postes' => ['label' => 'Postes',     'warna' => '#c8562f', 'teks' => '#f5f0e1'],
        'sertif' => ['label' => 'Sertifikat', 'warna' => '#1a2a3a', 'teks' => '#e8a838'],
    ];
@endphp

{{-- RINGKASAN PER TAHAP --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

    <div class="p-5" style="background:#fff; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">
        <div class="text-xs tracking-widest font-bold mb-2" style="color:#c8562f;">TOTAL</div>
        <div class="font-serif text-4xl" style="color:#1a2a3a;">{{ $totalPeserta }}</div>
    </div>

    @foreach ($tahapan as $kunci => $gaya)
        <div class="p-5" style="background:{{ $gaya['warna'] }}; color:{{ $gaya['teks'] }}; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">
            <div class="text-xs tracking-widest font-bold mb-2">{{ strtoupper($gaya['label']) }}</div>
            <div class="font-serif text-4xl">{{ $ringkasan[$kunci] ?? 0 }}</div>
        </div>
    @endforeach
</div>

{{-- SARINGAN --}}
<form method="GET" action="{{ route('instruktur.peserta.index') }}"
      class="flex flex-wrap gap-3 mb-8 p-4"
      style="background:#fff; border:2px solid #1a2a3a;">

    <input type="text" name="cari" value="{{ $kataCari }}"
           placeholder="Cari nama atau email" style="{{ $gayaInput }} min-width:220px;">

    <select name="materi" style="{{ $gayaInput }}">
        <option value="">Semua materi</option>
        @foreach ($daftarMateri as $m)
            <option value="{{ $m->id }}" @selected($filterMateri == $m->id)>{{ $m->judul }}</option>
        @endforeach
    </select>

    <select name="tahap" style="{{ $gayaInput }}">
        <option value="">Semua tahap</option>
        @foreach ($tahapan as $kunci => $gaya)
            <option value="{{ $kunci }}" @selected($filterTahap === $kunci)>{{ $gaya['label'] }}</option>
        @endforeach
    </select>

    <button type="submit" class="px-5 py-2 font-bold"
            style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a;">
        Terapkan
    </button>

    @if ($kataCari || $filterMateri || $filterTahap)
        <a href="{{ route('instruktur.peserta.index') }}" class="px-5 py-2 font-bold"
           style="background:#f5f0e1; color:#1a2a3a; border:2px solid #1a2a3a;">
            Reset
        </a>
    @endif
</form>

@if ($baris->isEmpty())

    <div class="p-12 text-center" style="background:#fff; border:2px dashed #1a2a3a;">
        <div class="text-5xl mb-4">👥</div>
        <div class="font-serif text-2xl mb-2" style="color:#1a2a3a;">
            {{ $kataCari || $filterMateri || $filterTahap ? 'Nggak ada yang cocok dengan saringan itu' : 'Belum ada peserta' }}
        </div>
        <p class="text-sm" style="color:#666;">
            {{ $kataCari || $filterMateri || $filterTahap
                ? 'Coba longgarkan saringannya.'
                : 'Peserta akan muncul di sini begitu mereka mulai mengambil materi Anda.' }}
        </p>
    </div>

@else

    <div class="overflow-x-auto" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:#1a2a3a; color:#f5f0e1;">
                    <th class="text-left px-4 py-3 font-bold text-xs tracking-widest">PESERTA</th>
                    <th class="text-left px-4 py-3 font-bold text-xs tracking-widest">MATERI</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">TAHAP</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">NILAI</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">HASIL</th>
                    <th class="text-right px-4 py-3 font-bold text-xs tracking-widest">MULAI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($baris as $b)
                    <tr style="border-top:1px solid #ddd;">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 flex items-center justify-center font-bold text-sm shrink-0"
                                     style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a;">
                                    {{ strtoupper(substr($b->nama_lengkap ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold" style="color:#1a2a3a;">{{ $b->nama_lengkap }}</div>
                                    <div class="text-xs" style="color:#888;">{{ $b->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <div style="color:#1a2a3a;">{{ $b->judul_materi }}</div>
                            <div class="text-xs" style="color:#888;">{{ $b->kategori }}</div>
                        </td>

                        <td class="px-4 py-4 text-center">
                            @php $g = $tahapan[$b->progres] ?? ['label' => $b->progres, 'warna' => '#ccc', 'teks' => '#555']; @endphp
                            <span class="text-xs px-2 py-1 font-bold whitespace-nowrap"
                                  style="background:{{ $g['warna'] }}; color:{{ $g['teks'] }};">
                                {{ strtoupper($g['label']) }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-center font-serif text-2xl" style="color:#1a2a3a;">
                            {{ $b->nilai !== null ? rtrim(rtrim(number_format($b->nilai, 1), '0'), '.') : '—' }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if ($b->status_penilaian)
                                @php $lulus = strtolower($b->status_penilaian) === 'lulus'; @endphp
                                <span class="text-xs px-2 py-1 font-bold"
                                      style="background:{{ $lulus ? '#7a8f6b' : '#c8562f' }}; color:#f5f0e1;">
                                    {{ strtoupper($b->status_penilaian) }}
                                </span>
                            @else
                                <span class="text-xs" style="color:#aaa;">belum dinilai</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-right text-xs whitespace-nowrap" style="color:#888;">
                            {{ $b->mulai_pada ? \Carbon\Carbon::parse($b->mulai_pada)->translatedFormat('d M Y') : '—' }}
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