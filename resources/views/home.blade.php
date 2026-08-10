@extends('layouts.app')

@section('title', 'Katalog Paket Sembako')

@section('content')

@guest
<!-- HERO SECTION (GUEST) -->
<section class="page-hero">
    <div class="container">
        <h1>Paket Sembako Reseller ISP</h1>
        <p class="hero-subtitle">
            Pesan online, bayar mudah, ambil langsung di Drop Point terdekat di wilayah Anda tanpa ongkos kirim.
        </p>

        <div class="hero-trust-list">
            <div class="hero-trust-item">
                <x-icon name="check-circle" size="15" />
                <span>Kualitas Terjamin</span>
            </div>
            <div class="hero-trust-item">
                <x-icon name="map-pin" size="15" />
                <span>{{ $dropPoints->count() }}+ Drop Point Tersebar</span>
            </div>
            <div class="hero-trust-item">
                <x-icon name="shield-check" size="15" />
                <span>Transaksi Aman & Terpercaya</span>
            </div>
        </div>
    </div>
</section>

<!-- PANDUAN BELANJA (4 LANGKAH) -->
<section class="how-it-works-section">
    <div class="container">
        <div style="margin-bottom: var(--space-sm);">
            <h3 style="margin-bottom: 2px; font-size: 1.05rem;">Cara Pesan Mudah</h3>
            <p class="text-muted" style="margin-bottom: 0; font-size: 0.8rem;">4 langkah mudah belanja sembako hemat di reseller ISP.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon-box">
                    <x-icon name="package" size="16" />
                </div>
                <h4>1. Pilih Paket</h4>
                <p>Pilih paket sembako sesuai kebutuhan Anda.</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon-box">
                    <x-icon name="map-pin" size="16" />
                </div>
                <h4>2. Pilih Drop Point</h4>
                <p>Tentukan lokasi reseller terdekat dari rumah.</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon-box">
                    <x-icon name="credit-card" size="16" />
                </div>
                <h4>3. Bayar & Upload</h4>
                <p>Transfer via Bank atau QRIS dan unggah bukti.</p>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon-box">
                    <x-icon name="check-circle" size="16" />
                </div>
                <h4>4. Ambil Paket</h4>
                <p>Ambil paket di Drop Point saat status siap.</p>
            </div>
        </div>
    </div>
</section>
@endguest

@auth
<!-- WELCOME HEADER (AUTHENTICATED) -->
<section class="page-hero">
    <div class="container">
        <div>
            <h1 style="font-size: 1.45rem; margin-bottom: 4px;">Halo, {{ auth()->user()->name }}</h1>
            <p class="hero-subtitle" style="margin-bottom: 0;">
                Drop Point Utama: 
                @if(auth()->user()->dropPoint)
                    <strong>{{ auth()->user()->dropPoint->name }}</strong> ({{ auth()->user()->dropPoint->region }})
                @else
                    <span style="opacity: 0.8;">Belum ditentukan</span>
                @endif
                <a href="{{ route('profile.edit') }}" style="color: #fff; text-decoration: underline; margin-left: 6px; font-size: 0.775rem;">[Ubah]</a>
            </p>
        </div>
    </div>
</section>
@endauth

