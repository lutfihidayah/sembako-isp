<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Aplikasi Paket Sembako ISP - Pesan sembako online, ambil di drop point terdekat">
    <title>@yield('title', 'Sembako ISP') – Paket Sembako Online</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300873d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z'/%3E%3Cpath d='M3 6h18'/%3E%3Cpath d='M16 10a4 4 0 0 1-8 0'/%3E%3C/svg%3E">
    @stack('styles')
</head>
<body class="@auth has-bottom-nav @endauth">

@if(!request()->routeIs('login', 'register', 'password.*'))
<!-- TOP NAVBAR (E-COMMERCE STYLE WITH HEADER SEARCH) -->
<nav class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="brand-icon">
                <x-icon name="logo" size="20" />
            </div>
            <span>Sembako ISP</span>
        </a>

        <!-- Header Search Bar -->
        <form method="GET" action="{{ route('home') }}#katalog" class="header-search-form">
            <div class="header-search-wrapper">
                <span class="header-search-icon"><x-icon name="search" size="14" /></span>
                <input type="text" name="search" class="header-search-input" placeholder="Cari paket sembako..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="header-search-btn" title="Cari">
                <x-icon name="search" size="13" />
            </button>
        </form>

        <div class="navbar-actions">
            @php $cartCount = collect(session('cart', []))->sum(); @endphp
            @auth
            <div class="user-menu">
                <button class="user-btn">
                    @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="user-avatar" style="object-fit: cover;">
                    @else
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    @endif
                    <span>{{ Str::words(auth()->user()->name, 1, '') }}</span>
                    <span style="font-size: 10px; opacity: 0.6;">▼</span>
                </button>
                <div class="dropdown-menu">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 8px 12px 12px; border-bottom: 1px solid var(--gray-100); margin-bottom: 4px;">
                        @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 36px; height: 36px; border-radius: var(--radius-full); object-fit: cover;">
                        @else
                        <div class="user-avatar" style="width: 36px; height: 36px; font-size: 14px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        @endif
                        <div style="overflow: hidden;">
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--gray-800); white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">{{ auth()->user()->name }}</div>
                            <div style="font-size: 0.775rem; color: var(--gray-500); white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}">
                        <x-icon name="user" size="15" />
                        <span>Edit Profil & Foto</span>
                    </a>
                    <a href="{{ route('orders.index') }}">
                        <x-icon name="clipboard" size="15" />
                        <span>Riwayat Pesanan</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-danger">
                            <x-icon name="logout" size="15" />
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-ghost btn-sm" style="font-size: 0.825rem; padding: 6px 10px;">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="font-size: 0.825rem; padding: 6px 12px;">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
@endif

@if(session('success') || session('error') || session('warning'))
<div class="container" style="padding-top: var(--space-md); padding-bottom: 0;">
    @if(session('success'))
    <div class="alert alert-success">
        <span class="alert-icon"><x-icon name="check-circle" size="18" /></span>
        <span>{{ session('success') }}</span>
        <button class="alert-close" onclick="this.parentElement.remove()"><x-icon name="x" size="14" /></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
        <span>{{ session('error') }}</span>
        <button class="alert-close" onclick="this.parentElement.remove()"><x-icon name="x" size="14" /></button>
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning">
        <span class="alert-icon"><x-icon name="alert-triangle" size="18" /></span>
        <span>{{ session('warning') }}</span>
        <button class="alert-close" onclick="this.parentElement.remove()"><x-icon name="x" size="14" /></button>
    </div>
    @endif
</div>
@endif

<main style="flex: 1;">@yield('content')</main>

@if(!request()->routeIs('login', 'register', 'password.*'))
<!-- FOOTER -->
<footer class="site-footer">
    <div class="container">
        <p>© {{ date('Y') }} <strong>Sembako ISP</strong> — Layanan Paket Sembako Drop Point Reseller ISP.</p>
    </div>
</footer>
@endif

@if(!request()->routeIs('login', 'register', 'password.*'))
<!-- ============================================================
     MOBILE BOTTOM APP BAR NAVIGATION (PIXEL-PERFECT MATCH)
     ============================================================ -->
<div class="mobile-bottom-nav">
    <!-- 1. Beranda -->
    <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') && !request()->has('category') && !request()->has('search') ? 'active' : '' }}">
        <div class="nav-icon"><x-icon name="home" size="20" /></div>
        <span>Beranda</span>
    </a>

    <!-- 2. Katalog / Produk -->
    <a href="{{ route('home') }}#katalog" class="bottom-nav-item {{ request()->has('category') || request()->has('search') ? 'active' : '' }}">
        <div class="nav-icon"><x-icon name="grid" size="20" /></div>
        <span>Katalog</span>
    </a>

    <!-- 3. Floating Center Action Button (Keranjang Belanja) -->
    <a href="{{ route('cart.index') }}" class="bottom-nav-center {{ request()->routeIs('cart.*') ? 'active' : '' }}" title="Keranjang Belanja">
        <x-icon name="cart" size="22" />
        @php $cartCount = collect(session('cart', []))->sum(); @endphp
        @if($cartCount > 0)
        <span class="cart-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
        @endif
    </a>

    <!-- 4. Pesanan Saya -->
    <a href="{{ auth()->check() ? route('orders.index') : route('login') }}" class="bottom-nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
        <div class="nav-icon"><x-icon name="clipboard" size="20" /></div>
        <span>Pesanan</span>
    </a>

    <!-- 5. Akun / Profil -->
    <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') || request()->routeIs('login') || request()->routeIs('register') ? 'active' : '' }}">
        <div class="nav-icon"><x-icon name="user" size="20" /></div>
        <span>Akun</span>
    </a>
</div>
@endif

@stack('scripts')
</body>
</html>
