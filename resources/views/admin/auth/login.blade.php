<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Sembako ISP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300897B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z'/%3E%3Cpath d='M3 6h18'/%3E%3Cpath d='M16 10a4 4 0 0 1-8 0'/%3E%3C/svg%3E">
</head>
<body style="background: linear-gradient(135deg, var(--primary-900) 0%, var(--gray-900) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">

<div style="width: 100%; max-width: 420px;">
    <!-- Logo -->
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary-500), var(--primary-700)); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white;">
            <x-icon name="logo" size="32" />
        </div>
        <h1 style="color: #fff; font-size: 1.6rem; margin-bottom: 4px;">Sembako ISP</h1>
        <p style="color: rgba(255,255,255,.6); font-size: 0.875rem;">Portal Administrator Pengelolaan Pesanan</p>
    </div>

    <!-- Card -->
    <div class="card" style="box-shadow: var(--shadow-xl);">
        <div class="card-body" style="padding: var(--space-xl);">
            <h2 style="margin-bottom: var(--space-xl); font-size: 1.2rem; font-weight: 700; color: var(--gray-900); display: flex; align-items: center; gap: 8px;">
                <x-icon name="shield-check" size="20" />
                <span>Masuk Administrator</span>
            </h2>

            @if($errors->any())
            <div class="alert alert-error mb-lg">
                <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email Admin <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@sembako.test">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password <span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                </div>

                <div class="form-check mb-lg">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember">Ingat sesi saya</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span>Masuk ke Panel Admin</span>
                    <x-icon name="arrow-right" size="16" />
                </button>
            </form>
        </div>
    </div>

    <div style="text-align: center; margin-top: var(--space-lg);">
        <a href="{{ route('home') }}" style="color: rgba(255,255,255,.5); font-size: 0.875rem;">← Kembali ke Halaman Publik</a>
    </div>
</div>

</body>
</html>
