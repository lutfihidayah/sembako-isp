<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Admin') — Sembako ISP Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300873d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z'/%3E%3Cpath d='M3 6h18'/%3E%3Cpath d='M16 10a4 4 0 0 1-8 0'/%3E%3C/svg%3E">
    @stack('styles')
</head>
<body>

<!-- Admin Mobile Top Header Bar -->
<div class="admin-mobile-header">
    <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: 1.1rem;">
        <div style="width: 32px; height: 32px; background: var(--primary-500); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
            <x-icon name="logo" size="18" />
        </div>
        <span>Admin ISP</span>
    </div>
    <button id="admin-sidebar-toggle" style="background: none; border: 1.5px solid rgba(255,255,255,.2); color: #fff; padding: 6px 10px; border-radius: var(--radius-md); cursor: pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
        </svg>
    </button>
</div>

<!-- Mobile Drawer Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <x-icon name="logo" size="20" />
            </div>
            <span>Sembako ISP</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon"><x-icon name="dashboard" size="16" /></span>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-label">Data Master</div>
            <a href="{{ route('admin.drop-points.index') }}" class="sidebar-item {{ request()->routeIs('admin.drop-points.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="map-pin" size="16" /></span>
                <span>Drop Points</span>
            </a>
            <a href="{{ route('admin.packages.index') }}" class="sidebar-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="package" size="16" /></span>
                <span>Paket Sembako</span>
            </a>

            <div class="sidebar-section-label">Transaksi</div>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="receipt" size="16" /></span>
                <span>Kelola Pesanan</span>
            </a>
            @php
                $pendingCount = \App\Models\Order::where('status', 'menunggu_pembayaran')->whereNotNull('payment_proof')->count();
            @endphp
            @if($pendingCount > 0)
            <a href="{{ route('admin.orders.index', ['status' => 'menunggu_pembayaran']) }}" class="sidebar-item" style="color: var(--warning);">
                <span class="icon"><x-icon name="credit-card" size="16" /></span>
                <span>Verifikasi Bayar</span>
                <span class="badge badge-warning" style="margin-left: auto; font-size: 0.7rem;">{{ $pendingCount }}</span>
            </a>
            @endif

            <div class="sidebar-section-label">Laporan</div>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="bar-chart" size="16" /></span>
                <span>Laporan Penjualan</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div style="font-size: 0.8rem; color: rgba(255,255,255,.5); margin-bottom: var(--space-sm);">
                Login sebagai:<br>
                <strong style="color: rgba(255,255,255,.85);">{{ Auth::guard('admin')->user()->name }}</strong>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 12px; border-radius: var(--radius-md); background: rgba(255,255,255,.08); border: none; color: rgba(255,255,255,.75); font-size: 0.875rem; cursor: pointer; font-family: inherit; transition: all var(--transition);">
                    <x-icon name="logout" size="15" />
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-content">

        @if(session('success') || session('error') || session('warning'))
        <div style="margin-bottom: var(--space-md);">
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

        @yield('content')
    </main>

</div>

<script>
const sidebarToggle = document.getElementById('admin-sidebar-toggle');
const adminSidebar = document.getElementById('admin-sidebar');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');

function toggleSidebar() {
    adminSidebar.classList.toggle('open');
    sidebarBackdrop.classList.toggle('open');
}

sidebarToggle?.addEventListener('click', toggleSidebar);
sidebarBackdrop?.addEventListener('click', toggleSidebar);
</script>

@stack('scripts')
</body>
</html>
