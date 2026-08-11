@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="shopee-orders-container">

    <!-- ============================================================
         1. DESKTOP BREADCRUMBS & APP BAR HEADER
         ============================================================ -->
    <div style="margin-bottom: 12px;">
        <div style="font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Beranda</a>
            <span>/</span>
            <span style="color: #0f172a; font-weight: 600;">Pesanan Saya</span>
        </div>

        <div class="shopee-app-bar">
            <div class="shopee-app-bar-title">
                <a href="{{ route('home') }}" style="color: inherit; display: flex; align-items: center; text-decoration: none;" title="Kembali ke Beranda">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                </a>
                <span>Daftar Pesanan Saya</span>
            </div>

            <!-- Desktop Search & Cart Shortcut -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <form method="GET" action="{{ route('orders.index') }}" style="display: flex; align-items: center; gap: 6px;">
                    <input type="hidden" name="status" value="{{ $statusTab }}">
                    <div class="search-input-wrapper" style="width: 220px;">
                        <span class="search-icon"><x-icon name="search" size="14" /></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari invoice / produk..." value="{{ request('search') }}" style="height: 34px; font-size: 0.8rem; padding-left: 32px;">
                    </div>
                </form>

                <a href="{{ route('cart.index') }}" class="btn btn-ghost btn-sm" style="display: inline-flex; align-items: center; gap: 6px; position: relative;" title="Keranjang Belanja">
                    <x-icon name="cart" size="18" />
                    <span style="display: none; @media(min-width:768px){display:inline;}">Keranjang</span>
                    @php $cartCount = collect(session('cart', []))->sum(); @endphp
                    @if($cartCount > 0)
                    <span style="background: #ef4444; color: #fff; font-size: 0.6rem; font-weight: 800; min-width: 16px; height: 16px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; line-height: 1;">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================
         2. SHOPEE HORIZONTAL SCROLLABLE STATUS TABS
         ============================================================ -->
    <div class="shopee-tabs-wrapper">
        <div class="shopee-tabs">
            <!-- 1. Semua -->
            <a href="{{ route('orders.index', ['status' => 'all', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'all' ? 'active' : '' }}">
                <span>Semua</span>
                @if($counts['all'] > 0)
                <span style="font-size: 0.75rem; opacity: 0.7;">({{ $counts['all'] }})</span>
                @endif
            </a>

            <!-- 2. Belum Bayar -->
            <a href="{{ route('orders.index', ['status' => 'belum_bayar', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'belum_bayar' ? 'active' : '' }}">
                <span>Belum Bayar</span>
                @if($counts['belum_bayar'] > 0)
                <span class="tab-badge">{{ $counts['belum_bayar'] }}</span>
                @endif
            </a>

            <!-- 3. Dikemas / Dibelanjakan -->
            <a href="{{ route('orders.index', ['status' => 'dikemas', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'dikemas' ? 'active' : '' }}">
                <span>Dikemas</span>
                @if($counts['dikemas'] > 0)
                <span class="tab-badge" style="background: #0284c7;">{{ $counts['dikemas'] }}</span>
                @endif
            </a>

            <!-- 4. Dikirim -->
            <a href="{{ route('orders.index', ['status' => 'dikirim', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'dikirim' ? 'active' : '' }}">
                <span>Dikirim</span>
                @if($counts['dikirim'] > 0)
                <span class="tab-badge" style="background: #6366f1;">{{ $counts['dikirim'] }}</span>
                @endif
            </a>

            <!-- 5. Siap Diambil -->
            <a href="{{ route('orders.index', ['status' => 'siap_diambil', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'siap_diambil' ? 'active' : '' }}">
                <span>Siap Diambil</span>
                @if($counts['siap_diambil'] > 0)
                <span class="tab-badge" style="background: #00873d;">{{ $counts['siap_diambil'] }}</span>
                @endif
            </a>

            <!-- 6. Selesai -->
            <a href="{{ route('orders.index', ['status' => 'selesai', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'selesai' ? 'active' : '' }}">
                <span>Selesai</span>
            </a>

            <!-- 7. Dibatalkan -->
            <a href="{{ route('orders.index', ['status' => 'dibatalkan', 'search' => request('search')]) }}" class="shopee-tab {{ $statusTab === 'dibatalkan' ? 'active' : '' }}">
                <span>Dibatalkan</span>
            </a>
        </div>
    </div>

    <!-- ============================================================
         3. ORDER CARDS LIST (SHOPEE STYLE - MOBILE & DESKTOP TUNED)
         ============================================================ -->
    @if($orders->isEmpty())
    <div class="card" style="padding: 56px 20px; text-align: center; border-radius: 14px; margin-top: 12px; border: 1px solid #e2e8f0;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <x-icon name="package" size="34" />
        </div>
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Belum Ada Pesanan</h3>
        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 20px; max-width: 380px; margin-left: auto; margin-right: auto;">
            @if(request('search'))
            Tidak ada pesanan yang sesuai dengan kata kunci "<strong>{{ request('search') }}</strong>".
            @elseif($statusTab !== 'all')
            Tidak ada transaksi di kategori status ini.
            @else
            Anda belum pernah melakukan pemesanan paket sembako.
            @endif
        </p>
        <div style="display: flex; justify-content: center; gap: 8px;">
            @if(request('search'))
            <a href="{{ route('orders.index', ['status' => $statusTab]) }}" class="btn btn-ghost">Reset Pencarian</a>
            @endif
            <a href="{{ route('home') }}#katalog" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700;">
                <x-icon name="bag" size="16" />
                <span>Mulai Belanja Sembako</span>
            </a>
        </div>
    </div>
    @else
    <div class="shopee-orders-list">
        @foreach($orders as $order)
        <div class="shopee-order-card">
            
            <!-- Card Header: Drop Point Info & Status Text -->
            <div class="shopee-card-header">
                <div class="shopee-seller-name">
                    <span class="shopee-seller-badge">Drop Point</span>
                    <span class="shopee-seller-title">{{ $order->dropPoint->name }}</span>
                    <span style="font-size: 0.725rem; color: #94a3b8; margin-left: 4px; font-family: monospace;">({{ $order->order_number }})</span>
                </div>
                <div class="shopee-status-text {{ $order->status }}">
                    {{ $order->status_label }}
                </div>
            </div>

            <!-- Card Product Items -->
            @foreach($order->items as $item)
            <a href="{{ route('orders.show', $order) }}" class="shopee-card-item">
                <!-- Thumbnail -->
                @if($item->package && $item->package->image)
                <img src="{{ asset('storage/' . $item->package->image) }}" alt="{{ $item->package->name }}" class="shopee-item-img">
                @else
                <div class="shopee-item-img" style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <x-icon name="package" size="28" />
                </div>
                @endif

                <!-- Product Details -->
                <div class="shopee-item-details">
                    <div>
                        <div class="shopee-item-title">
                            {{ $item->package ? $item->package->name : 'Paket Sembako' }}
                        </div>
                        @if($item->package && !empty($item->package->items) && is_array($item->package->items))
                        <div class="shopee-item-variant">
                            {{ implode(', ', array_slice($item->package->items, 0, 4)) }}{{ count($item->package->items) > 4 ? '...' : '' }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="shopee-item-price-row">
                        <span style="font-size: 0.775rem; color: #64748b; font-weight: 500;">x{{ $item->quantity }}</span>
                        <span style="font-weight: 700; color: #0f172a; font-size: 0.875rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
            @endforeach

            <!-- Contextual Banner / Notification (Shopee style alert) -->
            @if($order->status === 'menunggu_pembayaran')
                @if($order->payment_proof)
                <div class="shopee-notice-box success">
                    <x-icon name="clock" size="15" />
                    <span>Bukti transfer sudah diunggah. Menunggu verifikasi admin ISP.</span>
                </div>
                @else
                <div class="shopee-notice-box warning">
                    <x-icon name="alert-triangle" size="15" />
                    <span>Silakan selesaikan transfer & upload bukti pembayaran agar pesanan segera disiapkan.</span>
                </div>
                @endif
            @elseif($order->status === 'siap_diambil')
            <div class="shopee-notice-box success">
                <x-icon name="map-pin" size="15" />
                <span>Paket sudah tiba di <strong>{{ $order->dropPoint->name }}</strong>. Siap Anda ambil!</span>
            </div>
            @endif

            <!-- Order Total Price Summary Row -->
            <div class="shopee-card-summary">
                <span style="font-size: 0.825rem;">Total {{ $order->items->sum('quantity') }} produk:</span>
                <span class="shopee-card-summary-total" style="color: #00873d;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>

            <!-- Action Buttons Row -->
            <div class="shopee-card-actions">
                <a href="{{ route('orders.show', $order) }}" class="shopee-btn-secondary">
                    Detail Pesanan
                </a>

                @if($order->status === 'menunggu_pembayaran')
                    @if(!$order->payment_proof)
                    <a href="{{ route('orders.show', $order) }}" class="shopee-btn-primary">
                        Upload Bukti Bayar
                    </a>
                    @else
                    <a href="{{ route('orders.show', $order) }}" class="shopee-btn-outline-primary">
                        Lihat Bukti Bayar
                    </a>
                    @endif
                @elseif($order->status === 'siap_diambil')
                    <a href="{{ route('orders.show', $order) }}" class="shopee-btn-primary">
                        Petunjuk Pengambilan
                    </a>
                @elseif($order->status === 'selesai')
                    <a href="{{ route('home') }}#katalog" class="shopee-btn-primary">
                        Beli Lagi
                    </a>
                @endif
            </div>

        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div style="padding: 20px 0; display: flex; justify-content: center;">
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
    </div>
    @endif
    @endif

</div>
@endsection
