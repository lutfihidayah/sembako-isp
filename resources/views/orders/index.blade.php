@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="section">
<div class="container">
    <h1 style="margin-bottom: var(--space-xl); display: flex; align-items: center; gap: 10px;">
        <x-icon name="clipboard" size="26" />
        <span>Pesanan Saya</span>
    </h1>

    @if($orders->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <x-icon name="package" size="54" />
        </div>
        <h3>Belum Ada Pesanan</h3>
        <p>Anda belum pernah melakukan pemesanan paket sembako.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Mulai Belanja</a>
    </div>
    @else
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Drop Point</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <span style="font-weight: 700; font-family: monospace; color: var(--primary-700);">{{ $order->order_number }}</span>
                    </td>
                    <td>
                        <div style="font-size: 0.875rem;">{{ $order->created_at->format('d M Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-400);">{{ $order->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $order->dropPoint->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-500);">{{ $order->dropPoint->region }}</div>
                    </td>
                    <td style="font-weight: 700; color: var(--primary-600);">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    </td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-ghost btn-sm">
                            <span>Detail</span>
                            <x-icon name="arrow-right" size="13" />
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination">
        @if($orders->onFirstPage())
        <span class="page-link disabled">‹</span>
        @else
        <a href="{{ $orders->previousPageUrl() }}" class="page-link">‹</a>
        @endif

        @foreach($orders->getUrlRange(max(1, $orders->currentPage()-2), min($orders->lastPage(), $orders->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="page-link {{ $page == $orders->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($orders->hasMorePages())
        <a href="{{ $orders->nextPageUrl() }}" class="page-link">›</a>
        @else
        <span class="page-link disabled">›</span>
        @endif
    </div>
    @endif
    @endif
</div>
</div>
@endsection
