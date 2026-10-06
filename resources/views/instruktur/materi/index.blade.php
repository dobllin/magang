@extends('instruktur.layouts.app')

@section('title', 'Materi Saya')
@section('page-title', 'Materi Saya')

@section('content')

@include('instruktur.partials.notifikasi')

<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <div class="text-xs tracking-widest font-bold mb-1" style="color: #c8562f;">KOLEKSI</div>
        <div class="font-serif text-3xl" style="color: #1a2a3a;">
            {{ $materis->count() }} materi
        </div>
    </div>

    <a href="{{ route('instruktur.materi.create') }}"
       class="px-6 py-3 font-bold"
       style="background: #e8a838; color: #1a2a3a; border: 2px solid #1a2a3a; box-shadow: 4px 4px 0 #1a2a3a;">
        + Tambah Materi
    </a>
</div>

@if ($materis->isEmpty())

    <div class="p-12 text-center" style="background: #fff; border: 2px dashed #1a2a3a;">
        <div class="text-5xl mb-4">📚</div>
        <div class="font-serif text-2xl mb-2" style="color: #1a2a3a;">Belum ada materi di sini</div>
        <p class="text-sm mb-6" style="color: #666;">
            Materi pertama Anda bisa berisi video YouTube, PDF, dan gambar dari Google Drive.
        </p>
        <a href="{{ route('instruktur.materi.create') }}"
           class="inline-block px-6 py-3 font-bold"
           style="background: #e8a838; color: #1a2a3a; border: 2px solid #1a2a3a; box-shadow: 4px 4px 0 #1a2a3a;">
            Buat materi pertama
        </a>
    </div>

@else

    <div class="overflow-x-auto" style="background: #fff; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <table class="w-full text-sm">
            <thead>
                <tr style="background: #1a2a3a; color: #f5f0e1;">
                    <th class="text-left px-4 py-3 font-bold text-xs tracking-widest">MATERI</th>
                    <th class="text-left px-4 py-3 font-bold text-xs tracking-widest">JENIS</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">ISI</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">SOAL</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">PESERTA</th>
                    <th class="text-center px-4 py-3 font-bold text-xs tracking-widest">STATUS</th>
                    <th class="text-right px-4 py-3 font-bold text-xs tracking-widest">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materis as $m)
                    <tr style="border-top: 1px solid #ddd;">
                        <td class="px-4 py-4">
                            <div class="font-bold" style="color: #1a2a3a;">{{ $m->judul }}</div>
                            <div class="text-xs mt-1" style="color: #888;">
                                {{ $m->kategori }}
                                @if ($m->subkategori) · {{ $m->subkategori }} @endif
                                @if ($m->duration_minutes) · {{ $m->duration_minutes }} menit @endif
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-xs px-2 py-1 font-bold"
                                  style="background: #7a8f6b; color: #f5f0e1;">
                                {{ strtoupper($m->jenis_materi ?? '—') }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-center whitespace-nowrap" style="color: #666;">
                            <span title="Video">🎬 {{ $m->jumlah_video }}</span>
                            <span title="PDF" class="ml-2">📄 {{ $m->jumlah_pdf }}</span>
                            <span title="Gambar" class="ml-2">🖼️ {{ $m->jumlah_gambar }}</span>
                        </td>

                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('instruktur.soal.index', ['materi' => $m->id]) }}"
                               class="font-bold hover:underline" style="color: #c8562f;">
                                {{ $m->jumlah_soal }}
                            </a>
                        </td>

                        <td class="px-4 py-4 text-center font-bold" style="color: #1a2a3a;">
                            {{ $m->jumlah_peserta }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            @php
                                $terbit = in_array(strtolower($m->status ?? ''), ['published', 'publish', 'aktif']);
                            @endphp
                            <span class="text-xs px-2 py-1 font-bold"
                                  style="background: {{ $terbit ? '#7a8f6b' : '#ccc' }}; color: {{ $terbit ? '#f5f0e1' : '#555' }};">
                                {{ strtoupper($m->status ?? '—') }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                <a href="{{ route('instruktur.materi.show', $m->id) }}"
                                   class="text-xs px-3 py-2 font-bold"
                                   style="background: #f5f0e1; color: #1a2a3a; border: 1px solid #1a2a3a;">
                                    Preview
                                </a>
                                <a href="{{ route('instruktur.materi.edit', $m->id) }}"
                                   class="text-xs px-3 py-2 font-bold"
                                   style="background: #e8a838; color: #1a2a3a; border: 1px solid #1a2a3a;">
                                    Edit
                                </a>
                                <form action="{{ route('instruktur.materi.destroy', $m->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus materi &quot;{{ $m->judul }}&quot;? Semua soal dan lampirannya ikut terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs px-3 py-2 font-bold"
                                            style="background: #c8562f; color: #f5f0e1; border: 1px solid #1a2a3a;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif

@endsection