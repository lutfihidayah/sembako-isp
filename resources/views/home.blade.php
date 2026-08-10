@extends('layouts.app')

@section('title', auth()->check() ? 'Katalog Paket Sembako' : 'Layanan Paket Sembako Jaringan Reseller ISP')

@section('content')

@guest
<!-- ============================================================
     LANDING PAGE INFORMASI RESMI (UNTUK PENGUNJUNG / GUEST)
     ============================================================ -->

<!-- HERO BANNER INFORMASI -->
<section class="page-hero" style="padding: var(--space-2xl) 0 var(--space-xl);">
    <div class="container">
        <div style="max-width: 680px;">
            <div class="hero-badge-pill">
                <x-icon name="shield-check" size="14" />
                <span>Layanan Resmi Jaringan Reseller ISP</span>
            </div>

            <h1 style="font-size: 1.85rem; font-weight: 800; line-height: 1.25; margin-bottom: 10px;">
                Belanja Paket Sembako Hemat, Ambil di Reseller ISP Terdekat
            </h1>

            <p class="hero-subtitle" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: var(--space-lg);">
                Program khusus bagi pelanggan jaringan internet ISP. Pesan paket sembako kebutuhan rumah tangga secara online, bayar mudah via Transfer Bank atau QRIS, dan ambil pesanan langsung di Drop Point reseller tanpa biaya pengiriman.
            </p>

            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: var(--space-lg);">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="box-shadow: 0 4px 14px rgba(0,0,0,0.25); display: inline-flex; align-items: center; gap: 8px;">
                    <x-icon name="user" size="18" />
                    <span>Masuk ke Akun</span>
                </a>
                <a href="{{ route('register') }}" class="btn btn-ghost btn-lg" style="background: rgba(255,255,255,0.15); color: #fff; border-color: rgba(255,255,255,0.3); display: inline-flex; align-items: center; gap: 8px;">
                    <span>Daftar Akun Baru</span>
                </a>
            </div>

            <div class="hero-trust-list">
                <div class="hero-trust-item">
                    <x-icon name="check-circle" size="15" />
                    <span>100% Bebas Ongkos Kirim</span>
                </div>
                <div class="hero-trust-item">
                    <x-icon name="map-pin" size="15" />
                    <span>{{ $dropPoints->count() }}+ Titik Drop Point Reseller</span>
                </div>
                <div class="hero-trust-item">
                    <x-icon name="shield-check" size="15" />
                    <span>Transaksi Aman & Terpercaya</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KEUNGGULAN LAYANAN KITA -->
<section class="section" style="background: #ffffff; padding: var(--space-2xl) 0;">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto var(--space-xl);">
            <div style="color: var(--primary-700); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">
                Keunggulan Layanan
            </div>
            <h2 style="font-size: 1.4rem; color: var(--gray-900); margin-bottom: 6px;">
                Mengapa Belanja Sembako Lewat Jaringan Reseller ISP?
            </h2>
            <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 0;">
                Solusi belanja praktis, hemat, dan terdekat langsung dari mitra reseller internet di lingkungan Anda.
            </p>
        </div>

        <div class="landing-features-grid">
            <div class="landing-feature-card">
                <div class="landing-feature-icon">
                    <x-icon name="truck" size="22" />
                </div>
                <h4>100% Bebas Ongkos Kirim</h4>
                <p>Paket sembako diantar langsung ke Drop Point reseller terdekat tanpa biaya pengiriman tambahan sepeserpun.</p>
            </div>

            <div class="landing-feature-card">
                <div class="landing-feature-icon">
                    <x-icon name="tag" size="22" />
                </div>
                <h4>Harga Hemat & Kualitas Terjamin</h4>
                <p>Isi paket sembako dikurasi dengan kualitas terbaik dan harga paket yang lebih bersahabat dibandingkan harga eceran pasar.</p>
            </div>

            <div class="landing-feature-card">
                <div class="landing-feature-icon">
                    <x-icon name="credit-card" size="22" />
                </div>
                <h4>Pembayaran Mudah & Aman</h4>
                <p>Tersedia metode pembayaran Transfer Bank dan QRIS instan dengan verifikasi transaksi yang transparan dan cepat.</p>
            </div>

            <div class="landing-feature-card">
                <div class="landing-feature-icon">
                    <x-icon name="clipboard" size="22" />
                </div>
                <h4>Lacak Status Pesanan Jelas</h4>
                <p>Pantau tahapan pesanan mulai dari proses belanja, pengiriman ke reseller, hingga notifikasi siap diambil.</p>
            </div>
        </div>
    </div>
