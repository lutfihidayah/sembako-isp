@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="section" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container-sm" style="max-width: 520px;">
        <div class="card" style="box-shadow: var(--shadow-lg);">
            <div class="card-body" style="padding: var(--space-xl);">
                <div style="text-align: center; margin-bottom: var(--space-xl);">
                    <div style="width: 52px; height: 52px; background: linear-gradient(135deg, var(--primary-500), var(--primary-700)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm); color: white;">
                        <x-icon name="logo" size="26" />
                    </div>
                    <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--gray-900);">Daftar Akun Baru</h2>
                    <p class="text-muted text-sm">Khusus pelanggan jaringan reseller ISP</p>
                </div>

                @if($errors->any())
                <div class="alert alert-error mb-md">
                    <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required placeholder="nama@email.com">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="081234567890">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Alamat Lengkap</label>
                        <textarea name="address" id="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" rows="2" placeholder="Jl. Mawar No. 12, RT 01/02">{{ old('address') }}</textarea>
                        @error('address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="drop_point_id">Pilih Drop Point Pengambilan Terdekat</label>
                        <select name="drop_point_id" id="drop_point_id" class="form-control form-select">
                            <option value="">-- Pilih Drop Point (Bisa diatur nanti) --</option>
                            @foreach($dropPoints as $dp)
                            <option value="{{ $dp->id }}" {{ old('drop_point_id') == $dp->id ? 'selected' : '' }}>
                                {{ $dp->name }} ({{ $dp->region }})
                            </option>
                            @endforeach
                        </select>
                        @error('drop_point_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label" for="password">Password <span class="required">*</span></label>
                            <input type="password" name="password" id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required placeholder="Minimal 8 karakter">
                            @error('password') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: var(--space-sm);">
                        Daftar Akun
                    </button>
                </form>

                <div style="text-align: center; margin-top: var(--space-lg); padding-top: var(--space-md); border-top: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-500);">
                    Sudah punya akun? <a href="{{ route('login') }}" style="font-weight: 600; color: var(--primary-600);">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
