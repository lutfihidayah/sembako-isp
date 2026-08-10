@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="section" style="min-height: 75vh; display: flex; align-items: center;">
    <div class="container-sm" style="max-width: 440px;">
        <div class="card" style="box-shadow: var(--shadow-lg);">
            <div class="card-body" style="padding: var(--space-xl);">
                <div style="text-align: center; margin-bottom: var(--space-xl);">
                    <div style="width: 52px; height: 52px; background: var(--primary-50); color: var(--primary-600); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm);">
                        <x-icon name="shield-check" size="26" />
                    </div>
                    <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--gray-900);">Lupa Password?</h2>
                    <p class="text-muted text-sm">Masukkan email Anda untuk menerima link reset password.</p>
                </div>

                @if(session('status'))
                <div class="alert alert-success mb-md">
                    <span class="alert-icon"><x-icon name="check-circle" size="18" /></span>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error mb-md">
                    <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Email Terdaftar <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div style="text-align: center; margin-top: var(--space-lg);">
                    <a href="{{ route('login') }}" style="font-size: 0.875rem; color: var(--gray-600);">← Kembali ke halaman masuk</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
