@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="section" style="padding-bottom: 90px; padding-top: 14px;">
<div class="container" style="max-width: 960px; padding-left: 12px; padding-right: 12px; box-sizing: border-box; width: 100%;">
    <!-- Breadcrumb & Title -->
    <div style="margin-bottom: 14px;">
        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Beranda</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" style="color: #64748b; text-decoration: none;">Keranjang</a>
            <span>/</span>
            <span style="color: #00873d; font-weight: 600;">Checkout</span>
        </div>
        <h1 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
            <x-icon name="credit-card" size="18" />
            <span>Checkout Pesanan</span>
        </h1>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form" style="width: 100%; box-sizing: border-box;">
        @csrf

        <div class="checkout-grid" style="width: 100%; box-sizing: border-box;">

            <!-- Left: Drop Point & Payment -->
            <div style="display: flex; flex-direction: column; gap: 14px; width: 100%; box-sizing: border-box; min-width: 0;">

                <!-- 1. Drop Point Selection (Dropdown) -->
                <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; width: 100%; box-sizing: border-box;">
                    <div style="background: #f8fafc; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.85rem;">
                            <span style="width: 20px; height: 20px; border-radius: 50%; background: #00873d; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.675rem; font-weight: 800; flex-shrink: 0;">1</span>
                            <span>Pilih Drop Point Pengambilan</span>
                        </div>
                        <span style="font-size: 0.675rem; color: #00873d; background: #dcfce7; padding: 2px 6px; border-radius: 10px; font-weight: 700;">Wajib</span>
                    </div>

                    <div class="card-body" style="padding: 12px; width: 100%; box-sizing: border-box;">
                        @if($errors->has('drop_point_id'))
                        <div class="alert alert-error mb-md" style="font-size: 0.8rem; padding: 8px 12px;">{{ $errors->first('drop_point_id') }}</div>
                        @endif

                        @if($dropPoints->isEmpty())
                        <div class="alert alert-warning" style="font-size: 0.8rem;">Belum ada Drop Point aktif. Silakan hubungi admin.</div>
                        @else
                        <!-- Custom Dropdown Selector -->
                        <div style="margin-bottom: 10px; position: relative; width: 100%; box-sizing: border-box;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">
                                Lokasi Drop Point Reseller:
                            </label>
                            
                            <input type="hidden" name="drop_point_id" id="drop_point_input" value="{{ $user->drop_point_id ?? ($dropPoints->first()->id ?? '') }}">

                            <!-- Dropdown Trigger Button -->
                            <div id="dp_dropdown_trigger" onclick="toggleDpDropdown()" style="width: 100%; box-sizing: border-box; padding: 9px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 6px; transition: all 0.15s ease; overflow: hidden;">
                                <div style="display: flex; align-items: center; gap: 6px; min-width: 0; flex: 1; overflow: hidden;">
                                    <span style="font-size: 0.95rem; flex-shrink: 0;">📍</span>
                                    <span id="dp_trigger_label" style="font-size: 0.825rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">
                                        Pilih Drop Point...
                                    </span>
                                </div>
                                <span id="dp_dropdown_arrow" style="font-size: 0.65rem; color: #64748b; flex-shrink: 0; transition: transform 0.2s;">▼</span>
                            </div>

                            <!-- Dropdown Menu Options List -->
                            <div id="dp_dropdown_menu" style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; width: 100%; box-sizing: border-box; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 10px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.12), 0 8px 10px -6px rgba(0,0,0,0.06); z-index: 100; max-height: 220px; overflow-y: auto; padding: 4px;">
                                @foreach($dropPoints as $dp)
                                <div class="dp-menu-option" 
                                     id="dp-opt-{{ $dp->id }}"
                                     onclick="selectCustomDropPoint({{ json_encode($dp) }})"
                                     style="padding: 8px 10px; border-radius: 6px; cursor: pointer; transition: background 0.15s; border-bottom: 1px solid #f8fafc; box-sizing: border-box; width: 100%;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 4px; margin-bottom: 2px;">
                                        <span style="font-weight: 700; font-size: 0.825rem; color: #0f172a;">{{ $dp->name }}</span>
                                        <span style="font-size: 0.65rem; font-weight: 700; color: #00873d; background: #dcfce7; padding: 2px 6px; border-radius: 4px; flex-shrink: 0;">{{ $dp->region }}</span>
                                    </div>
                                    <div style="font-size: 0.725rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $dp->address }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Live Selected Drop Point Detail Box -->
                        <div id="selected_dp_box" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px; width: 100%; box-sizing: border-box; overflow: hidden; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 6px; margin-bottom: 4px;">
                                <span id="dp_detail_name" style="font-weight: 700; color: #0f172a; font-size: 0.825rem; line-height: 1.3; word-break: break-word; flex: 1;">-</span>
                                <span id="dp_detail_region" style="font-size: 0.65rem; font-weight: 700; color: #00873d; background: #dcfce7; border: 1px solid #86efac; padding: 2px 6px; border-radius: 4px; flex-shrink: 0; white-space: nowrap;">-</span>
                            </div>
                            <div id="dp_detail_address" style="font-size: 0.775rem; color: #334155; margin-bottom: 6px; line-height: 1.35; word-break: break-word;">-</div>
                            <div style="display: flex; gap: 10px; font-size: 0.7rem; color: #64748b; flex-wrap: wrap;">
                                <span>⏰ <span id="dp_detail_hours">-</span></span>
                                <span>📞 <span id="dp_detail_contact">-</span></span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Payment Method Selection -->
                <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; width: 100%; box-sizing: border-box;">
                    <div style="background: #f8fafc; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.85rem;">
                            <span style="width: 20px; height: 20px; border-radius: 50%; background: #00873d; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.675rem; font-weight: 800; flex-shrink: 0;">2</span>
                            <span>Pilih Metode Pembayaran</span>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 12px; width: 100%; box-sizing: border-box;">
                        @if($errors->has('payment_method'))
                        <div class="alert alert-error mb-md" style="font-size: 0.8rem; padding: 8px 12px;">{{ $errors->first('payment_method') }}</div>
                        @endif

                        <div style="display: flex; flex-direction: column; gap: 8px; width: 100%; box-sizing: border-box;">
                            <!-- Bank Transfer -->
                            <label class="payment-method-card {{ old('payment_method', 'transfer_bank') === 'transfer_bank' ? 'selected' : '' }}"
                                   id="pm-card-transfer_bank"
                                   onclick="selectPaymentMethod('transfer_bank')"
                                   style="padding: 10px; border-radius: 8px; width: 100%; box-sizing: border-box; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="payment_method" value="transfer_bank"
                                       {{ old('payment_method', 'transfer_bank') === 'transfer_bank' ? 'checked' : '' }}
                                       id="pm-input-transfer_bank"
                                       style="accent-color: #00873d; flex-shrink: 0;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-icon name="bank" size="16" />
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.825rem;">Transfer Bank Manual</div>
                                    <div style="font-size: 0.7rem; color: #64748b; line-height: 1.25;">BCA / Mandiri / BRI (Upload bukti transfer)</div>
                                </div>
                            </label>

                            <!-- QRIS -->
                            <label class="payment-method-card {{ old('payment_method') === 'qris' ? 'selected' : '' }}"
                                   id="pm-card-qris"
                                   onclick="selectPaymentMethod('qris')"
                                   style="padding: 10px; border-radius: 8px; width: 100%; box-sizing: border-box; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="payment_method" value="qris"
                                       {{ old('payment_method') === 'qris' ? 'checked' : '' }}
                                       id="pm-input-qris"
                                       style="accent-color: #00873d; flex-shrink: 0;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <x-icon name="qr-code" size="16" />
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.825rem;">QRIS (E-Wallet & M-Banking)</div>
                                    <div style="font-size: 0.7rem; color: #64748b; line-height: 1.25;">Gopay, OVO, Dana, ShopeePay, BCA Mobile</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary -->
            <div style="position: sticky; top: 84px; width: 100%; box-sizing: border-box;">
                <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.04); width: 100%; box-sizing: border-box; overflow: hidden;">
                    <div style="background: #f8fafc; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.85rem;">
                        <x-icon name="receipt" size="15" />
                        <span>Ringkasan Pesanan</span>
                    </div>

                    <div class="card-body" style="padding: 12px; width: 100%; box-sizing: border-box;">
                        <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px;">
                            @foreach($cartItems as $item)
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #475569;">
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;">{{ $item['package']->name }} <small style="color:#94a3b8;">×{{ $item['qty'] }}</small></span>
                                <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div style="border-top: 1.5px dashed #e2e8f0; padding-top: 10px; margin-bottom: 14px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-size: 0.85rem; font-weight: 700; color: #1e293b;">Total Bayar</span>
                                <span style="font-size: 1.2rem; font-weight: 800; color: #00873d;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 0.875rem; font-weight: 700; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,135,61,0.22);" id="submit-btn">
                            <x-icon name="check-circle" size="15" />
                            <span>Buat Pesanan & Bayar</span>
                        </button>

                        <div style="font-size: 0.675rem; color: #94a3b8; text-align: center; margin-top: 6px;">
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
const dpDropdownMenu = document.getElementById('dp_dropdown_menu');
const dpDropdownTrigger = document.getElementById('dp_dropdown_trigger');
const dpDropdownArrow = document.getElementById('dp_dropdown_arrow');
const dpInput = document.getElementById('drop_point_input');
const dpTriggerLabel = document.getElementById('dp_trigger_label');

