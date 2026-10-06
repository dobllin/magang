<!-- Simpan file ini di: resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMPEL PPKPI</title>
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
            width: 400px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,.25);
        }
        .btn-masuk {
            background: #f0ad33;
            border: 2px solid #1a1f36;
            color: #1a1f36;
            font-weight: 700;
        }
        .btn-masuk:hover {
            background: #dd9c22;
            color: #1a1f36;
        }
        a.link-daftar { color: #2f5bff; font-weight: 600; text-decoration: none; }
        a.link-daftar:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div style="width:400px;">
        <a href="{{ route('dashboard') }}" class="d-inline-flex align-items-center gap-1 text-white text-decoration-none mb-3 small">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="card auth-card p-4 bg-white">
        <div class="text-center mb-4">
            <h4 class="mb-0 fw-bold">Masuk</h4>
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

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn btn-masuk w-100 py-2">Masuk</button>
        </form>

        <p class="text-center small mt-3 mb-0">
            Belum punya akun? <a href="{{ route('register') }}" class="link-daftar">Daftar</a>
        </p>
        </div>
    </div>
</body>
</html>