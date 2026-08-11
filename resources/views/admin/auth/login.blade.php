<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Sembako ISP Portal</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236366f1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/%3E%3C/svg%3E">
</head>
<body class="admin-auth-body">

<div class="admin-auth-card">

    <!-- LEFT: EXECUTIVE VISUAL BRANDING PANEL -->
    <div class="admin-auth-visual">
        <!-- Decorative Glow Rings -->
        <div class="auth-visual-circle" style="width: 260px; height: 260px; top: -60px; left: -60px; background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, rgba(99,102,241,0) 70%);"></div>
        <div class="auth-visual-circle" style="width: 220px; height: 220px; bottom: -40px; right: -40px; background: radial-gradient(circle, rgba(124,58,237,0.2) 0%, rgba(124,58,237,0) 70%);"></div>

        <!-- Top Badge -->
        <div style="position: relative; z-index: 2;">
            <span class="badge" style="background: rgba(99, 102, 241, 0.2); color: #c7d2fe; border: 1px solid rgba(129, 140, 248, 0.35); font-size: 0.75rem; padding: 4px 12px; border-radius: var(--radius-full); font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="shield-check" size="13" />
                <span>Portal Resmi Administrator ISP</span>
            </span>
        </div>

        <!-- Center Shield Illustration & Headline -->
        <div class="auth-visual-center">
            <div class="auth-visual-badge">
                <x-icon name="shield-check" size="44" />
            </div>
            <h2 class="auth-visual-title" style="color: #ffffff; font-size: 1.5rem; letter-spacing: -0.02em;">
                Control Center Administrator
            </h2>
            <p class="auth-visual-subtitle" style="color: #c7d2fe; font-size: 0.85rem;">
                Akses panel kontrol dan otorisasi khusus staf ISP untuk pengelolaan transaksi sembako, mitra drop point, stok paket, dan laporan pendapatan.
            </p>
        </div>

        <!-- Bottom Security Badges -->
        <div class="auth-visual-footer" style="border-top: 1px solid rgba(99, 102, 241, 0.25);">
            <div class="auth-visual-pill" style="color: #c7d2fe;">
                <x-icon name="lock" size="14" />
                <span>256-Bit SSL Enkripsi</span>
            </div>
            <div class="auth-visual-pill" style="color: #c7d2fe;">
                <x-icon name="dashboard" size="14" />
                <span>Real-Time Monitoring</span>
            </div>
        </div>
    </div>

    <!-- RIGHT: EXECUTIVE LOGIN FORM PANEL -->
    <div class="admin-auth-form">
        <div class="auth-form-header">
            <div class="auth-form-logo">
                <x-icon name="shield-check" size="26" />
            </div>
            <h1 class="auth-form-title" style="color: #0f172a; font-size: 1.4rem;">Otorisasi Administrator</h1>
            <p class="auth-form-subtitle">Masukkan email staf dan kata sandi Anda</p>
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
                <label class="form-label" for="email" style="font-weight: 600; font-size: 0.825rem; color: #334155;">
                    Alamat Email Admin <span class="required">*</span>
                </label>
                <input type="email" name="email" id="email" class="form-control admin-auth-input" value="{{ old('email') }}" required autofocus placeholder="admin@sembako.test" style="height: 42px; font-size: 0.875rem;">
            </div>

            <div class="form-group mb-md">
                <label class="form-label" for="password" style="font-weight: 600; font-size: 0.825rem; color: #334155;">
                    Password <span class="required">*</span>
                </label>
                <input type="password" name="password" id="password" class="form-control admin-auth-input" required placeholder="••••••••" style="height: 42px; font-size: 0.875rem;">
            </div>

            <div class="form-check mb-lg">
                <input type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label" for="remember" style="font-size: 0.825rem; color: #64748b;">Ingat sesi admin di browser ini</label>
            </div>

            <button type="submit" class="btn admin-auth-btn" style="width: 100%; height: 44px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 10px;">
                <span>Masuk ke Control Center</span>
                <x-icon name="arrow-right" size="16" />
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
            <a href="{{ route('home') }}" style="color: #64748b; font-size: 0.825rem; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                <span>← Kembali ke Halaman Publik</span>
            </a>
        </div>
    </div>

</div>

</body>
</html>
