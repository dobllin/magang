@extends('instruktur.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Selamat Datang, ' . explode(' ', $user->nama_lengkap)[0] . '!')

@section('content')

<!-- ============================================ -->
<!-- STATS OVERVIEW - 4 CARD                      -->
<!-- ============================================ -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="p-6" style="background: #e8a838; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="text-xs tracking-widest font-bold mb-2" style="color: #1a2a3a;">MATERI SAYA</div>
                <div class="font-serif text-5xl leading-none" style="color: #1a2a3a;">{{ $totalMateri }}</div>
            </div>
            <div class="text-4xl">📚</div>
        </div>
        <div class="text-xs font-semibold" style="color: #1a2a3a;">
            {{ $totalMateri > 0 ? 'Total materi aktif' : 'Belum ada materi' }}
        </div>
    </div>

    <div class="p-6" style="background: #c8562f; color: #f5f0e1; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="text-xs tracking-widest font-bold mb-2">TOTAL PESERTA</div>
                <div class="font-serif text-5xl leading-none">{{ $totalPeserta }}</div>
            </div>
            <div class="text-4xl">👥</div>
        </div>
        <div class="text-xs font-semibold opacity-90">Peserta belajar materi Anda</div>
    </div>

    <div class="p-6" style="background: #7a8f6b; color: #f5f0e1; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="text-xs tracking-widest font-bold mb-2">LULUS SERTIFIKAT</div>
                <div class="font-serif text-5xl leading-none">{{ $totalLulus }}</div>
            </div>
            <div class="text-4xl">🏆</div>
        </div>
        <div class="text-xs font-semibold opacity-90">
            {{ $totalPeserta > 0 ? round(($totalLulus / $totalPeserta) * 100, 1) . '% completion rate' : 'Belum ada data' }}
        </div>
    </div>

    <div class="p-6" style="background: #1a2a3a; color: #e8a838; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="text-xs tracking-widest font-bold mb-2">RATA NILAI</div>
                <div class="font-serif text-5xl leading-none">{{ $rataNilai }}</div>
            </div>
            <div class="text-4xl">⭐</div>
        </div>
        <div class="text-xs font-semibold" style="color: #f5f0e1;">Rata-rata nilai peserta</div>
    </div>

</div>