</section>

<!-- CARA KERJA & PANDUAN PESAN (4 LANGKAH MUDAH) -->
<section class="how-it-works-section" style="background: var(--gray-50); padding: var(--space-2xl) 0;">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto var(--space-lg);">
            <div style="color: var(--primary-700); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">
                Panduan Belanja
            </div>
            <h2 style="font-size: 1.35rem; color: var(--gray-900); margin-bottom: 6px;">
                4 Langkah Mudah Mendapatkan Paket Sembako
            </h2>
            <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0;">
                Alur belanja yang praktis dan terstruktur dari pemesanan hingga pengambilan.
            </p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon-box">
                    <x-icon name="package" size="18" />
                </div>
                <h4>1. Pilih Paket Sembako</h4>
                <p>Pilih kategori paket hemat, standar, atau premium sesuai kebutuhan keluarga.</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon-box">
                    <x-icon name="map-pin" size="18" />
                </div>
                <h4>2. Tentukan Drop Point</h4>
                <p>Pilih titik reseller ISP terdekat dari tempat tinggal Anda untuk lokasi ambil.</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon-box">
                    <x-icon name="credit-card" size="18" />
                </div>
                <h4>3. Bayar & Konfirmasi</h4>
                <p>Selesaikan transfer pembayaran (Bank / QRIS) dan admin segera membelanjakan paket.</p>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon-box">
                    <x-icon name="check-circle" size="18" />
                </div>
                <h4>4. Ambil di Reseller</h4>
                <p>Kunjungi Drop Point saat status pesanan 'Siap Diambil' dan bawa pulang sembako Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- INFORMASI JARINGAN DROP POINT -->
@if($dropPoints->isNotEmpty())
<section style="background: #ffffff; padding: var(--space-2xl) 0;">
    <div class="container">
        <details class="drop-point-accordion">
            <summary class="drop-point-summary">
                <div class="drop-point-summary-content">
                    <div class="drop-point-icon">
                        <x-icon name="map-pin" size="18" />
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem; color: var(--gray-900);">
                            Jaringan Lokasi Drop Point Reseller ISP
                        </div>
                        <div style="font-size: 0.775rem; color: var(--gray-500);">
                            Tersedia di {{ $dropPoints->count() }} titik reseller aktif — Bebas Biaya Kirim
                        </div>
                    </div>
                </div>
                <div class="drop-point-toggle-btn">
                    <span>Lihat Daftar Lokasi</span>
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
</section>
@endif

<!-- CALL TO ACTION (CTA) -->
<div class="container">
    <div class="landing-cta-card">
        <h3 style="color: #fff; font-size: 1.45rem; font-weight: 800; margin-bottom: 8px;">
            Sudah Menjadi Pelanggan Jaringan Internet ISP?
        </h3>
        <p style="color: rgba(255,255,255,0.9); font-size: 0.9rem; max-width: 540px; margin: 0 auto var(--space-lg); line-height: 1.5;">
            Masuk atau daftarkan akun Anda sekarang untuk melihat katalog lengkap dan mulai memesan paket sembako hemat.
        </p>
        <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('login') }}" class="btn btn-ghost btn-lg" style="background: #ffffff; color: var(--primary-700); font-weight: 700; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                Masuk Sekarang
            </a>
            <a href="{{ route('register') }}" class="btn btn-ghost btn-lg" style="background: rgba(255,255,255,0.15); color: #fff; border-color: rgba(255,255,255,0.3);">
                Daftar Akun Baru
            </a>
        </div>
    </div>
</div>
@endguest


@auth
<!-- ============================================================
     ETALASE & KATALOG BELANJA (UNTUK PENGGUNA LOGIN / AUTH)
     ============================================================ -->

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
                        <form method="POST" action="{{ route('cart.add', $package) }}" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; padding: 4px 6px; font-size: 0.775rem; height: 32px; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                <x-icon name="cart" size="13" />
                                <span>Pesan</span>
                            </button>
                        </form>
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

<!-- DROP POINT ACCORDION DROPDOWN (UNTUK USER LOGIN) -->
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
@endauth

@endsection
