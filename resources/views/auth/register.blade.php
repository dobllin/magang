<!-- Simpan file ini di: resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SIMPEL PPKPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(160deg, #1a3fd6, #2f5bff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, sans-serif;
        }
        .auth-card {
            width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,.25);
        }
        .btn-daftar {
            background: #f0ad33;
            border: 2px solid #1a1f36;
            color: #1a1f36;
            font-weight: 700;
        }
        .btn-daftar:hover {
            background: #dd9c22;
            color: #1a1f36;
        }
        a.link-masuk { color: #2f5bff; font-weight: 600; text-decoration: none; }
        a.link-masuk:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div style="width:420px;">
        <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center gap-1 text-white text-decoration-none mb-3 small">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="card auth-card p-4 bg-white">
        <div class="text-center mb-4">
            <h4 class="mb-0 fw-bold">Daftar Akun</h4>
            <small class="text-muted">SIMPEL PPKPI</small>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="jk_p">Perempuan</label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-daftar w-100 py-2">Daftar →</button>
        </form>

        <p class="text-center small mt-3 mb-0">
            Sudah punya akun? <a href="{{ route('login') }}" class="link-masuk">Masuk</a>
        </p>
        </div>
    </div>
</body>
</html>