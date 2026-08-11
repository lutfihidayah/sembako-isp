@extends('layouts.app')

@section('title', 'Masuk Akun')

@section('content')
<div class="auth-split-section">
    <div class="auth-split-card">

        <!-- LEFT: VISUAL BRANDING PANEL -->
        <div class="auth-split-visual">
            <!-- Decorative Glow Rings -->
            <div class="auth-visual-circle" style="width: 260px; height: 260px; top: -60px; left: -60px;"></div>
            <div class="auth-visual-circle" style="width: 200px; height: 200px; bottom: -40px; right: -40px;"></div>

            <!-- Top Small Tag -->
            <div style="position: relative; z-index: 2;">
                <span class="badge" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.75rem; padding: 4px 10px; border-radius: var(--radius-full);">
                    Jaringan Reseller ISP
                </span>
            </div>

            <!-- Center Illustration Badge & Headline -->
            <div class="auth-visual-center">
                <div class="auth-visual-badge">
                    <x-icon name="cart" size="40" />
                </div>
                <h2 class="auth-visual-title">Belanja Sembako Hemat & Praktis</h2>
                <p class="auth-visual-subtitle">
                    Pesan paket sembako kebutuhan rumah tangga secara online, bayar mudah, dan ambil di Drop Point terdekat tanpa ongkir.
                </p>
            </div>

            <!-- Bottom Feature Pills -->
            <div class="auth-visual-footer">
                <div class="auth-visual-pill">
                    <x-icon name="check-circle" size="14" />
                    <span>100% Bebas Ongkir</span>
                </div>
                <div class="auth-visual-pill">
                    <x-icon name="shield-check" size="14" />
                    <span>Transaksi Terpercaya</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: LOGIN FORM PANEL -->
        <div class="auth-split-form">
            <div class="auth-form-header">
                <div class="auth-form-logo">
                    <x-icon name="logo" size="24" />
                </div>
                <h1 class="auth-form-title">Selamat Datang Kembali</h1>
                <p class="auth-form-subtitle">Masuk ke akun pelanggan sembako Anda</p>
            </div>

            @if(session('status'))
            <div class="alert alert-info mb-md">
                <span class="alert-icon"><x-icon name="info" size="18" /></span>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error mb-md">
                <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group mb-md">
                    <label class="form-label" for="email">Alamat Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" style="height: 42px;">
                </div>

                <div class="form-group mb-md">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Password <span class="required">*</span></label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size: 0.75rem; color: var(--primary-700); font-weight: 600;">Lupa password?</a>
                        @endif
                    </div>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••" style="height: 42px;">
                </div>

                <div class="form-check mb-lg">
                    <input type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size: 0.825rem; color: var(--gray-600);">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 44px; font-weight: 700; box-shadow: 0 4px 14px rgba(0, 135, 61, 0.25);">
                    Masuk Sekarang
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--gray-100); font-size: 0.85rem; color: var(--gray-600);">
                Belum terdaftar? <a href="{{ route('register') }}" style="font-weight: 700; color: var(--primary-700); text-decoration: none;">Daftar Akun Baru</a>
            </div>
        </div>

    </div>
</div>
@endsection
