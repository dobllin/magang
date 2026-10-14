{{-- Video, dokumen, dan gambar sebuah materi. Dipakai di preview instruktur dan halaman belajar peserta. --}}
@php use App\Support\Tautan; @endphp

<div class="space-y-6">
    {{-- VIDEO --}}
    @forelse ($materi->videos as $i => $v)
        @php $embed = Tautan::youtubeEmbed($v->video); @endphp
        <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
            <div class="text-xs tracking-widest font-bold mb-4" style="color:#c8562f;">
                VIDEO {{ $i + 1 }}
            </div>

            @if ($embed)
                <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                    <iframe src="{{ $embed }}"
                            style="position:absolute; top:0; left:0; width:100%; height:100%; border:2px solid #1a2a3a;"
                            allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            title="Video {{ $i + 1 }}"></iframe>
                </div>
            @else
                <div class="p-4 text-sm" style="background:#ffe9e2; border:1px solid #c8562f; color:#c8562f;">
                    Link video ini nggak bisa dibaca sebagai YouTube:
                    <span class="font-mono break-all">{{ $v->video }}</span>
                </div>
            @endif
        </div>
    @empty
    @endforelse

    {{-- PDF --}}
    @foreach ($materi->pdfs as $i => $p)
        @php $preview = Tautan::drivePreview($p->pdf_files); @endphp
        <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
            <div class="flex items-center justify-between mb-4">
                <div class="text-xs tracking-widest font-bold" style="color:#c8562f;">
                    DOKUMEN {{ $i + 1 }}
                </div>
                @if ($preview)
                    <a href="{{ Tautan::driveBuka($p->pdf_files) }}" target="_blank" rel="noopener"
                       class="text-xs px-3 py-2 font-bold"
                       style="background:#f5f0e1; color:#1a2a3a; border:1px solid #1a2a3a;">
                        Buka di Drive ↗
                    </a>
                @endif
            </div>

            @if ($preview)
                <iframe src="{{ $preview }}"
                        style="width:100%; height:600px; border:2px solid #1a2a3a;"
                        title="Dokumen {{ $i + 1 }}"></iframe>
            @else
                <div class="p-4 text-sm" style="background:#ffe9e2; border:1px solid #c8562f; color:#c8562f;">
                    Link ini nggak bisa dibaca sebagai Google Drive:
                    <span class="font-mono break-all">{{ $p->pdf_files }}</span>
                </div>
            @endif
        </div>
    @endforeach

    {{-- GAMBAR --}}
    @if ($materi->gambars->isNotEmpty())
        <div class="p-6" style="background:#fff; border:2px solid #1a2a3a; box-shadow:6px 6px 0 #1a2a3a;">
            <div class="text-xs tracking-widest font-bold mb-4" style="color:#c8562f;">GAMBAR PENDUKUNG</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($materi->gambars as $g)
                    @php $src = Tautan::driveGambar($g->picture); @endphp
                    @if ($src)
                        <a href="{{ Tautan::driveBuka($g->picture) }}" target="_blank" rel="noopener">
                            <img src="{{ $src }}" alt="Gambar pendukung materi {{ $materi->judul }}"
                                 loading="lazy"
                                 style="width:100%; border:2px solid #1a2a3a;">
                        </a>
                    @else
                        <div class="p-4 text-xs" style="background:#ffe9e2; border:1px solid #c8562f; color:#c8562f;">
                            Link gambar nggak terbaca: <span class="font-mono break-all">{{ $g->picture }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
