@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="section" style="padding-bottom: 90px;">
<div class="container" style="max-width: 960px;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 18px;">
        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; margin-bottom: 6px;">
            <a href="{{ route('orders.index') }}" style="color: #64748b; text-decoration: none;">← Kembali ke Pesanan Saya</a>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <h1 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0; font-family: monospace; letter-spacing: 0.5px;">
                    {{ $order->order_number }}
                </h1>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                    Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
            <span class="badge status-{{ $order->status }}" style="font-size: 0.85rem; padding: 6px 16px; border-radius: 20px; font-weight: 700;">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <!-- 1. STATUS TIMELINE -->
    <div class="card mb-lg" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
        <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.9rem;">
            <x-icon name="map-pin" size="16" />
            <span>Lacak Status Pesanan</span>
        </div>
        <div class="card-body" style="padding: 16px;">
            @php
                $statusFlow = \App\Models\Order::STATUS_FLOW;
                $statusLabels = \App\Models\Order::STATUS_LABELS;
                $currentIndex = array_search($order->status, $statusFlow);
                $isCancelled = $order->status === 'dibatalkan';
                $statusIcons = ['credit-card', 'check-circle', 'cart', 'truck', 'map-pin', 'shield-check'];
            @endphp

            @if(!$isCancelled)
            <div class="timeline-scroll-container">
                <div class="timeline-steps" style="min-width: 520px; padding: 10px 0;">
                    @foreach($statusFlow as $i => $statusKey)
                    <div class="timeline-step {{ $i < $currentIndex ? 'completed' : ($i == $currentIndex ? 'active' : '') }}">
                        <div class="step-icon">
                            @if($i < $currentIndex)
                                <x-icon name="check" size="16" />
                            @elseif($i == $currentIndex)
                                <x-icon name="{{ $statusIcons[$i] ?? 'check-circle' }}" size="16" />
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <div class="step-label" style="font-size: 0.725rem;">{{ $statusLabels[$statusKey] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="alert alert-error" style="border-radius: 10px; margin: 0;">
                <span class="alert-icon"><x-icon name="x-circle" size="18" /></span>
                <span>Pesanan ini telah dibatalkan.</span>
            </div>
            @endif
        </div>
    </div>

    <!-- 2. Main Order Content -->
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;" class="checkout-grid">

        <!-- Left Column: Items & Payment Proof -->
        <div style="display: flex; flex-direction: column; gap: 16px;">

            <!-- Items Card -->
            <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <x-icon name="package" size="16" />
                        <span>Rincian Produk Sembako</span>
                    </div>
                    <span style="font-size: 0.775rem; color: #64748b;">{{ $order->items->count() }} Paket</span>
                </div>

                <div class="card-body" style="padding: 0;">
                    @foreach($order->items as $item)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 58px; height: 58px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            @if($item->package && $item->package->primary_image)
                            <img src="{{ asset('storage/' . $item->package->primary_image) }}" alt="{{ $item->package->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <x-icon name="package" size="24" style="color: #94a3b8;" />
                            @endif
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem; line-height: 1.3;">{{ $item->package ? $item->package->name : 'Paket Sembako' }}</div>
                            <div style="font-size: 0.775rem; color: #64748b; margin-top: 2px;">
                                Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                            </div>
                        </div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem; text-align: right;">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach

                    <div style="padding: 14px 16px; background: #fafafa; display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-weight: 700; color: #334155; font-size: 0.925rem;">Total Tagihan</span>
                        <span style="font-weight: 800; font-size: 1.25rem; color: #00873d;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Upload Payment Proof Card (Only for Menunggu Pembayaran) -->
            @if($order->status === 'menunggu_pembayaran')
            <div class="card" style="border-radius: 14px; border: 1.5px solid #fed7aa; box-shadow: 0 2px 8px rgba(249,115,22,0.08); overflow: hidden;">
                <div style="background: #fff7ed; padding: 12px 16px; border-bottom: 1px solid #ffedd5; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #c2410c; font-size: 0.9rem;">
                    <x-icon name="upload" size="16" />
                    <span>Petunjuk & Upload Bukti Pembayaran</span>
                </div>

                <div class="card-body" style="padding: 16px;">
                    <!-- Bank Details Card -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">Silakan transfer ke rekening resmi ISP:</div>
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                            <div>
                                <div style="font-weight: 800; font-size: 1.05rem; color: #0f172a; font-family: monospace;">1234-5678-90</div>
                                <div style="font-size: 0.775rem; color: #475569; font-weight: 600;">Bank BCA a/n Sembako ISP Official</div>
                            </div>
                            <button type="button" onclick="copyRekening('1234567890')" style="padding: 6px 12px; font-size: 0.75rem; font-weight: 700; color: #00873d; background: #dcfce7; border: 1px solid #86efac; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" id="btnCopyRek">
                                <x-icon name="copy" size="13" />
                                <span id="copyText">Salin No. Rek</span>
                            </button>
                        </div>
                    </div>

                    @if($order->payment_proof)
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <x-icon name="check-circle" size="16" style="color: #00873d;" />
                            <span style="font-size: 0.8rem; color: #166534; font-weight: 600;">Bukti transfer telah diunggah</span>
                        </div>
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" style="font-size: 0.775rem; font-weight: 700; color: #00873d; text-decoration: underline;">
                            Lihat Foto
                        </a>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('orders.upload-payment', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 0.825rem; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                {{ $order->payment_proof ? 'Ganti / Unggah Ulang Bukti Transfer' : 'Pilih Foto Bukti Transfer' }}
                            </label>
                            <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required style="font-size: 0.825rem; padding: 8px;">
                            <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 4px;">Format: JPG, PNG, atau PDF (Maks. 2 MB)</div>
                            @error('payment_proof')
                            <div class="form-error" style="color: #ef4444; font-size: 0.775rem; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-weight: 700; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <x-icon name="upload" size="15" />
                            <span>{{ $order->payment_proof ? 'Kirim Ulang Bukti' : 'Kirim Bukti Pembayaran' }}</span>
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column: Drop Point & Info -->
        <div style="display: flex; flex-direction: column; gap: 16px;">

            <!-- Drop Point Info Card -->
            <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                    <x-icon name="map-pin" size="16" />
                    <span>Drop Point Pengambilan</span>
                </div>
                <div class="card-body" style="padding: 16px;">
                    <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-bottom: 4px;">{{ $order->dropPoint->name }}</div>
                    <div style="font-size: 0.825rem; color: #475569; margin-bottom: 8px; line-height: 1.4;">
                        {{ $order->dropPoint->address }}
                    </div>
                    <div style="font-size: 0.775rem; color: #64748b; margin-bottom: 4px;">
                        ⏰ Jam Buka: <strong>{{ $order->dropPoint->operational_hours }}</strong>
                    </div>
                    <div style="font-size: 0.775rem; color: #64748b;">
                        📞 Kontak: <strong>{{ $order->dropPoint->contact_number }}</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Summary Card -->
            <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                    <x-icon name="credit-card" size="16" />
                    <span>Info Metode Pembayaran</span>
                </div>
                <div class="card-body" style="padding: 16px; display: flex; flex-direction: column; gap: 10px; font-size: 0.85rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Metode</span>
                        <span style="font-weight: 700; color: #0f172a;">{{ $order->payment_method_label }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b;">Status Bayar</span>
                        @if($order->status === 'menunggu_pembayaran')
                        <span style="font-size: 0.75rem; font-weight: 700; color: #c2410c; background: #ffedd5; padding: 2px 8px; border-radius: 6px;">Menunggu Bukti</span>
                        @elseif($order->status === 'dibatalkan')
                        <span style="font-size: 0.75rem; font-weight: 700; color: #b91c1c; background: #fee2e2; padding: 2px 8px; border-radius: 6px;">Dibatalkan</span>
                        @else
                        <span style="font-size: 0.75rem; font-weight: 700; color: #15803d; background: #dcfce7; padding: 2px 8px; border-radius: 6px;">Lunas / Terverifikasi</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</div>

@push('scripts')
<script>
function copyRekening(text) {
    navigator.clipboard.writeText(text).then(function() {
        const span = document.getElementById('copyText');
        span.innerText = 'Tersalin! ✓';
        setTimeout(() => span.innerText = 'Salin No. Rek', 2500);
    });
}
</script>
@endpush

@endsection
