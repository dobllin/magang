{{-- Simpan file ini di: resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3">
    @php
        $cards = [
            ['label' => 'Users', 'key' => 'users', 'icon' => 'bi-people', 'color' => 'primary'],
            ['label' => 'Instruktur', 'key' => 'instruktur', 'icon' => 'bi-person-badge', 'color' => 'success'],
            ['label' => 'Materi', 'key' => 'materi', 'icon' => 'bi-journal-text', 'color' => 'info'],
            ['label' => 'Soal', 'key' => 'soal', 'icon' => 'bi-patch-question', 'color' => 'warning'],
            ['label' => 'Penilaian', 'key' => 'penilaian', 'icon' => 'bi-clipboard-check', 'color' => 'danger'],
            ['label' => 'Progress', 'key' => 'progress', 'icon' => 'bi-graph-up', 'color' => 'secondary'],
            ['label' => 'Video', 'key' => 'video', 'icon' => 'bi-camera-reels', 'color' => 'dark'],
            ['label' => 'PDF', 'key' => 'pdf', 'icon' => 'bi-file-earmark-pdf', 'color' => 'primary'],
            ['label' => 'Gambar', 'key' => 'gambar', 'icon' => 'bi-image', 'color' => 'success'],
        ];
    @endphp

    @foreach ($cards as $card)
        <div class="col-md-4 col-lg-3">
            <div class="card card-stat shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ $card['label'] }}</div>
                        <h3 class="text-{{ $card['color'] }}">{{ $stats[$card['key']] }}</h3>
                    </div>
                    <i class="bi {{ $card['icon'] }} text-{{ $card['color'] }}" style="font-size:2rem;"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection