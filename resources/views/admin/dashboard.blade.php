{{-- Simpan file ini di: resources/views/admin/dashboard.blade.php (TIMPA yang lama) --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3">
    @php
        $cards = [
            ['label' => 'Users', 'key' => 'users', 'icon' => 'bi-people', 'color' => 'primary', 'route' => 'admin.users.index'],
            ['label' => 'Instruktur', 'key' => 'instruktur', 'icon' => 'bi-person-badge', 'color' => 'success', 'route' => 'admin.instruktur.index'],
            ['label' => 'Materi', 'key' => 'materi', 'icon' => 'bi-journal-text', 'color' => 'info', 'route' => 'admin.materi.index'],
            ['label' => 'Soal', 'key' => 'soal', 'icon' => 'bi-patch-question', 'color' => 'warning', 'route' => null],
            ['label' => 'Penilaian', 'key' => 'penilaian', 'icon' => 'bi-clipboard-check', 'color' => 'danger', 'route' => null],
            ['label' => 'Progress', 'key' => 'progress', 'icon' => 'bi-graph-up', 'color' => 'secondary', 'route' => null],
            ['label' => 'Video', 'key' => 'video', 'icon' => 'bi-camera-reels', 'color' => 'dark', 'route' => null],
            ['label' => 'PDF', 'key' => 'pdf', 'icon' => 'bi-file-earmark-pdf', 'color' => 'primary', 'route' => null],
            ['label' => 'Gambar', 'key' => 'gambar', 'icon' => 'bi-image', 'color' => 'success', 'route' => null],
        ];
    @endphp

    @foreach ($cards as $card)
        <div class="col-md-4 col-lg-3">
            @if ($card['route'])
                <a href="{{ route($card['route']) }}" class="text-decoration-none">
            @endif
            <div class="card card-stat shadow-sm {{ $card['route'] ? 'hover-card' : '' }}">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ $card['label'] }}</div>
                        <h3 class="text-{{ $card['color'] }}">{{ $stats[$card['key']] }}</h3>
                    </div>
                    <i class="bi {{ $card['icon'] }} text-{{ $card['color'] }}" style="font-size:2rem;"></i>
                </div>
            </div>
            @if ($card['route'])
                </a>
            @endif
        </div>
    @endforeach
</div>

<style>
.hover-card { transition: .15s; cursor: pointer; }
.hover-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.1) !important; }
</style>
@endsection