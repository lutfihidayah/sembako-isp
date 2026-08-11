<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Sembako ISP</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300873d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z'/%3E%3Cpath d='M3 6h18'/%3E%3Cpath d='M16 10a4 4 0 0 1-8 0'/%3E%3C/svg%3E">
</head>
<body style="background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg);">

<div class="auth-split-card" style="max-width: 880px;">

    <!-- LEFT: VISUAL BRANDING PANEL -->
    <div class="auth-split-visual" style="background: linear-gradient(145deg, #043d1c 0%, #064e24 50%, #00873d 100%);">
        <div class="auth-visual-circle" style="width: 260px; height: 260px; top: -60px; left: -60px;"></div>
        <div class="auth-visual-circle" style="width: 200px; height: 200px; bottom: -40px; right: -40px;"></div>

        <div style="position: relative; z-index: 2;">
            <span class="badge" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.75rem; padding: 4px 10px; border-radius: var(--radius-full);">
                Portal Administrator
            </span>
        </div>

        <div class="auth-visual-center">
            <div class="auth-visual-badge">
                <x-icon name="shield-check" size="40" />
            </div>
            <h2 class="auth-visual-title">Manajemen Operasional & Penjualan</h2>
            <p class="auth-visual-subtitle">
                Akses panel kontrol untuk mengelola transaksi pesanan, drop point reseller, stok paket, dan laporan pendapatan.
            </p>
        </div>

        <div class="auth-visual-footer">
            <div class="auth-visual-pill">
                <x-icon name="lock" size="14" />
                <span>Sesi Terenkripsi</span>
            </div>
            <div class="auth-visual-pill">
                <x-icon name="dashboard" size="14" />
                <span>Sistem Real-Time</span>
            </div>
        </div>
    </div>

    <!-- RIGHT: ADMIN LOGIN FORM -->
    <div class="auth-split-form">
        <div class="auth-form-header">
            <div class="auth-form-logo">
                <x-icon name="logo" size="24" />
            </div>
            <h1 class="auth-form-title">Login Administrator</h1>
            <p class="auth-form-subtitle">Masukkan kredensial akun staf ISP</p>
        </div>

        @if($errors->any())
        <div class="alert alert-error mb-md">
            <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group mb-md">
                <label class="form-label" for="email">Email Administrator <span class="required">*</span></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@sembako.test" style="height: 42px;">
            </div>

            <div class="form-group mb-md">
                <label class="form-label" for="password">Password <span class="required">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••" style="height: 42px;">
            </div>

            <div class="form-check mb-lg">
                <input type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label" for="remember" style="font-size: 0.825rem; color: var(--gray-600);">Ingat sesi admin</label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 44px; font-weight: 700; box-shadow: 0 4px 14px rgba(0, 135, 61, 0.25); display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span>Masuk ke Panel Admin</span>
                <x-icon name="arrow-right" size="16" />
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--gray-100);">
            <a href="{{ route('home') }}" style="color: var(--gray-500); font-size: 0.825rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <span>← Kembali ke Halaman Publik</span>
            </a>
        </div>
    </div>

</div>

</body>
</html>
