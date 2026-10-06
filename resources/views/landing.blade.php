@extends('layouts.app')

@section('title', 'PPKPI - Belajar Kapanpun, Dimanapun')

@section('content')

<!-- HERO SECTION -->
<section class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <div>
                <div class="inline-block bg-blue-500 bg-opacity-30 rounded-full px-4 py-1 mb-4">
                    <span class="text-sm font-semibold">#BelajarKapanpunDimanapun</span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
                    Tingkatkan Kompetensi Industri Bareng <span class="text-yellow-300">PPKPI</span>
                </h1>
                <p class="text-lg text-blue-100 mb-8 leading-relaxed">
                    Platform pelatihan online untuk pengembangan kompetensi industri.
                    Belajar dari instruktur berpengalaman, dapatkan sertifikat, dan raih karir yang lebih baik.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#courses" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                        Mulai Belajar
                    </a>
                    <a href="#" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-700 transition">
                        Daftar Gratis
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mt-12 max-w-md">
                    <div>
                        <div class="text-3xl font-bold">{{ $materis->count() }}+</div>
                        <div class="text-sm text-blue-200">Materi</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $kategoris->count() }}</div>
                        <div class="text-sm text-blue-200">Kategori</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">1000+</div>
                        <div class="text-sm text-blue-200">Peserta</div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-300 rounded-3xl transform rotate-6"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8 text-gray-900">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Total Materi</div>
                                    <div class="text-sm text-gray-500">Siap dipelajari</div>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-blue-600">{{ $materis->count() }}</div>
                        </div>
                        <div class="space-y-3">
                            @foreach($topCourses->take(3) as $course)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                                        {{ substr($course->kategori, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-sm truncate">{{ $course->judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $course->kategori }}</div>
                                    </div>
                                    <div class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">
                                        {{ $course->learning_points }} LP
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KATEGORI SECTION -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Jelajahi Kategori</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Pilih kategori pelatihan sesuai kebutuhan pengembangan kompetensi lu.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($kategoris as $kat)
                <a href="#" class="group bg-gradient-to-br from-blue-50 to-indigo-100 hover:from-blue-100 hover:to-indigo-200 rounded-xl p-6 text-center transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg mx-auto mb-3 flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($kat, 0, 1) }}
                    </div>
                    <div class="font-semibold text-gray-900 text-sm">{{ $kat }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- COURSES SECTION -->
<section id="courses" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Materi Terbaru</h2>
                <p class="text-gray-600">Materi pelatihan dari instruktur berpengalaman.</p>
            </div>
            <a href="#" class="hidden md:block text-blue-600 hover:text-blue-800 font-semibold">Lihat Semua →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($materis as $materi)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition overflow-hidden group">

                    <!-- Thumbnail -->
                    <div class="relative h-48 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-white text-5xl font-black opacity-30">{{ substr($materi->kategori, 0, 3) }}</span>
                        </div>
                        <div class="absolute top-4 left-4 bg-white bg-opacity-90 px-3 py-1 rounded-full text-xs font-semibold text-blue-700 uppercase">
                            {{ $materi->jenis_materi }}
                        </div>
                        <div class="absolute top-4 right-4 bg-yellow-400 px-3 py-1 rounded-full text-xs font-bold text-gray-900">
                            {{ $materi->learning_points }} LP
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold">{{ $materi->kategori }}</span>
                            @if($materi->subkategori)
                                <span class="text-xs text-gray-500">• {{ $materi->subkategori }}</span>
                            @endif
                        </div>

                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition">
                            {{ $materi->judul }}
                        </h3>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ $materi->deskripsi }}
                        </p>

                        <!-- Meta info -->
                        <div class="flex items-center justify-between text-xs text-gray-500 pb-4 border-b border-gray-100">
                            <div class="flex items-center space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $materi->duration_minutes }} menit
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $materi->total_lessons }} lessons
                                </span>
                            </div>
                            <span class="text-xs">{{ $materi->bahasa }}</span>
                        </div>

                        <!-- Instructor -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr($materi->instruktur->user->nama_lengkap, 0, 1) }}
                                </div>
                                <div class="text-xs">
                                    <div class="font-semibold text-gray-900">{{ $materi->instruktur->user->nama_lengkap }}</div>
                                    <div class="text-gray-500">{{ $materi->instruktur->jabatan }}</div>
                                </div>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Meningkatkan Kompetensi Anda?</h2>
        <p class="text-lg text-blue-100 mb-8">Bergabung sekarang dan mulai perjalanan pengembangan karir lu.</p>
        <a href="#" class="inline-block bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-bold hover:bg-yellow-300 transition">
            Daftar Sekarang - Gratis!
        </a>
    </div>
</section>

@endsection