@extends('layouts.app')

@section('title', 'Masuk Akun')

@section('content')
<div class="section" style="min-height: 75vh; display: flex; align-items: center;">
    <div class="container-sm" style="max-width: 440px;">
        <div class="card" style="box-shadow: var(--shadow-lg);">
            <div class="card-body" style="padding: var(--space-xl);">
                <div style="text-align: center; margin-bottom: var(--space-xl);">
                    <div style="width: 52px; height: 52px; background: linear-gradient(135deg, var(--primary-500), var(--primary-700)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm); color: white;">
                        <x-icon name="logo" size="26" />
                    </div>
                    <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--gray-900);">Masuk ke Akun</h2>
                    <p class="text-muted text-sm">Gunakan email dan password terdaftar Anda</p>
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

                    <div class="form-group">
                        <label class="form-label" for="email">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label class="form-label" for="password" style="margin-bottom: 0;">Password <span class="required">*</span></label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size: 0.75rem; color: var(--primary-600);">Lupa password?</a>
                            @endif
                        </div>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                    </div>

                    <div class="form-check mb-lg">
                        <input type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya di perangkat ini</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Masuk Sekarang
                    </button>
                </form>

                <div style="text-align: center; margin-top: var(--space-lg); padding-top: var(--space-md); border-top: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-500);">
                    Belum punya akun? <a href="{{ route('register') }}" style="font-weight: 600; color: var(--primary-600);">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