<!-- KATALOG PRODUK (E-COMMERCE APP STYLE) -->
<section class="section" id="katalog" style="padding-top: var(--space-lg);">
    <div class="container">

        <!-- Category Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
            <div>
                <h2 style="margin-bottom: 2px; font-size: 1.15rem;">Kategori Pilihan</h2>
            </div>
            @if($search || $category)
            <div>
                <a href="{{ route('home') }}#katalog" class="btn btn-ghost btn-sm" style="font-size: 0.775rem; padding: 4px 8px; color: var(--danger);">
                    <x-icon name="x" size="13" />
                    <span>Reset Filter</span>
                </a>
            </div>
            @endif
        </div>

        <!-- E-Commerce Category Icons Bar -->
        <div class="ecom-categories-bar">
            <!-- All Categories -->
            <a href="{{ route('home', array_filter(['search' => $search])) }}#katalog"
               class="ecom-cat-item {{ empty($category) ? 'active' : '' }}">
                <div class="ecom-cat-icon">
                    <x-icon name="grid" size="20" />
                </div>
                <span class="ecom-cat-label">Semua</span>
            </a>

            @foreach($categories as $cat)
            @php
                $catLower = strtolower($cat);
                $iconName = 'bag';
                if (str_contains($catLower, 'hemat') || str_contains($catLower, 'murah') || str_contains($catLower, 'promo')) {
                    $iconName = 'tag';
                } elseif (str_contains($catLower, 'standar') || str_contains($catLower, 'basic') || str_contains($catLower, 'keluarga')) {
                    $iconName = 'package';
                } elseif (str_contains($catLower, 'premium') || str_contains($catLower, 'lengkap') || str_contains($catLower, 'super')) {
                    $iconName = 'crown';
                }
            @endphp
            <a href="{{ route('home', array_filter(['category' => $cat, 'search' => $search])) }}#katalog"
               class="ecom-cat-item {{ $category == $cat ? 'active' : '' }}">
                <div class="ecom-cat-icon">
                    <x-icon name="{{ $iconName }}" size="20" />
                </div>
                <span class="ecom-cat-label">{{ $cat }}</span>
            </a>
            @endforeach
        </div>

        <!-- Section Title for Products -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-sm);">
            <div style="font-weight: 700; font-size: 0.95rem; color: var(--gray-800);">
                Daftar Paket Sembako 
                <span style="font-weight: 400; font-size: 0.8rem; color: var(--gray-500);">({{ $packages->total() }} produk)</span>
            </div>
        </div>

        <!-- Compact Product Grid (2 columns on mobile, 4-5 on desktop) -->
        @if($packages->isEmpty())
        <div class="empty-state" style="padding: var(--space-xl) var(--space-md);">
            <div class="empty-icon">
                <x-icon name="search" size="36" />
            </div>
            <h4 style="font-size: 1rem;">Paket Tidak Ditemukan</h4>
            <p style="font-size: 0.825rem;">Tidak ada paket yang cocok dengan pencarian Anda.</p>
            <a href="{{ route('home') }}#katalog" class="btn btn-outline-primary btn-sm">Lihat Semua Paket</a>
        </div>
        @else
        <div class="ecom-product-grid">
            @foreach($packages as $package)
            <div class="ecom-product-card">
                <!-- Image or Decorative Header -->
                <div style="position: relative; overflow: hidden;">
                    @if($package->image)
                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="ecom-card-img">
                    @else
                    <div class="ecom-card-placeholder">
                        <x-icon name="package" size="28" />
                        @if($package->items && count($package->items) > 0)
                        <span style="font-size: 0.675rem; font-weight: 600; color: var(--primary-800); background: rgba(255,255,255,0.85); padding: 1px 6px; border-radius: var(--radius-full); margin-top: 4px;">
                            {{ count($package->items) }} Item
                        </span>
                        @endif
                    </div>
                    @endif

                    <!-- Stock Badges -->
                    @if($package->isOutOfStock())
                    <span class="badge badge-danger" style="position: absolute; top: 6px; right: 6px; font-size: 0.65rem; padding: 2px 6px;">Habis</span>
                    @elseif($package->stock <= 5)
                    <span class="badge badge-warning" style="position: absolute; top: 6px; right: 6px; font-size: 0.65rem; padding: 2px 6px;">Sisa {{ $package->stock }}</span>
                    @endif
                </div>

                <div class="ecom-card-body">
                    @if($package->category)
                    <div style="font-size: 0.675rem; font-weight: 600; color: var(--primary-600); text-transform: uppercase; margin-bottom: 2px;">
                        {{ $package->category }}
                    </div>
                    @endif

                    <a href="{{ route('packages.show', $package) }}" class="ecom-card-title" title="{{ $package->name }}">
                        {{ $package->name }}
                    </a>

                    <div class="ecom-card-price">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                    </div>

                    <div class="ecom-card-actions">
                        <a href="{{ route('packages.show', $package) }}" class="btn btn-ghost btn-sm" style="flex: 1; padding: 4px 6px; font-size: 0.775rem; height: 32px;">
                            Detail
                        </a>
                        @if(!$package->isOutOfStock())
                        @auth
                        <form method="POST" action="{{ route('cart.add', $package) }}" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; padding: 4px 6px; font-size: 0.775rem; height: 32px; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                <x-icon name="cart" size="13" />
                                <span>Pesan</span>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="flex: 1; padding: 4px 6px; font-size: 0.775rem; height: 32px; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                            <x-icon name="cart" size="13" />
                            <span>Pesan</span>
                        </a>
                        @endauth
                        @else
                        <button class="btn btn-ghost btn-sm" disabled style="flex: 1; padding: 4px 6px; font-size: 0.775rem; height: 32px; opacity: .4;">Habis</button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
        <div class="pagination" style="padding: var(--space-md) 0;">
            @if($packages->onFirstPage())
            <span class="page-link disabled" style="min-width: 32px; height: 32px; padding: 0 8px; font-size: 0.8rem;">‹</span>
            @else
            <a href="{{ $packages->previousPageUrl() }}#katalog" class="page-link" style="min-width: 32px; height: 32px; padding: 0 8px; font-size: 0.8rem;">‹</a>
            @endif

            @foreach($packages->getUrlRange(max(1, $packages->currentPage() - 2), min($packages->lastPage(), $packages->currentPage() + 2)) as $page => $url)
            <a href="{{ $url }}#katalog" class="page-link {{ $page == $packages->currentPage() ? 'active' : '' }}" style="min-width: 32px; height: 32px; padding: 0 8px; font-size: 0.8rem;">{{ $page }}</a>
            @endforeach

            @if($packages->hasMorePages())
            <a href="{{ $packages->nextPageUrl() }}#katalog" class="page-link" style="min-width: 32px; height: 32px; padding: 0 8px; font-size: 0.8rem;">›</a>
            @else
            <span class="page-link disabled" style="min-width: 32px; height: 32px; padding: 0 8px; font-size: 0.8rem;">›</span>
            @endif
        </div>
        @endif
        @endif

    </div>
