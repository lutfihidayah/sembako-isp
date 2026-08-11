@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')

<!-- Filter -->
<div class="card mb-xl" style="padding: var(--space-md) var(--space-lg);">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="search-bar">
        <div class="search-input-wrapper">
            <span class="search-icon"><x-icon name="search" size="16" /></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nomor pesanan / nama konsumen..." value="{{ request('search') }}">
        </div>
        <select name="status" class="form-control form-select" style="width: 200px;">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Order::STATUS_LABELS as $val => $label)
            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="drop_point_id" class="form-control form-select" style="width: 200px;">
            <option value="">Semua Drop Point</option>
            @foreach($dropPoints as $dp)
            <option value="{{ $dp->id }}" {{ request('drop_point_id') == $dp->id ? 'selected' : '' }}>{{ $dp->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'status', 'drop_point_id']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Konsumen</th>
                <th>Drop Point</th>
                <th>Total</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>
                    <span style="font-family: monospace; font-weight: 700; color: var(--primary-700);">{{ $order->order_number }}</span>
                    @if($order->status === 'menunggu_pembayaran' && $order->payment_proof)
                    <br><span class="badge badge-warning" style="font-size: 0.65rem; margin-top: 2px;">Butuh Verifikasi</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $order->user->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--gray-400);">{{ $order->user->email }}</div>
                </td>
                <td style="font-size: 0.875rem;">{{ $order->dropPoint->name }}</td>
                <td style="font-weight: 700; color: var(--primary-600);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td style="font-size: 0.825rem;">{{ $order->payment_method_label }}</td>
                <td><span class="badge status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                <td style="font-size: 0.8rem; color: var(--gray-500);">{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-sm">
                        <span>Detail</span>
                        <x-icon name="arrow-right" size="12" />
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state" style="padding: var(--space-xl);">
                        <div class="empty-icon">
                            <x-icon name="receipt" size="36" />
                        </div>
                        <p class="text-muted">Tidak ada pesanan yang sesuai dengan filter.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
<div class="pagination">
    @if($orders->onFirstPage()) <span class="page-link disabled">‹</span> @else <a href="{{ $orders->previousPageUrl() }}" class="page-link">‹</a> @endif
    @foreach($orders->getUrlRange(max(1, $orders->currentPage()-2), min($orders->lastPage(), $orders->currentPage()+2)) as $page => $url)
    <a href="{{ $url }}" class="page-link {{ $page == $orders->currentPage() ? 'active' : '' }}">{{ $page }}</a>
    @endforeach
    @if($orders->hasMorePages()) <a href="{{ $orders->nextPageUrl() }}" class="page-link">›</a> @else <span class="page-link disabled">›</span> @endif
</div>
@endif

@endsection
