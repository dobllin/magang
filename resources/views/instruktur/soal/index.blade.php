@extends('instruktur.layouts.app')

@section('title', 'Bank Soal')
@section('page-title', 'Bank Soal')

@section('content')

@include('instruktur.partials.notifikasi')

@php
    $jumlahSoal = $soalPerMateri->flatten()->count();
    $gayaInput  = 'padding:10px 12px; border:2px solid #1a2a3a; background:#fff; color:#1a2a3a;';
@endphp

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <div class="text-xs tracking-widest font-bold mb-1" style="color:#c8562f;">PERTANYAAN TERSIMPAN</div>
        <div class="font-serif text-3xl" style="color:#1a2a3a;">{{ $jumlahSoal }} soal</div>
    </div>

    <a href="{{ route('instruktur.soal.create', ['materi' => $filterMateri]) }}"
       class="px-6 py-3 font-bold"
       style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">
        + Tambah Soal
    </a>
</div>

{{-- SARINGAN --}}
<form method="GET" action="{{ route('instruktur.soal.index') }}"
      class="flex flex-wrap gap-3 mb-8 p-4"
      style="background:#fff; border:2px solid #1a2a3a;">

    <select name="materi" style="{{ $gayaInput }}">
        <option value="">Semua materi</option>
        @foreach ($daftarMateri as $m)
            <option value="{{ $m->id }}" @selected($filterMateri == $m->id)>{{ $m->judul }}</option>
        @endforeach
    </select>

    @if ($pilihanJenis)
        <select name="jenis" style="{{ $gayaInput }}">
            <option value="">Pretes & postes</option>
            @foreach ($pilihanJenis as $p)
                <option value="{{ $p }}" @selected($filterJenis === $p)>{{ ucfirst($p) }}</option>
            @endforeach
        </select>
    @endif

    <button type="submit" class="px-5 py-2 font-bold"
            style="background:#1a2a3a; color:#f5f0e1; border:2px solid #1a2a3a;">
        Terapkan
    </button>

    @if ($filterMateri || $filterJenis)
        <a href="{{ route('instruktur.soal.index') }}" class="px-5 py-2 font-bold"
           style="background:#f5f0e1; color:#1a2a3a; border:2px solid #1a2a3a;">
            Reset
        </a>
    @endif
</form>

@if ($jumlahSoal === 0)

    <div class="p-12 text-center" style="background:#fff; border:2px dashed #1a2a3a;">
        <div class="text-5xl mb-4">📝</div>
        <div class="font-serif text-2xl mb-2" style="color:#1a2a3a;">Belum ada soal</div>
        <p class="text-sm mb-6" style="color:#666;">
            Soal pretes dipakai buat mengukur kemampuan awal, soal postes buat mengukur hasil belajar.
        </p>
        <a href="{{ route('instruktur.soal.create') }}"
           class="inline-block px-6 py-3 font-bold"
           style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;">
            Tulis soal pertama
        </a>
    </div>

@else

    @foreach ($soalPerMateri as $judulMateri => $kumpulan)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <div class="font-serif text-2xl" style="color:#1a2a3a;">{{ $judulMateri }}</div>
                <div class="text-xs font-bold" style="color:#888;">{{ $kumpulan->count() }} soal</div>
            </div>

            <div style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                @foreach ($kumpulan as $nomor => $s)
                    <div class="p-5 {{ $nomor > 0 ? 'border-t' : '' }}" style="border-color:#ddd;">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-serif text-xl" style="color:#c8562f;">
                                        {{ str_pad($nomor + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="text-xs px-2 py-1 font-bold"
                                          style="background:#7a8f6b; color:#f5f0e1;">
                                        {{ strtoupper($s->jenis_soal ?? '—') }}
                                    </span>
                                </div>

                                <p class="font-semibold mb-2" style="color:#1a2a3a;">{{ $s->pertanyaan }}</p>

                                <div class="text-sm" style="color:#666;">
                                    <span class="font-bold" style="color:#7a8f6b;">Kunci:</span>
                                    {{ $s->kunci_jawaban }}
                                </div>
                            </div>

                            <div class="flex gap-2 whitespace-nowrap">
                                <a href="{{ route('instruktur.soal.edit', $s->id) }}"
                                   class="text-xs px-3 py-2 font-bold"
                                   style="background:#e8a838; color:#1a2a3a; border:1px solid #1a2a3a;">
                                    Edit
                                </a>
                                <form action="{{ route('instruktur.soal.destroy', $s->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus soal nomor {{ $nomor + 1 }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs px-3 py-2 font-bold"
                                            style="background:#c8562f; color:#f5f0e1; border:1px solid #1a2a3a;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

@endif

@endsection