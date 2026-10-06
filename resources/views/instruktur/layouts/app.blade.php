<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Instruktur') — SIMPEL PPKPI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="antialiased" style="background: #f5f0e1;">

    @php
        // Gaya menu yang lagi dibuka. Disimpan sekali di sini
        // supaya nggak diketik ulang di tiap link.
        $menuAktif = 'background: rgba(232, 168, 56, 0.15); border-color: #e8a838;';

        $namaUser = Auth::user()->nama_lengkap ?: (Auth::user()->email ?? 'Pengguna');
    @endphp

    <div class="flex min-h-screen">

        <!-- ============================================ -->
        <!-- SIDEBAR                                      -->
        <!-- ============================================ -->
        <aside class="w-64 flex-shrink-0" style="background: #1a2a3a; color: #f5f0e1;">
            <div class="p-6 border-b-2" style="border-color: #e8a838;">
                <a href="{{ route('instruktur.dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-12 h-12 flex items-center justify-center font-serif text-2xl font-bold" style="background: #e8a838; color: #1a2a3a;">
                        P
                    </div>
                    <div>
                        <div class="font-serif text-xl leading-none" style="color: #e8a838;">SIMPEL</div>
                        <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">PPKPI</div>
                    </div>
                </a>
            </div>

            <nav class="py-6">
                <div class="px-6 mb-4">
                    <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">MENU INSTRUKTUR</div>
                </div>

                <a href="{{ route('instruktur.dashboard') }}"
                   class="flex items-center space-x-3 px-6 py-3 hover:bg-white/10 border-l-4 {{ request()->routeIs('instruktur.dashboard') ? '' : 'border-transparent' }}"
                   style="{{ request()->routeIs('instruktur.dashboard') ? $menuAktif : '' }}">
                    <span class="text-xl">📊</span>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <a href="{{ route('instruktur.materi.index') }}"
                   class="flex items-center space-x-3 px-6 py-3 hover:bg-white/10 border-l-4 {{ request()->routeIs('instruktur.materi.*') ? '' : 'border-transparent' }}"
                   style="{{ request()->routeIs('instruktur.materi.*') ? $menuAktif : '' }}">
                    <span class="text-xl">📚</span>
                    <span class="font-semibold">Materi Saya</span>
                </a>

                <a href="{{ route('instruktur.soal.index') }}"
                   class="flex items-center space-x-3 px-6 py-3 hover:bg-white/10 border-l-4 {{ request()->routeIs('instruktur.soal.*') ? '' : 'border-transparent' }}"
                   style="{{ request()->routeIs('instruktur.soal.*') ? $menuAktif : '' }}">
                    <span class="text-xl">📝</span>
                    <span class="font-semibold">Bank Soal</span>
                </a>

                <a href="{{ route('instruktur.peserta.index') }}"
                   class="flex items-center space-x-3 px-6 py-3 hover:bg-white/10 border-l-4 {{ request()->routeIs('instruktur.peserta.*') ? '' : 'border-transparent' }}"
                   style="{{ request()->routeIs('instruktur.peserta.*') ? $menuAktif : '' }}">
                    <span class="text-xl">👥</span>
                    <span class="font-semibold">Peserta</span>
                </a>

                <a href="{{ route('instruktur.sertifikat.index') }}"
                   class="flex items-center space-x-3 px-6 py-3 hover:bg-white/10 border-l-4 {{ request()->routeIs('instruktur.sertifikat.*') ? '' : 'border-transparent' }}"
                   style="{{ request()->routeIs('instruktur.sertifikat.*') ? $menuAktif : '' }}">
                    <span class="text-xl">🏆</span>
                    <span class="font-semibold">Sertifikat</span>
                </a>

                <div class="px-6 mt-8 mb-4">
                    <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">AKUN</div>
                </div>

                <a href="{{ route('profile.index') }}"
                   class="flex items-center space-x-3 px-6 py-3 hover:bg-white/10 border-l-4 {{ request()->routeIs('profile.*') ? '' : 'border-transparent' }}"
                   style="{{ request()->routeIs('profile.*') ? $menuAktif : '' }}">
                    <span class="text-xl">⚙️</span>
                    <span class="font-semibold">Profil</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-6 py-3 hover:bg-red-500/20 border-l-4 border-transparent text-left">
                        <span class="text-xl">🚪</span>
                        <span class="font-semibold">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- ============================================ -->
        <!-- MAIN CONTENT                                 -->
        <!-- ============================================ -->
        <div class="flex-1 flex flex-col">

            <!-- TOP NAVBAR -->
            <header class="border-b-2 py-4 px-8" style="background: #f5f0e1; border-color: #1a2a3a;">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">DASHBOARD</div>
                        <h1 class="font-serif text-2xl" style="color: #1a2a3a;">@yield('page-title', 'Overview')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm font-bold" style="color: #1a2a3a;">{{ $namaUser }}</div>
                            <div class="text-xs" style="color: #c8562f;">{{ Auth::user()->instruktur->jabatan ?? 'Instruktur' }}</div>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center font-serif text-xl font-bold" style="background: #e8a838; color: #1a2a3a; border: 2px solid #1a2a3a; box-shadow: 3px 3px 0 #1a2a3a;">
                            {{ strtoupper(substr($namaUser, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>