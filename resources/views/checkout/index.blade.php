@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="section" style="padding-bottom: 90px;">
<div class="container" style="max-width: 960px;">
    <!-- Breadcrumb & Title -->
    <div style="margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Beranda</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" style="color: #64748b; text-decoration: none;">Keranjang</a>
            <span>/</span>
            <span style="color: #00873d; font-weight: 600;">Checkout</span>
        </div>
        <h1 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
            <x-icon name="credit-card" size="24" />
            <span>Checkout & Konfirmasi Pesanan</span>
        </h1>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf

        <div class="checkout-grid">

            <!-- Left: Drop Point & Payment -->
            <div style="display: flex; flex-direction: column; gap: 20px;">

                <!-- 1. Drop Point Selection -->
                <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
                    <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.925rem;">
                            <span style="width: 24px; height: 24px; border-radius: 50%; background: #00873d; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">1</span>
                            <span>Pilih Drop Point Pengambilan Sembako</span>
                        </div>
                        <span style="font-size: 0.75rem; color: #64748b; background: #e2e8f0; padding: 2px 8px; border-radius: 12px;">Wajib</span>
                    </div>

                    <div class="card-body" style="padding: 16px;">
                        @if($errors->has('drop_point_id'))
                        <div class="alert alert-error mb-md">{{ $errors->first('drop_point_id') }}</div>
                        @endif

                        @if($dropPoints->isEmpty())
                        <div class="alert alert-warning">Belum ada Drop Point aktif. Silakan hubungi admin.</div>
                        @else
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($dropPoints as $dp)
                            @php $isSelected = ($user->drop_point_id == $dp->id || old('drop_point_id') == $dp->id); @endphp
                            <label class="drop-point-select-card {{ $isSelected ? 'selected' : '' }}"
                                   id="dp-card-{{ $dp->id }}"
                                   onclick="selectDropPoint({{ $dp->id }})">
                                <input type="radio" name="drop_point_id" value="{{ $dp->id }}"
                                       {{ $isSelected ? 'checked' : '' }}
                                       id="dp-input-{{ $dp->id }}"
                                       style="margin-top: 4px; accent-color: #00873d;">
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 3px;">
                                        <span style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">{{ $dp->name }}</span>
                                        <span style="font-size: 0.725rem; font-weight: 600; color: #00873d; background: #dcfce7; padding: 2px 8px; border-radius: 6px;">{{ $dp->region }}</span>
                                    </div>
                                    <div style="font-size: 0.825rem; color: #475569; margin-bottom: 6px; line-height: 1.4;">
                                        <x-icon name="map-pin" size="13" style="display: inline; vertical-align: -2px; color: #64748b;" />
                                        <span>{{ $dp->address }}</span>
                                    </div>
                                    <div style="display: flex; gap: 14px; font-size: 0.75rem; color: #64748b; flex-wrap: wrap;">
                                        <span>⏰ {{ $dp->operational_hours }}</span>
                                        <span>📞 {{ $dp->contact_number }}</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Payment Method Selection -->
                <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;">
                    <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.925rem;">
                            <span style="width: 24px; height: 24px; border-radius: 50%; background: #00873d; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
                            <span>Pilih Metode Pembayaran</span>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 16px;">
                        @if($errors->has('payment_method'))
                        <div class="alert alert-error mb-md">{{ $errors->first('payment_method') }}</div>
                        @endif

                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <!-- Bank Transfer -->
                            <label class="payment-method-card {{ old('payment_method', 'transfer_bank') === 'transfer_bank' ? 'selected' : '' }}"
                                   id="pm-card-transfer_bank"
                                   onclick="selectPaymentMethod('transfer_bank')">
                                <input type="radio" name="payment_method" value="transfer_bank"
                                       {{ old('payment_method', 'transfer_bank') === 'transfer_bank' ? 'checked' : '' }}
                                       id="pm-input-transfer_bank"
                                       style="accent-color: #00873d;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-icon name="bank" size="20" />
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">Transfer Bank Manual</div>
                                    <div style="font-size: 0.775rem; color: #64748b;">Bank BCA / Mandiri / BRI – Upload bukti transfer setelah checkout</div>
                                </div>
                            </label>

                            <!-- QRIS -->
                            <label class="payment-method-card {{ old('payment_method') === 'qris' ? 'selected' : '' }}"
                                   id="pm-card-qris"
                                   onclick="selectPaymentMethod('qris')">
                                <input type="radio" name="payment_method" value="qris"
                                       {{ old('payment_method') === 'qris' ? 'checked' : '' }}
                                       id="pm-input-qris"
                                       style="accent-color: #00873d;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-icon name="qr-code" size="20" />
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">QRIS (E-Wallet & Mobile Banking)</div>
                                    <div style="font-size: 0.775rem; color: #64748b;">Gopay, OVO, Dana, ShopeePay, BCA Mobile</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary -->
            <div style="position: sticky; top: 84px;">
                <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div style="background: #f8fafc; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                        <x-icon name="receipt" size="17" />
                        <span>Ringkasan Pesanan</span>
                    </div>

                    <div class="card-body" style="padding: 16px;">
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                            @foreach($cartItems as $item)
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #475569;">
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;">{{ $item['package']->name }} <small style="color:#94a3b8;">×{{ $item['qty'] }}</small></span>
                                <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div style="border-top: 1.5px dashed #e2e8f0; padding-top: 14px; margin-bottom: 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">Total Pembayaran</span>
                                <span style="font-size: 1.35rem; font-weight: 800; color: #00873d;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 0.95rem; font-weight: 700; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,135,61,0.25);" id="submit-btn">
                            <x-icon name="check-circle" size="17" />
                            <span>Buat Pesanan & Bayar</span>
                        </button>

                        <div style="font-size: 0.725rem; color: #94a3b8; text-align: center; margin-top: 10px;">
                            🔒 Pembayaran aman & verifikasi langsung oleh admin
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
</div>

@push('scripts')
<script>
function selectDropPoint(id) {
    document.querySelectorAll('.drop-point-select-card').forEach(c => c.classList.remove('selected'));
    const target = document.getElementById('dp-card-' + id);
    if (target) {
        target.classList.add('selected');
        const input = document.getElementById('dp-input-' + id);
        if (input) input.checked = true;
    }
}

function selectPaymentMethod(method) {
    document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
    const target = document.getElementById('pm-card-' + method);
    if (target) {
        target.classList.add('selected');
        const input = document.getElementById('pm-input-' + method);
        if (input) input.checked = true;
    }
}

// Prevent double submission
document.getElementById('checkout-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span>Memproses Pesanan...</span>';
});
</script>
@endpush

@endsection