</section>

<!-- DROP POINT ACCORDION DROPDOWN -->
@if($dropPoints->isNotEmpty())
<div class="container" style="padding-bottom: var(--space-md);">
    <details class="drop-point-accordion">
        <summary class="drop-point-summary">
            <div class="drop-point-summary-content">
                <div class="drop-point-icon">
                    <x-icon name="map-pin" size="18" />
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.875rem; color: var(--gray-900);">
                        Jaringan Drop Point Reseller
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        Tersedia di {{ $dropPoints->count() }} titik reseller ISP — Bebas Biaya Kirim
                    </div>
                </div>
            </div>
            <div class="drop-point-toggle-btn">
                <span>Lihat Lokasi</span>
                <span class="drop-point-toggle-icon">
                    <x-icon name="chevron-down" size="15" />
                </span>
            </div>
        </summary>

        <div class="drop-point-body">
            <div class="drop-point-grid">
                @foreach($dropPoints as $dp)
                <div class="drop-point-card">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--gray-900);">
                            {{ $dp->name }}
                        </div>
                        <span class="badge badge-success" style="font-size: 0.65rem; padding: 2px 6px;">
                            {{ $dp->region }}
                        </span>
                    </div>

                    <div style="font-size: 0.775rem; color: var(--gray-600); line-height: 1.4; margin-bottom: 8px;">
                        {{ $dp->address }}
                    </div>

                    <div style="margin-top: auto; padding-top: 6px; border-top: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: space-between; gap: 6px; font-size: 0.725rem; color: var(--gray-500);">
                        <div style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-icon name="clock" size="12" />
                            <span>{{ $dp->operational_hours ?? '08:00 - 20:00 WIB' }}</span>
                        </div>

                        @if($dp->contact_number)
                        @php $cleanPhone = preg_replace('/[^0-9]/', '', $dp->contact_number); @endphp
                        <a href="https://wa.me/{{ str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone }}" target="_blank" class="btn btn-ghost btn-sm" style="padding: 2px 8px; height: 24px; font-size: 0.7rem; color: var(--primary-700); border-color: var(--primary-200); background: var(--primary-50); display: inline-flex; align-items: center; gap: 4px;">
                            <x-icon name="whatsapp" size="12" />
                            <span>WhatsApp</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </details>
</div>
@endif

@endsection