function toggleDpDropdown() {
    if (dpDropdownMenu.style.display === 'none' || !dpDropdownMenu.style.display) {
        dpDropdownMenu.style.display = 'block';
        dpDropdownArrow.style.transform = 'rotate(180deg)';
        dpDropdownTrigger.style.borderColor = '#00873d';
    } else {
        closeDpDropdown();
    }
}

function closeDpDropdown() {
    dpDropdownMenu.style.display = 'none';
    dpDropdownArrow.style.transform = 'rotate(0deg)';
    dpDropdownTrigger.style.borderColor = '#cbd5e1';
}

function selectCustomDropPoint(dp) {
    if (!dp) return;
    dpInput.value = dp.id;
    dpTriggerLabel.innerText = dp.name + ' (' + dp.region + ')';
    
    // Update live preview detail box
    document.getElementById('dp_detail_name').innerText = dp.name;
    document.getElementById('dp_detail_region').innerText = dp.region;
    document.getElementById('dp_detail_address').innerText = dp.address;
    document.getElementById('dp_detail_hours').innerText = dp.operational_hours;
    document.getElementById('dp_detail_contact').innerText = dp.contact_number;

    // Highlight active option in list
    document.querySelectorAll('.dp-menu-option').forEach(opt => {
        opt.style.background = '#ffffff';
    });
    const selectedOpt = document.getElementById('dp-opt-' + dp.id);
    if (selectedOpt) selectedOpt.style.background = '#f0fdf4';

    closeDpDropdown();
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    if (dpDropdownTrigger && dpDropdownMenu && !dpDropdownTrigger.contains(e.target) && !dpDropdownMenu.contains(e.target)) {
        closeDpDropdown();
    }
});

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
    const dropPointsList = @json($dropPoints);
    const initialId = dpInput.value;
    const initialDp = dropPointsList.find(d => d.id == initialId) || dropPointsList[0];
    if (initialDp) {
        selectCustomDropPoint(initialDp);
    }
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
