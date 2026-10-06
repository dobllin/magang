<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ' PPKPI')</title>

    <!-- Retro Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <!-- NAVBAR -->
    <nav class="bg-cream border-b-2 border-navy sticky top-0 z-50" style="background: #f5f0e1; border-color: #1a2a3a;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 flex items-center justify-center font-serif text-2xl font-bold" style="background: #e8a838; color: #1a2a3a; border: 2px solid #1a2a3a; box-shadow: 3px 3px 0 #1a2a3a;">
                            P
                        </div>
                    </div>
                    <div>
                        <div class="text-xs tracking-widest font-bold" style="color: #c8562f;">PPKPI</div>
                    </div>
                </a>

                <!-- Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="font-semibold hover:underline decoration-2 underline-offset-4" style="color: #1a2a3a;">Beranda</a>
                    <a href="#courses" class="font-semibold hover:underline decoration-2 underline-offset-4" style="color: #1a2a3a;">Materi</a>
                    <a href="#about" class="font-semibold hover:underline decoration-2 underline-offset-4" style="color: #1a2a3a;">Tentang</a>
                </div>

                                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="font-semibold" style="color: #c8562f;">Dashboard Admin</a>
                        @elseif (auth()->user()->role === 'instruktur')
                            <a href="{{ route('instruktur.dashboard') }}" class="font-semibold" style="color: #c8562f;">Dashboard Instruktur</a>
                        @endif

                        <a href="{{ route('profile.index') }}" class="font-semibold" style="color: #1a2a3a;">{{ auth()->user()->nama_lengkap }}</a>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn-retro text-sm py-2 px-4" style="box-shadow: 3px 3px 0 #1a2a3a;">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold" style="color: #1a2a3a;">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-retro text-sm py-2 px-4" style="box-shadow: 3px 3px 0 #1a2a3a;">Daftar →</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="border-t-4" style="background: #1a2a3a; color: #f5f0e1; border-color: #e8a838;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="font-serif text-4xl mb-4" style="color: #e8a838;"> PPKPI</div>
                    <p class="text-sm leading-relaxed max-w-md opacity-80">Sistem Informasi Manajemen Pelatihan dan Evaluasi untuk Pusat Pelatihan Kerja Pengembangan Industri.</p>
                    <div class="mt-6 flex gap-3">
                        <div class="w-10 h-10 border-2 flex items-center justify-center" style="border-color: #e8a838;">📘</div>
                        <div class="w-10 h-10 border-2 flex items-center justify-center" style="border-color: #e8a838;">📷</div>
                        <div class="w-10 h-10 border-2 flex items-center justify-center" style="border-color: #e8a838;">🐦</div>
                    </div>
                </div>
                <div>
                    <h4 class="badge-retro mb-4" style="background: #e8a838; color: #1a2a3a;">Menu</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/" class="hover:text-kunyit" style="opacity: 0.8;">→ Beranda</a></li>
                        <li><a href="#" style="opacity: 0.8;">→ Materi</a></li>
                        <li><a href="#" style="opacity: 0.8;">→ Tentang</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="badge-retro mb-4" style="background: #e8a838; color: #1a2a3a;">Kontak</h4>
                    <ul class="space-y-3 text-sm" style="opacity: 0.8;">
                        <li>info@ppkpi.id</li>
                        <li>+62 21-1234-5678</li>
                        <li>Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t mt-12 pt-8 text-center text-xs tracking-widest" style="border-color: rgba(245, 240, 225, 0.2); opacity: 0.6;">
                © {{ date('Y') }}  PPKPI — MADE WITH ❤️ IN JAKARTA
            </div>
        </div>
    </footer>

</body>
</html>