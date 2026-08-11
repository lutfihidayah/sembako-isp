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
            <line x1="4" x2="20" y1="12" x2="20"/><line x1="4" x2="20" y1="6" x2="6"/><line x1="4" x2="20" y1="18" x2="18"/>
        </svg>
    </button>
</div>

<!-- Mobile Drawer Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

<div class="admin-layout">

    <!-- SIDEBAR (LEFT) -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <x-icon name="logo" size="20" />
            </div>
            <span>Sembako ISP</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon"><x-icon name="dashboard" size="16" /></span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="receipt" size="16" /></span>
                <span>Kelola Pesanan</span>
                @php
                    $pendingCount = \App\Models\Order::where('status', 'menunggu_pembayaran')->whereNotNull('payment_proof')->count();
                @endphp
                @if($pendingCount > 0)
                <span class="badge badge-warning" style="margin-left: auto; font-size: 0.675rem; padding: 2px 6px;">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.packages.index') }}" class="sidebar-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="package" size="16" /></span>
                <span>Paket Sembako</span>
            </a>

            <a href="{{ route('admin.drop-points.index') }}" class="sidebar-item {{ request()->routeIs('admin.drop-points.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="map-pin" size="16" /></span>
                <span>Drop Points</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <span class="icon"><x-icon name="bar-chart" size="16" /></span>
                <span>Laporan Penjualan</span>
            </a>
        </nav>
    </aside>

    <!-- RIGHT MAIN WRAPPER (ATTACHED HEADER + CONTENT) -->
    <div class="admin-main-wrapper">

        <!-- SEAMLESS ATTACHED TOP NAVBAR (FLUSH WITH SIDEBAR) -->
        <header class="admin-top-nav">
            <!-- Left: Toggle Menu Button -->
            <button type="button" class="header-toggle-btn" id="desktop-sidebar-toggle" title="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <!-- Right: Admin Profile Avatar Trigger with Dropdown -->
            <div class="header-actions-group">
                <div class="admin-profile-dropdown-wrapper" id="admin-profile-wrapper">
                    <button type="button" class="header-circle-btn dark" id="admin-profile-toggle" title="Menu Administrator" aria-expanded="false" style="cursor: pointer;">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="admin-dropdown-menu" id="admin-dropdown-menu">
                        <div class="admin-dropdown-header">
                            <div class="header-circle-btn dark" style="width: 34px; height: 34px; font-size: 0.85rem; flex-shrink: 0;">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                            </div>
                            <div style="overflow: hidden; line-height: 1.2;">
                                <div style="font-weight: 700; font-size: 0.825rem; color: #0f172a; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                                    {{ Auth::guard('admin')->user()->name }}
                                </div>
                                <div style="font-size: 0.7rem; color: #64748b; margin-top: 2px;">Administrator</div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <a href="{{ route('admin.dashboard') }}" class="admin-dropdown-item">
                            <x-icon name="dashboard" size="14" />
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('home') }}" target="_blank" class="admin-dropdown-item">
                            <x-icon name="eye" size="14" />
                            <span>Lihat Toko Publik</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="admin-dropdown-item danger" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; font-family: inherit;">
                                <x-icon name="logout" size="14" />
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT WITH INNER PADDING -->
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

</div>

<script>
// Sidebar Toggle Logic
const sidebarToggle = document.getElementById('admin-sidebar-toggle');
const desktopSidebarToggle = document.getElementById('desktop-sidebar-toggle');
const adminSidebar = document.getElementById('admin-sidebar');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');

function toggleSidebar() {
    adminSidebar.classList.toggle('open');
    if (sidebarBackdrop) sidebarBackdrop.classList.toggle('open');
}

sidebarToggle?.addEventListener('click', toggleSidebar);
desktopSidebarToggle?.addEventListener('click', toggleSidebar);
sidebarBackdrop?.addEventListener('click', toggleSidebar);

// Admin Profile Dropdown Logic
const profileToggle = document.getElementById('admin-profile-toggle');
const profileMenu = document.getElementById('admin-dropdown-menu');
const profileWrapper = document.getElementById('admin-profile-wrapper');

profileToggle?.addEventListener('click', function(e) {
    e.stopPropagation();
    profileMenu?.classList.toggle('show');
    const isExpanded = profileMenu?.classList.contains('show');
    profileToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
});

// Close when clicking outside
document.addEventListener('click', function(e) {
    if (profileWrapper && !profileWrapper.contains(e.target)) {
        profileMenu?.classList.remove('show');
        profileToggle?.setAttribute('aria-expanded', 'false');
    }
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && profileMenu?.classList.contains('show')) {
        profileMenu.classList.remove('show');
        profileToggle?.setAttribute('aria-expanded', 'false');
    }
});
</script>

@stack('scripts')
</body>
</html>
