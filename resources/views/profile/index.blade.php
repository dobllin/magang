<!-- Simpan file ini di: resources/views/profile/index.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya - SIMPEL PPKPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background:#f4f6f9; font-family: system-ui, sans-serif; }
        .navbar-user { background:#fff3e0; border-bottom: 1px solid #eadfc7; }
        .btn-daftar { background:#f0ad33; border:2px solid #1a1f36; color:#1a1f36; font-weight:700; }
        .btn-daftar:hover { background:#dd9c22; color:#1a1f36; }
        .profile-avatar {
            width: 72px; height: 72px; border-radius: 50%;
            background: #2f5bff; color:#fff; display:flex; align-items:center; justify-content:center;
            font-size: 1.6rem; font-weight:700;
        }
        .card { border:none; border-radius: 14px; }
    </style>
</head>
<body>

<nav class="navbar navbar-user px-4 py-3 d-flex justify-content-between">
    <a href="{{ route('dashboard') }}" class="fw-bold text-dark text-decoration-none">
        <span class="badge bg-warning text-dark me-1"><i class="bi bi-p-circle"></i> P</span> PPKPI
    </a>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ $user->nama_lengkap }}</span>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-dark btn-sm">Keluar</button>
        </form>
    </div>
</nav>

<div class="container py-5">
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

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="profile-avatar">{{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}</div>
        <div>
            <h4 class="mb-0">{{ $user->nama_lengkap }}</h4>
            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
            <span class="badge bg-{{ $user->status == 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($user->status ?? 'aktif') }}</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Edit Profil</h5>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" value="{{ old('nis', $user->nis) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="jk_l">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="jk_p">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-daftar mt-4 px-4">Simpan Perubahan</button>
                </form>
            </div>

            <div class="card shadow-sm p-4 mt-4">
                <h5 class="mb-3">Ganti Password</h5>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark mt-4 px-4">Ganti Password</button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm p-4">
                <h6 class="text-muted mb-3">Ringkasan Akun</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>{{ $user->email }}</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $user->no_hp }}</li>
                    <li class="mb-2"><i class="bi bi-card-text me-2"></i>NIS: {{ $user->nis }}</li>
                    <li class="mb-2"><i class="bi bi-calendar me-2"></i>Bergabung sejak {{ $user->created_at?->format('d M Y') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>