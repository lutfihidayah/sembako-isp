@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')

<div class="admin-topbar">
    <div>
        <div class="page-title">Laporan Penjualan & Pendapatan</div>
        <div class="page-subtitle">Rangkuman transaksi dan pendapatan berdasarkan rentang tanggal dan drop point.</div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-xl">
    <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
        <x-icon name="search" size="16" />
        <span>Filter Parameter Laporan</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="search-bar">
            <div class="form-group" style="margin: 0; flex: 1;">
                <label class="form-label" style="font-size: 0.8rem;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="form-group" style="margin: 0; flex: 1;">
                <label class="form-label" style="font-size: 0.8rem;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="form-group" style="margin: 0; flex: 1;">
                <label class="form-label" style="font-size: 0.8rem;">Drop Point</label>
                <select name="drop_point_id" class="form-control form-select">
                    <option value="">Semua Drop Point</option>
                    @foreach($dropPoints as $dp)
                    <option value="{{ $dp->id }}" {{ $dropPointId == $dp->id ? 'selected' : '' }}>{{ $dp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn btn-primary">Tampilkan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="stats-grid mb-xl">
    <div class="stat-card">
        <div class="stat-icon blue">
            <x-icon name="receipt" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($summary['total_orders']) }}</div>
            <div class="stat-label">Total Pesanan Diproses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <x-icon name="check-circle" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($summary['completed_orders']) }}</div>
            <div class="stat-label">Pesanan Berhasil Diambil</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <x-icon name="wallet" size="24" />
        </div>
        <div class="stat-content">
            <div class="stat-value" style="font-size: 1.15rem;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Omzet Penjualan</div>
        </div>
    </div>
</div>

<!-- Per Drop Point -->
@if($perDropPoint->isNotEmpty())
<div class="card mb-xl">
    <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
        <x-icon name="map-pin" size="16" />
        <span>Rincian Transaksi per Drop Point (Pesanan Selesai)</span>
    </div>
    <div class="table-wrapper" style="border: none; border-radius: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Drop Point</th>
                    <th>Jumlah Pesanan Selesai</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perDropPoint as $row)
                <tr>
                    <td style="font-weight: 600;">{{ $row->dp_name }}</td>
                    <td>{{ number_format($row->count) }} transaksi</td>
                    <td style="font-weight: 700; color: var(--primary-600);">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Orders Table -->
<div class="card">
    <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
        <x-icon name="clipboard" size="16" />
        <span>Daftar Rincian Pesanan (Periode: {{ $startDate }} s/d {{ $endDate }})</span>
    </div>
    @if($orders->isEmpty())
    <div class="empty-state" style="padding: var(--space-xl);">
        <div class="empty-icon">
            <x-icon name="receipt" size="36" />
        </div>
        <p class="text-muted">Tidak ada data pesanan pada periode filter ini.</p>
    </div>
    @else
    <div class="table-wrapper" style="border: none; border-radius: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Konsumen</th>
                    <th>Drop Point</th>
                    <th>Total Tagihan</th>
                    <th>Status</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" style="font-family: monospace; font-weight: 700; color: var(--primary-700);">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td>{{ $order->user->name }}</td>
                    <td style="font-size: 0.875rem;">{{ $order->dropPoint->name }}</td>
                    <td style="font-weight: 700; color: var(--primary-600);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><span class="badge status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td style="font-size: 0.8rem; color: var(--gray-500);">{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="pagination">
        @if($orders->onFirstPage()) <span class="page-link disabled">‹</span> @else <a href="{{ $orders->previousPageUrl()->appends(request()->except('page')) }}" class="page-link">‹</a> @endif
        @foreach($orders->getUrlRange(max(1, $orders->currentPage()-2), min($orders->lastPage(), $orders->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="page-link {{ $page == $orders->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($orders->hasMorePages()) <a href="{{ $orders->nextPageUrl() }}" class="page-link">›</a> @else <span class="page-link disabled">›</span> @endif
    </div>
    @endif
    @endif
</div>

@endsection
