@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="section" style="padding-bottom: 90px; padding-top: 16px;">
<div class="container" style="max-width: 960px;">
    <!-- Breadcrumb & Title -->
    <div style="margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Beranda</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" style="color: #64748b; text-decoration: none;">Keranjang</a>
            <span>/</span>
            <span style="color: #00873d; font-weight: 600;">Checkout</span>
        </div>
        <h1 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
            <x-icon name="credit-card" size="20" />
            <span>Checkout Pesanan</span>
        </h1>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf

        <div class="checkout-grid">

            <!-- Left: Drop Point & Payment -->
            <div style="display: flex; flex-direction: column; gap: 16px;">

                <!-- 1. Drop Point Selection (Dropdown) -->
                <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden;">
                    <div style="background: #f8fafc; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.875rem;">
                            <span style="width: 22px; height: 22px; border-radius: 50%; background: #00873d; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">1</span>
                            <span>Pilih Drop Point Pengambilan</span>
                        </div>
                        <span style="font-size: 0.7rem; color: #00873d; background: #dcfce7; padding: 2px 8px; border-radius: 12px; font-weight: 600;">Wajib</span>
                    </div>

                    <div class="card-body" style="padding: 14px;">
                        @if($errors->has('drop_point_id'))
                        <div class="alert alert-error mb-md" style="font-size: 0.8rem; padding: 8px 12px;">{{ $errors->first('drop_point_id') }}</div>
                        @endif

                        @if($dropPoints->isEmpty())
                        <div class="alert alert-warning" style="font-size: 0.8rem;">Belum ada Drop Point aktif. Silakan hubungi admin.</div>
                        @else
                        <!-- Dropdown Select -->
                        <div style="margin-bottom: 12px;">
                            <label for="drop_point_select" style="display: block; font-size: 0.775rem; font-weight: 700; color: #475569; margin-bottom: 6px;">
                                Lokasi Drop Point Reseller:
                            </label>
                            <div style="position: relative;">
                                <select name="drop_point_id" id="drop_point_select" class="form-control" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.875rem; font-weight: 600; color: #0f172a; background-color: #fff; appearance: none; -webkit-appearance: none; cursor: pointer;" onchange="updateSelectedDropPointInfo(this.value)">
                                    <option value="" disabled>-- Pilih Drop Point --</option>
                                    @foreach($dropPoints as $dp)
                                    <option value="{{ $dp->id }}"
                                            data-name="{{ $dp->name }}"
                                            data-region="{{ $dp->region }}"
                                            data-address="{{ $dp->address }}"
                                            data-hours="{{ $dp->operational_hours }}"
                                            data-contact="{{ $dp->contact_number }}"
                                            {{ ($user->drop_point_id == $dp->id || old('drop_point_id') == $dp->id) ? 'selected' : '' }}>
                                        📍 {{ $dp->name }} — {{ $dp->region }}
                                    </option>
                                    @endforeach
                                </select>
                                <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b;">
                                    ▼
                                </div>
                            </div>
                        </div>

                        <!-- Live Selected Drop Point Detail Box -->
                        <div id="selected_dp_box" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                                <span id="dp_detail_name" style="font-weight: 700; color: #0f172a; font-size: 0.875rem;">-</span>
                                <span id="dp_detail_region" style="font-size: 0.7rem; font-weight: 700; color: #00873d; background: #dcfce7; border: 1px solid #86efac; padding: 2px 8px; border-radius: 6px;">-</span>
                            </div>
                            <div id="dp_detail_address" style="font-size: 0.8rem; color: #334155; margin-bottom: 6px; line-height: 1.35;">-</div>
                            <div style="display: flex; gap: 12px; font-size: 0.725rem; color: #64748b; flex-wrap: wrap;">
                                <span>⏰ <span id="dp_detail_hours">-</span></span>
                                <span>📞 <span id="dp_detail_contact">-</span></span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Payment Method Selection -->
                <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden;">
                    <div style="background: #f8fafc; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.875rem;">
                            <span style="width: 22px; height: 22px; border-radius: 50%; background: #00873d; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">2</span>
                            <span>Pilih Metode Pembayaran</span>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 14px;">
                        @if($errors->has('payment_method'))
                        <div class="alert alert-error mb-md" style="font-size: 0.8rem; padding: 8px 12px;">{{ $errors->first('payment_method') }}</div>
                        @endif

                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <!-- Bank Transfer -->
                            <label class="payment-method-card {{ old('payment_method', 'transfer_bank') === 'transfer_bank' ? 'selected' : '' }}"
                                   id="pm-card-transfer_bank"
                                   onclick="selectPaymentMethod('transfer_bank')"
                                   style="padding: 10px 12px; border-radius: 10px;">
                                <input type="radio" name="payment_method" value="transfer_bank"
                                       {{ old('payment_method', 'transfer_bank') === 'transfer_bank' ? 'checked' : '' }}
                                       id="pm-input-transfer_bank"
                                       style="accent-color: #00873d;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-icon name="bank" size="18" />
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.85rem;">Transfer Bank Manual</div>
                                    <div style="font-size: 0.725rem; color: #64748b;">Bank BCA / Mandiri / BRI – Upload bukti setelah checkout</div>
                                </div>
                            </label>

                            <!-- QRIS -->
                            <label class="payment-method-card {{ old('payment_method') === 'qris' ? 'selected' : '' }}"
                                   id="pm-card-qris"
                                   onclick="selectPaymentMethod('qris')"
                                   style="padding: 10px 12px; border-radius: 10px;">
                                <input type="radio" name="payment_method" value="qris"
                                       {{ old('payment_method') === 'qris' ? 'checked' : '' }}
                                       id="pm-input-qris"
                                       style="accent-color: #00873d;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-icon name="qr-code" size="18" />
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.85rem;">QRIS (E-Wallet & Mobile Banking)</div>
                                    <div style="font-size: 0.725rem; color: #64748b;">Gopay, OVO, Dana, ShopeePay, BCA Mobile</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary -->
            <div style="position: sticky; top: 84px;">
                <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
                    <div style="background: #f8fafc; padding: 12px 14px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                        <x-icon name="receipt" size="16" />
                        <span>Ringkasan Pesanan</span>
                    </div>

                    <div class="card-body" style="padding: 14px;">
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px;">
                            @foreach($cartItems as $item)
                            <div style="display: flex; justify-content: space-between; font-size: 0.825rem; color: #475569;">
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;">{{ $item['package']->name }} <small style="color:#94a3b8;">×{{ $item['qty'] }}</small></span>
                                <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div style="border-top: 1.5px dashed #e2e8f0; padding-top: 12px; margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">Total Bayar</span>
                                <span style="font-size: 1.25rem; font-weight: 800; color: #00873d;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px; font-size: 0.9rem; font-weight: 700; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,135,61,0.22);" id="submit-btn">
                            <x-icon name="check-circle" size="16" />
                            <span>Buat Pesanan & Bayar</span>
                        </button>

                        <div style="font-size: 0.7rem; color: #94a3b8; text-align: center; margin-top: 8px;">
                            🔒 Pembayaran aman & verifikasi cepat
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
function updateSelectedDropPointInfo(val) {
    const select = document.getElementById('drop_point_select');
    if (!select) return;
    const selectedOpt = select.options[select.selectedIndex];
    if (selectedOpt) {
        document.getElementById('dp_detail_name').innerText = selectedOpt.dataset.name || "-";
        document.getElementById('dp_detail_region').innerText = selectedOpt.dataset.region || "-";
        document.getElementById('dp_detail_address').innerText = selectedOpt.dataset.address || "-";
        document.getElementById('dp_detail_hours').innerText = selectedOpt.dataset.hours || "-";
        document.getElementById('dp_detail_contact').innerText = selectedOpt.dataset.contact || "-";
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedDropPointInfo();
});

// Prevent double submission
document.getElementById('checkout-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span>Memproses Pesanan...</span>';
});
</script>
@endpush

@endsection
