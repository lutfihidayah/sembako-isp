@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="section">
<div class="container">
    <div style="margin-bottom: var(--space-md);">
        <a href="{{ route('orders.index') }}" style="color: var(--gray-500); font-size: 0.875rem;">← Kembali ke Pesanan Saya</a>
    </div>

    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: var(--space-xl); flex-wrap: wrap; gap: var(--space-md);">
        <div>
            <h1 style="font-size: 1.5rem; font-family: monospace; color: var(--primary-700);">{{ $order->order_number }}</h1>
            <p class="text-muted">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <span class="badge status-{{ $order->status }}" style="font-size: 0.95rem; padding: 8px 18px;">{{ $order->status_label }}</span>
    </div>

    <!-- STATUS TIMELINE -->
    <div class="card mb-xl">
        <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
            <x-icon name="map-pin" size="16" />
            <span>Tahapan Status Pesanan</span>
        </div>
        <div class="card-body">
            @php
                $statusFlow = \App\Models\Order::STATUS_FLOW;
                $statusLabels = \App\Models\Order::STATUS_LABELS;
                $currentIndex = array_search($order->status, $statusFlow);
                $isCancelled = $order->status === 'dibatalkan';
                $statusIcons = ['credit-card', 'check-circle', 'cart', 'truck', 'map-pin', 'shield-check'];
            @endphp

            @if(!$isCancelled)
            <div class="timeline-steps">
                @foreach($statusFlow as $i => $statusKey)
                <div class="timeline-step {{ $i < $currentIndex ? 'completed' : ($i == $currentIndex ? 'active' : '') }}">
                    <div class="step-icon">
                        @if($i < $currentIndex)
                            <x-icon name="check" size="16" />
                        @elseif($i == $currentIndex)
                            <x-icon name="{{ $statusIcons[$i] ?? 'check-circle' }}" size="18" />
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="step-label">{{ $statusLabels[$statusKey] }}</div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-error">
                <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                <span>Pesanan ini telah dibatalkan.</span>
            </div>
            @endif

            <!-- Status Logs -->
            @if($order->statusLogs->isNotEmpty())
            <div style="margin-top: var(--space-xl); border-top: 1px solid var(--gray-100); padding-top: var(--space-lg);">
                <h4 style="margin-bottom: var(--space-md); color: var(--gray-700); font-size: 0.95rem;">Riwayat Perubahan Status</h4>
                @foreach($order->statusLogs as $log)
                <div style="display: flex; gap: var(--space-md); margin-bottom: 8px;">
                    <div style="font-size: 0.8rem; color: var(--gray-400); min-width: 140px;">
                        {{ $log->created_at->format('d M Y H:i') }} WIB
                    </div>
                    <div>
                        <span class="badge status-{{ $log->status }}" style="font-size: 0.75rem;">{{ $statusLabels[$log->status] ?? $log->status }}</span>
                        @if($log->note)
                        <div style="font-size: 0.825rem; color: var(--gray-600); margin-top: 2px;">{{ $log->note }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="layout-split-sidebar">

        <!-- Left: Items & Payment -->
        <div style="display: flex; flex-direction: column; gap: var(--space-lg);">

            <!-- Items -->
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="package" size="16" />
                    <span>Daftar Item Pesanan</span>
                </div>
                <div class="card-body" style="padding: 0;">
                    @foreach($order->items as $item)
                    <div style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md) var(--space-lg); border-bottom: 1px solid var(--gray-50);">
                        <div style="width: 56px; height: 56px; border-radius: var(--radius-md); overflow: hidden; background: var(--primary-50); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if($item->package && $item->package->primary_image)
                            <img src="{{ asset('storage/' . $item->package->primary_image) }}" alt="{{ $item->package->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <x-icon name="package" size="24" />
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: var(--gray-800);">{{ $item->package ? $item->package->name : 'Paket dihapus' }}</div>
                            <div style="font-size: 0.8rem; color: var(--gray-500);">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</div>
                        </div>
                        <div style="font-weight: 700; color: var(--gray-800);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                    <div style="padding: var(--space-md) var(--space-lg); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; font-size: 1rem;">Total Pembayaran</span>
                        <span style="font-weight: 800; font-size: 1.2rem; color: var(--primary-600);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Proof Upload -->
            @if($order->status === 'menunggu_pembayaran')
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="upload" size="16" />
                    <span>Upload Bukti Pembayaran</span>
                </div>
                <div class="card-body">
                    @if($order->expired_at && now()->gt($order->expired_at))
                    <div class="alert alert-error">
                        <span class="alert-icon"><x-icon name="alert-triangle" size="18" /></span>
                        <span>Pesanan ini telah kedaluwarsa. Batas waktu pembayaran telah lewat.</span>
                    </div>
                    @else
                    @if($order->expired_at)
                    <div class="alert alert-warning mb-md">
                        <span class="alert-icon"><x-icon name="clock" size="18" /></span>
                        <span>Batas waktu pembayaran: <strong>{{ $order->expired_at->format('d M Y, H:i') }} WIB</strong> ({{ $order->expired_at->diffForHumans() }})</span>
                    </div>
                    @endif

                    @if($order->payment_method === 'transfer_bank')
                    <div class="alert alert-info mb-md">
                        <span class="alert-icon"><x-icon name="bank" size="18" /></span>
                        <div>
                            <div>Transfer ke rekening resmi ISP:</div>
                            <strong>Bank BCA: 1234-5678-90 a/n Sembako ISP</strong><br>
                            Jumlah Tagihan: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mb-md">
                        <span class="alert-icon"><x-icon name="qr-code" size="18" /></span>
                        <div>
                            Scan kode QRIS dan lakukan transfer senilai: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endif

                    @if($order->payment_proof)
                    <div style="margin-bottom: var(--space-md);">
                        <p class="text-sm text-muted">Bukti yang telah diunggah:</p>
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-ghost btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                            <x-icon name="paperclip" size="14" />
                            <span>Lihat Bukti Pembayaran</span>
                        </a>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('orders.upload-payment', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Pilih Berkas Bukti Transfer <span class="required">*</span></label>
                            <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-hint">Format yang didukung: JPG, PNG, atau PDF. Ukuran maks 2 MB.</div>
                            @error('payment_proof')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                            <x-icon name="upload" size="16" />
                            <span>Unggah Bukti</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- Right: Info -->
        <div style="display: flex; flex-direction: column; gap: var(--space-md);">

            <!-- Drop Point Info -->
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="map-pin" size="16" />
                    <span>Drop Point Pengambilan</span>
                </div>
                <div class="card-body">
                    <div style="font-weight: 700; font-size: 1rem; margin-bottom: 6px;">{{ $order->dropPoint->name }}</div>
                    <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 6px;">{{ $order->dropPoint->address }}</div>
                    <div style="font-size: 0.825rem; color: var(--gray-500); margin-bottom: 4px;">Jam: {{ $order->dropPoint->operational_hours }}</div>
                    <div style="font-size: 0.825rem; color: var(--gray-500);">Kontak: {{ $order->dropPoint->contact_number }}</div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="credit-card" size="16" />
                    <span>Informasi Pembayaran</span>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.875rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span class="text-muted">Metode</span>
                            <span style="font-weight: 600;">{{ $order->payment_method_label }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span class="text-muted">Status Bayar</span>
                            @if($order->status === 'menunggu_pembayaran')
                            <span class="badge badge-warning">Menunggu Pembayaran</span>
                            @elseif($order->status === 'dibatalkan')
                            <span class="badge badge-danger">Dibatalkan</span>
                            @else
                            <span class="badge badge-success">Terverifikasi</span>
                            @endif
                        </div>
                        @if($order->payment_proof && $order->status !== 'menunggu_pembayaran')
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-muted">Bukti Bayar</span>
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" style="color: var(--primary-600); font-size: 0.8rem; font-weight: 500;">Lihat Berkas →</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
