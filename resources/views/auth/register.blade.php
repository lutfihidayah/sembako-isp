@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="auth-split-section">
    <div class="auth-split-card" style="max-width: 1020px;">

        <!-- LEFT: VISUAL BRANDING PANEL -->
        <div class="auth-split-visual">
            <!-- Decorative Glow Rings -->
            <div class="auth-visual-circle" style="width: 280px; height: 280px; top: -60px; left: -60px;"></div>
            <div class="auth-visual-circle" style="width: 220px; height: 220px; bottom: -40px; right: -40px;"></div>

            <!-- Top Small Tag -->
            <div style="position: relative; z-index: 2;">
                <span class="badge" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.75rem; padding: 4px 10px; border-radius: var(--radius-full);">
                    Registrasi Pelanggan
                </span>
            </div>

            <!-- Center Illustration Badge & Headline -->
            <div class="auth-visual-center">
                <div class="auth-visual-badge">
                    <x-icon name="users" size="40" />
                </div>
                <h2 class="auth-visual-title">Bergabung Bersama Jaringan ISP</h2>
                <p class="auth-visual-subtitle">
                    Dapatkan akses eksklusif pembelian paket sembako berkualitas dengan harga lebih hemat dan bebas biaya kirim ke Drop Point terdekat.
                </p>
            </div>

            <!-- Bottom Feature Pills -->
            <div class="auth-visual-footer">
                <div class="auth-visual-pill">
                    <x-icon name="map-pin" size="14" />
                    <span>Titik Drop Point Tersebar</span>
                </div>
                <div class="auth-visual-pill">
                    <x-icon name="check-circle" size="14" />
                    <span>Verifikasi Cepat</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: REGISTER FORM PANEL -->
        <div class="auth-split-form" style="padding: 36px 40px;">
            <div class="auth-form-header" style="margin-bottom: 20px;">
                <div class="auth-form-logo">
                    <x-icon name="logo" size="24" />
                </div>
                <h1 class="auth-form-title">Buat Akun Baru</h1>
                <p class="auth-form-subtitle">Lengkapi formulir untuk mulai berbelanja sembako</p>
            </div>

            @if($errors->any())
            <div class="alert alert-error mb-md">
                <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="grid grid-2" style="gap: 12px; margin-bottom: 12px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="name" style="font-size: 0.8rem;">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso" style="height: 38px; font-size: 0.85rem;">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="email" style="font-size: 0.8rem;">Alamat Email <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required placeholder="nama@email.com" style="height: 38px; font-size: 0.85rem;">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-2" style="gap: 12px; margin-bottom: 12px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="phone" style="font-size: 0.8rem;">No. WhatsApp / HP</label>
                        <input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="081234567890" style="height: 38px; font-size: 0.85rem;">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="drop_point_id" style="font-size: 0.8rem;">Drop Point Utama</label>
                        <select name="drop_point_id" id="drop_point_id" class="form-control form-select" style="height: 38px; font-size: 0.85rem;">
                            <option value="">-- Pilih Lokasi Ambil --</option>
                            @foreach($dropPoints as $dp)
                            <option value="{{ $dp->id }}" {{ old('drop_point_id') == $dp->id ? 'selected' : '' }}>
                                {{ $dp->name }} ({{ $dp->region }})
                            </option>
                            @endforeach
                        </select>
                        @error('drop_point_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group mb-sm">
                    <label class="form-label" for="address" style="font-size: 0.8rem;">Alamat Tempat Tinggal</label>
                    <input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" placeholder="Jl. Mawar No. 12, RT 01/02" style="height: 38px; font-size: 0.85rem;">
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-2" style="gap: 12px; margin-bottom: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="password" style="font-size: 0.8rem;">Password <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required placeholder="Min. 8 karakter" style="height: 38px; font-size: 0.85rem;">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="password_confirmation" style="font-size: 0.8rem;">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Ulangi password" style="height: 38px; font-size: 0.85rem;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 44px; font-weight: 700; box-shadow: 0 4px 14px rgba(0, 135, 61, 0.25);">
                    Daftar Akun Baru
                </button>
            </form>

            <div style="text-align: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--gray-100); font-size: 0.85rem; color: var(--gray-600);">
                Sudah punya akun? <a href="{{ route('login') }}" style="font-weight: 700; color: var(--primary-700); text-decoration: none;">Masuk di sini</a>
            </div>
        </div>

    </div>
</div>
@endsection
