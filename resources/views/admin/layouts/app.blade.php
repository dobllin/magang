<!-- Simpan file ini di: resources/views/admin/layouts/app.blade.php (TIMPA yang lama) -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SIMPEL PPKPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background:#f4f6f9; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-top { background:#faf1e2; padding: 1rem 2.5rem; }
        .navbar-top .nav-link { color:#1a1f36; font-weight:600; }
        .navbar-top .nav-link.active { color:#2f5bff; }
        .btn-nav-accent {
            background:#f0ad33; border:2px solid #1a1f36; color:#1a1f36; font-weight:700;
            border-radius:8px; padding: .5rem 1.25rem;
        }
        .btn-nav-accent:hover { background:#dd9c22; color:#1a1f36; }
        .page-header {
            background: linear-gradient(160deg, #1741e6 0%, #2f5bff 60%, #3f5fe0 100%);
            border-radius: 18px;
            padding: 2.5rem;
            color: #fff;
            margin: 1.5rem 2.5rem 0;
        }
        .card-stat { border-radius: 14px; border:none; }
        .card-stat h3 { font-weight: 800; }
        .admin-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: #2f5bff; color:#fff; display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:.95rem; flex-shrink:0;
        }
        .admin-name-block { line-height: 1.15; }
        .admin-name-block .name { font-weight: 700; color:#1a1f36; font-size:.9rem; }
        .admin-name-block .role-label {
            font-size: .72rem; color:#6b7280; text-transform: uppercase; letter-spacing: .03em;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-top d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <span class="d-flex align-items-center justify-content-center" style="width:38px;height:38px;background:#f0ad33;border:2px solid #1a1f36;border-radius:8px;font-weight:800;color:#1a1f36;">P</span>
        <span class="fw-bold text-dark">PPKPI Admin</span>
    </a>
    <div class="d-flex gap-4 align-items-center">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
    </div>
    <div class="d-flex align-items-center gap-3">
        @php
            $namaAdmin = auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'Admin';
            $roleAdmin = auth()->user()->role ?? 'admin';
        @endphp
        <div class="d-flex align-items-center gap-2">
            <div class="admin-avatar">{{ strtoupper(substr($namaAdmin, 0, 1)) }}</div>
            <div class="admin-name-block">
                <div class="name">{{ $namaAdmin }}</div>
                <div class="role-label">{{ $roleAdmin === 'admin' ? 'Super Admin' : ucfirst($roleAdmin) }} · SIMPEL PPKPI</div>
            </div>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn-nav-accent">Keluar</button>
        </form>
    </div>
</nav>

<div class="container-fluid px-0">
    <div class="page-header">
        <h3 class="fw-bold mb-1">@yield('title', 'Dashboard')</h3>
        <p class="mb-0" style="opacity:.85;">Kelola seluruh data SIMPEL PPKPI dari sini.</p>
    </div>

    <div class="p-4 px-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>