<!-- ============================================ -->
<!-- ROW 2: CHART + AKTIVITAS TERBARU             -->
<!-- ============================================ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <div class="lg:col-span-2 p-6" style="background: white; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">DISTRIBUSI</div>
                <h3 class="font-serif text-2xl" style="color: #1a2a3a;">Progress Peserta</h3>
            </div>
            <div class="text-xs px-3 py-1 font-bold" style="background: #e8a838; color: #1a2a3a;">ALL MATERI</div>
        </div>
        <div style="height: 280px;">
            <canvas id="chartProgress"></canvas>
        </div>
    </div>

    <div class="p-6" style="background: white; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
        <div class="mb-4">
            <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">TIMELINE</div>
            <h3 class="font-serif text-2xl" style="color: #1a2a3a;">Aktivitas Terbaru</h3>
        </div>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @forelse($aktivitasTerbaru as $activity)
                @php
                    $badgeColor = match($activity->progres) {
                        'pretes' => '#e8a838',
                        'view' => '#7a8f6b',
                        'postes' => '#c8562f',
                        'sertif' => '#1a2a3a',
                        default => '#e8a838'
                    };
                    $badgeText = match($activity->progres) {
                        'pretes' => '#1a2a3a',
                        'view' => '#f5f0e1',
                        'postes' => '#f5f0e1',
                        'sertif' => '#e8a838',
                        default => '#1a2a3a'
                    };
                @endphp
                <div class="flex items-start space-x-3 pb-3 border-b border-dashed" style="border-color: #1a2a3a;">
                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center font-serif text-sm font-bold" style="background: #e8a838; color: #1a2a3a; border: 2px solid #1a2a3a;">
                        {{ substr($activity->nama_lengkap, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-sm truncate" style="color: #1a2a3a;">{{ $activity->nama_lengkap }}</div>
                        <div class="text-xs truncate" style="color: #666;">{{ $activity->judul_materi }}</div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs px-2 py-0.5 font-bold" style="background: {{ $badgeColor }}; color: {{ $badgeText }};">
                                {{ strtoupper($activity->progres) }}
                            </span>
                            <span class="text-xs" style="color: #999;">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8" style="color: #999;">
                    <div class="text-4xl mb-2">📭</div>
                    <div class="text-sm">Belum ada aktivitas</div>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- ============================================ -->
<!-- ROW 3: LIST MATERI                           -->
<!-- ============================================ -->
<div class="p-6 mb-8" style="background: white; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">MANAJEMEN</div>
            <h3 class="font-serif text-2xl" style="color: #1a2a3a;">Materi Saya</h3>
        </div>
        <a href="{{ route('instruktur.materi.create') }}" class="inline-block font-bold px-4 py-2" style="background: #e8a838; color: #1a2a3a; border: 2px solid #1a2a3a; box-shadow: 3px 3px 0 #1a2a3a;">+ Tambah Materi</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2" style="border-color: #1a2a3a;">
                    <th class="text-left py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">MATERI</th>
                    <th class="text-center py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">JENIS</th>
                    <th class="text-center py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">SOAL</th>
                    <th class="text-center py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">PESERTA</th>
                    <th class="text-center py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">LULUS</th>
                    <th class="text-center py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">STATUS</th>
                    <th class="text-center py-3 px-2 text-xs tracking-widest font-bold" style="color: #c8562f;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materis as $materi)
                    <tr class="border-b border-dashed hover:bg-yellow-50 transition" style="border-color: #ccc;">
                        <td class="py-4 px-2">
                            <div class="font-bold text-sm" style="color: #1a2a3a;">{{ $materi->judul }}</div>
                            <div class="text-xs" style="color: #666;">{{ $materi->kategori }} · {{ $materi->duration_minutes }} min</div>
                        </td>
                        <td class="py-4 px-2 text-center">
                            <span class="text-xs px-2 py-1 font-bold" style="background: #7a8f6b; color: #f5f0e1;">{{ strtoupper($materi->jenis_materi) }}</span>
                        </td>
                        <td class="py-4 px-2 text-center font-bold" style="color: #1a2a3a;">{{ $materi->jumlah_soal }}</td>
                        <td class="py-4 px-2 text-center font-bold" style="color: #1a2a3a;">{{ $materi->jumlah_peserta ?? 0 }}</td>
                        <td class="py-4 px-2 text-center font-bold" style="color: #7a8f6b;">{{ $materi->jumlah_lulus ?? 0 }}</td>
                        <td class="py-4 px-2 text-center">
                            @if($materi->status === 'published')
                                <span class="text-xs px-2 py-1 font-bold" style="background: #7a8f6b; color: #f5f0e1;">PUBLISHED</span>
                            @else
                                <span class="text-xs px-2 py-1 font-bold" style="background: #999; color: white;">DRAFT</span>
                            @endif
                        </td>
                        <td class="py-4 px-2 text-center">
                            <a href="{{ route('instruktur.materi.edit', $materi->id) }}" class="text-xs font-bold" style="color: #c8562f;">EDIT →</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center" style="color: #999;">
                            <div class="text-4xl mb-2">📚</div>
                            <div class="text-sm">Belum ada materi. Klik "Tambah Materi" untuk mulai.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- ROW 4: TOP 5 MATERI POPULER                  -->
<!-- ============================================ -->
<div class="p-6" style="background: #1a2a3a; color: #f5f0e1; border: 2px solid #1a2a3a; box-shadow: 6px 6px 0 #1a2a3a;">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="text-xs tracking-widest font-bold" style="color: #e8a838;">TOP CHARTS</div>
            <h3 class="font-serif text-2xl" style="color: #f5f0e1;">Materi Paling Populer</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @forelse($materiPopuler as $index => $materi)
            <div class="p-4 border-2" style="background: #2a3f55; border-color: #e8a838;">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-serif text-3xl" style="color: #e8a838;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-xs px-2 py-1 font-bold" style="background: #e8a838; color: #1a2a3a;">{{ $materi->learning_points }} LP</div>
                </div>
                <div class="font-bold text-sm mb-2 line-clamp-2" style="min-height: 2.5rem;">{{ $materi->judul }}</div>
                <div class="text-xs opacity-70 mb-3">{{ $materi->kategori }}</div>
                <div class="flex items-center justify-between text-xs">
                    <span>👥 {{ $materi->jumlah_peserta }}</span>
                    <span>🏆 {{ $materi->jumlah_lulus }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-5 text-center py-8 opacity-70">
                <div class="text-4xl mb-2">📊</div>
                <div class="text-sm">Belum ada data materi populer</div>
            </div>
        @endforelse
    </div>
</div>

<!-- ============================================ -->
<!-- CHART.JS SCRIPT                              -->
<!-- ============================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartProgress');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pretes', 'View Materi', 'Postes', 'Sertifikat'],
            datasets: [{
                label: 'Jumlah Peserta',
                data: [
                    {{ $distribusiProgress['pretes'] }},
                    {{ $distribusiProgress['view'] }},
                    {{ $distribusiProgress['postes'] }},
                    {{ $distribusiProgress['sertif'] }}
                ],
                backgroundColor: ['#e8a838', '#7a8f6b', '#c8562f', '#1a2a3a'],
                borderColor: '#1a2a3a',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a2a3a',
                    titleColor: '#e8a838',
                    bodyColor: '#f5f0e1',
                    borderColor: '#e8a838',
                    borderWidth: 2,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#1a2a3a',
                        font: { weight: 'bold' }
                    },
                    grid: { color: 'rgba(26, 42, 58, 0.1)' }
                },
                x: {
                    ticks: {
                        color: '#1a2a3a',
                        font: { weight: 'bold' }
                    },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>

@endsection