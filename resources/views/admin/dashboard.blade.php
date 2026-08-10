@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="admin-topbar">
    <div>
        <div class="page-title">Ringkasan Dashboard</div>
        <div class="page-subtitle">Selamat datang, {{ Auth::guard('admin')->user()->name }}. Berikut rangkuman operasional terkini.</div>
    </div>
    <div style="font-size: 0.875rem; color: var(--gray-500); display: flex; align-items: center; gap: 6px;">
        <x-icon name="clock" size="15" />
        <span>{{ now()->format('d M Y, H:i') }} WIB</span>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid mb-xl">
    <div class="stat-card">
        <div class="stat-icon green">
            <x-icon name="receipt" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Total Pesanan Masuk</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <x-icon name="clock" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['pending_payment']) }}</div>
            <div class="stat-label">Menunggu Pembayaran</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <x-icon name="refresh" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['orders_processing']) }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <x-icon name="check-circle" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['orders_completed']) }}</div>
            <div class="stat-label">Pesanan Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <x-icon name="wallet" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value" style="font-size: 1.25rem;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Omzet Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <x-icon name="map-pin" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['active_drop_points'] }}</div>
            <div class="stat-label">Drop Point Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <x-icon name="package" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_packages'] }}</div>
            <div class="stat-label">Paket Sembako Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <x-icon name="users" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Konsumen Terdaftar</div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <span style="display: flex; align-items: center; gap: 8px;">
            <x-icon name="receipt" size="16" />
            <span>Pesanan Terbaru</span>
        </span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <span>Lihat Semua</span>
            <x-icon name="arrow-right" size="13" />
        </a>
    </div>
    @if($recentOrders->isEmpty())
    <div class="empty-state" style="padding: var(--space-xl);">
        <div class="empty-icon">
            <x-icon name="receipt" size="36" />
        </div>
        <p class="text-muted">Belum ada pesanan masuk.</p>
    </div>
    @else
    <div class="table-wrapper" style="border: none; border-radius: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Konsumen</th>
                    <th>Drop Point</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td><span style="font-family: monospace; font-weight: 600; color: var(--primary-700);">{{ $order->order_number }}</span></td>
                    <td>
                        <div style="font-weight: 500;">{{ $order->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-400);">{{ $order->user->email }}</div>
                    </td>
                    <td style="font-size: 0.875rem;">{{ $order->dropPoint->name }}</td>
                    <td style="font-weight: 700; color: var(--primary-600);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><span class="badge status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-sm">Detail →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
