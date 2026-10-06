<!-- Simpan file ini di: resources/views/dashboard.blade.php (TIMPA yang lama) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEL PPKPI - Tingkatkan Kompetensi Industri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-top { background:#faf1e2; padding: 1rem 2.5rem; }
        .navbar-top a.nav-link { color:#1a1f36; font-weight:600; }
        .btn-daftar-nav {
            background:#f0ad33; border:2px solid #1a1f36; color:#1a1f36; font-weight:700;
            border-radius:8px; padding: .5rem 1.25rem;
        }
        .btn-daftar-nav:hover { background:#dd9c22; color:#1a1f36; }
        .hero-section {
            background: linear-gradient(160deg, #1741e6 0%, #2f5bff 60%, #3f5fe0 100%);
            padding: 5rem 2.5rem 4rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hashtag-badge {
            background: rgba(255,255,255,.15);
            border-radius: 30px;
            padding: .4rem 1rem;
            font-size: .85rem;
            font-weight: 600;
            display: inline-block;
        }
        .hero-title { font-size: 3rem; font-weight: 800; line-height: 1.15; }
        .hero-title .highlight { color: #ffd23f; }
        .btn-mulai { background:#fff; color:#1741e6; font-weight:700; border-radius:8px; padding:.75rem 1.5rem; border:none; }
        .btn-mulai:hover { background:#eef1ff; color:#1741e6; }
        .btn-daftar-gratis {
            background: transparent; color:#fff; font-weight:700; border-radius:8px;
            padding:.75rem 1.5rem; border:2px solid #fff;
        }
        .btn-daftar-gratis:hover { background: rgba(255,255,255,.1); color:#fff; }
        .stat-number { font-size: 1.8rem; font-weight: 800; }
        .floating-card {
            background: #fff;
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
            position: relative;
            z-index: 2;
        }
        .floating-card-bg {
            position: absolute;
            top: 30px; right: -20px;
            width: 100%; height: 100%;
            background: #ffd23f;
            border-radius: 18px;
            transform: rotate(6deg);
            z-index: 1;
        }
        .materi-item {
            border-radius: 12px;
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: .5rem;
            background: #f8f9fc;
        }
        .materi-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: #6a5bff; color:#fff; display:flex; align-items:center; justify-content:center;
            font-weight:700; flex-shrink:0;
        }
        .materi-item .lp-badge {
            background: #fff3cd; color:#856404; font-weight:700; font-size:.75rem;
            padding: .25rem .6rem; border-radius: 6px; white-space:nowrap;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-top d-flex justify-content-between align-items-center">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <span class="d-flex align-items-center justify-content-center" style="width:38px;height:38px;background:#f0ad33;border:2px solid #1a1f36;border-radius:8px;font-weight:800;color:#1a1f36;">P</span>
        <span class="fw-bold text-dark">PPKPI</span>
    </a>
    <div class="d-flex gap-4 align-items-center">
        <a href="{{ route('dashboard') }}" class="nav-link">Beranda</a>
        <a href="#materi-list" class="nav-link">Materi</a>
        <a href="#" class="nav-link">Tentang</a>
    </div>
            <div class="d-flex align-items-center gap-3">
        @auth
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link fw-bold" style="color:#c8562f;">Dashboard Admin</a>
            @elseif (auth()->user()->role === 'instruktur')
                <a href="{{ route('instruktur.dashboard') }}" class="nav-link fw-bold" style="color:#c8562f;">Dashboard Instruktur</a>
            @endif

            <a href="{{ route('profile.index') }}" class="nav-link">{{ auth()->user()->nama_lengkap }}</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-daftar-nav btn-sm">Keluar</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Masuk</a>
            <a href="{{ route('register') }}" class="btn-daftar-nav text-decoration-none">Daftar →</a>
        @endauth
    </div>
</nav>

<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="hashtag-badge mb-3">#BelajarKapanpunDimanapun</span>
                <h1 class="hero-title mt-3 mb-4">
                    Tingkatkan Kompetensi Industri Bareng <span class="highlight">PPKPI</span>
                </h1>
                <p class="mb-4" style="font-size:1.05rem; opacity:.9;">
                    Platform pelatihan online untuk pengembangan kompetensi industri.
                    Belajar dari instruktur berpengalaman, dapatkan sertifikat, dan raih karir yang lebih baik.
                </p>
                <div class="d-flex gap-3 mb-5">
                    @auth
                        <a href="{{ route('profile.index') }}" class="btn btn-mulai">Mulai Belajar</a>
                    @else
                        <a href="#materi-list" class="btn btn-mulai">Mulai Belajar</a>
                        <a href="{{ route('register') }}" class="btn btn-daftar-gratis">Daftar Gratis</a>
                    @endauth
                </div>
                <div class="d-flex gap-5">
                    <div>
                        <div class="stat-number">{{ $stats['total_materi'] }}+</div>
                        <small style="opacity:.8;">Materi</small>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['total_kategori'] }}</div>
                        <small style="opacity:.8;">Kategori</small>
                    </div>
                    <div>
                        <div class="stat-number">{{ $stats['total_peserta'] }}+</div>
                        <small style="opacity:.8;">Peserta</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="position-relative" style="max-width:420px; margin: 0 auto;">
                    <div class="floating-card-bg"></div>
                    <div class="floating-card">
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <div style="width:44px;height:44px;background:#eef1ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-book text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">Total Materi</div>
                                <small class="text-muted">Siap dipelajari</small>
                            </div>
                            <div class="fw-bold text-primary fs-4">{{ $stats['total_materi'] }}</div>
                        </div>

                        @foreach ($materi->take(3) as $item)
                            <div class="materi-item">
                                <div class="materi-icon">{{ strtoupper(substr($item->judul, 0, 1)) }}</div>
                                <div class="flex-grow-1" style="min-width:0;">
                                    <div class="fw-semibold text-dark small text-truncate">{{ $item->judul }}</div>
                                    <small class="text-muted">{{ $item->kategori }}</small>
                                </div>
                                <span class="lp-badge">{{ $item->learning_points }} LP</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5" id="materi-list">
    <h4 class="fw-bold mb-4">Semua Materi</h4>

    @if ($materi->isEmpty())
        <div class="alert alert-light border text-center py-5">Belum ada materi tersedia.</div>
    @else
        <div class="row g-4">
            @foreach ($materi as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius:14px;">
                        <div class="card-body">
                            <span class="badge mb-2" style="background:#eef1ff; color:#2f5bff;">{{ $item->kategori }}</span>
                            <h6 class="fw-bold">{{ $item->judul }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($item->deskripsi, 90) }}</p>
                            <div class="d-flex justify-content-between align-items-center small text-muted mb-3">
                                <span><i class="bi bi-clock me-1"></i>{{ $item->duration_minutes }} menit</span>
                                <span class="fw-bold text-primary">{{ $item->learning_points }} LP</span>
                            </div>
                            @auth
                                <a href="#" class="btn btn-daftar-nav btn-sm w-100 text-decoration-none">Mulai Belajar</a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-daftar-nav btn-sm w-100 text-decoration-none">Daftar untuk Belajar</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>