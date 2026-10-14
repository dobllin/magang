@extends('layouts.app')

@section('title', ucfirst($jenis) . ': ' . $materi->judul)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="color:#1a2a3a;">
    @include('partials.flash')

    <div class="text-xs tracking-widest font-bold mb-2" style="color:#c8562f;">{{ strtoupper($jenis) }}</div>
    <h1 class="font-serif text-4xl mb-2">{{ $materi->judul }}</h1>
    <p class="text-sm mb-8" style="color:#555;">
        @if ($jenis === 'pretes')
            Pretes ini cuma buat mengukur kemampuan awal kamu dan nggak memengaruhi kelulusan. Jawab sebisanya.
        @else
            Jawab semua soal. Nilai minimal {{ config('pelatihan.kkm') }} untuk lulus dan dapat sertifikat.
        @endif
    </p>

    <form method="POST" action="{{ route('kelas.tes.kirim', [$materi, $jenis]) }}" class="space-y-6">
        @csrf

        @foreach ($soal as $i => $s)
            <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
                <label for="jawaban-{{ $s->id }}" class="block mb-3">
                    <span class="text-xs tracking-widest font-bold" style="color:#c8562f;">SOAL {{ $i + 1 }}</span>
                    <span class="block mt-2 whitespace-pre-line">{{ $s->pertanyaan }}</span>
                </label>
                <textarea id="jawaban-{{ $s->id }}" name="jawaban[{{ $s->id }}]" rows="2"
                          style="width:100%; padding:10px 12px; border:2px solid #1a2a3a; background:#fff; color:#1a2a3a;"
                          placeholder="Tulis jawabanmu">{{ old('jawaban.' . $s->id) }}</textarea>
            </div>
        @endforeach

        <button type="submit" class="px-8 py-4 font-bold"
                style="background:#e8a838; color:#1a2a3a; border:2px solid #1a2a3a; box-shadow:4px 4px 0 #1a2a3a;"
                onclick="return confirm('Kirim jawaban sekarang?')">
            Kirim Jawaban
        </button>
    </form>
</div>
@endsection
