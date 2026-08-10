@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="section">
<div class="container-sm">
    <h1 style="margin-bottom: var(--space-sm); display: flex; align-items: center; gap: 10px;">
        <x-icon name="receipt" size="26" />
        <span>Checkout Pesanan</span>
    </h1>
    <p class="text-muted mb-xl">Tinjau pesanan Anda, pilih Drop Point, dan tentukan metode pembayaran.</p>

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf

        <div style="display: grid; gap: var(--space-xl);">

            <!-- Drop Point Selection -->
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="map-pin" size="16" />
                    <span>Pilih Drop Point Pengambilan</span>
                </div>
                <div class="card-body">
                    @if($errors->has('drop_point_id'))
                    <div class="alert alert-error mb-md">{{ $errors->first('drop_point_id') }}</div>
                    @endif

                    @if($dropPoints->isEmpty())
                    <div class="alert alert-warning">Belum ada Drop Point aktif. Hubungi admin.</div>
                    @else
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        @foreach($dropPoints as $dp)
                        <label style="display: flex; align-items: flex-start; gap: var(--space-md); padding: var(--space-md); border: 2px solid var(--gray-200); border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition);"
                               onclick="this.querySelectorAll('input')[0].checked=true; highlightDropPoint({{ $dp->id }});"
                               id="dp-label-{{ $dp->id }}"
                               class="{{ ($user->drop_point_id == $dp->id) ? 'selected-dp' : '' }}">
                            <input type="radio" name="drop_point_id" value="{{ $dp->id }}"
                                   {{ ($user->drop_point_id == $dp->id || old('drop_point_id') == $dp->id) ? 'checked' : '' }}
                                   style="margin-top: 4px; accent-color: var(--primary-500);"
                                   id="dp-{{ $dp->id }}">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--gray-800); margin-bottom: 2px;">{{ $dp->name }}</div>
                                <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 3px;">{{ $dp->address }}</div>
                                <div style="font-size: 0.8rem; color: var(--gray-500); display: flex; gap: 12px; flex-wrap: wrap;">
                                    <span>Jam: {{ $dp->operational_hours }}</span>
                                    <span>Kontak: {{ $dp->contact_number }}</span>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--primary-600); font-weight: 500; margin-top: 4px;">Wilayah: {{ $dp->region }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="credit-card" size="16" />
                    <span>Metode Pembayaran</span>
                </div>
                <div class="card-body">
                    @if($errors->has('payment_method'))
                    <div class="alert alert-error mb-md">{{ $errors->first('payment_method') }}</div>
                    @endif
                    <div class="grid grid-2">
                        <label style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border: 2px solid var(--gray-200); border-radius: var(--radius-md); cursor: pointer; transition: border-color var(--transition);">
                            <input type="radio" name="payment_method" value="transfer_bank"
                                   {{ old('payment_method', 'transfer_bank') == 'transfer_bank' ? 'checked' : '' }}
                                   style="accent-color: var(--primary-500);">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; background: var(--primary-50); color: var(--primary-600); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                                    <x-icon name="bank" size="18" />
                                </div>
                                <div>
                                    <div style="font-weight: 600;">Transfer Bank</div>
                                    <div style="font-size: 0.8rem; color: var(--gray-500);">BCA / Mandiri / BRI</div>
                                </div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; gap: var(--space-md); padding: var(--space-md); border: 2px solid var(--gray-200); border-radius: var(--radius-md); cursor: pointer; transition: border-color var(--transition);">
                            <input type="radio" name="payment_method" value="qris"
                                   {{ old('payment_method') == 'qris' ? 'checked' : '' }}
                                   style="accent-color: var(--primary-500);">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; background: var(--primary-50); color: var(--primary-600); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                                    <x-icon name="qr-code" size="18" />
                                </div>
                                <div>
                                    <div style="font-weight: 600;">QRIS</div>
                                    <div style="font-size: 0.8rem; color: var(--gray-500);">Semua E-Wallet & Bank</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="alert alert-info mt-md">
                        <span class="alert-icon"><x-icon name="info" size="16" /></span>
                        <span>Setelah membuat pesanan, silakan unggah bukti transfer pembayaran Anda untuk diverifikasi oleh admin.</span>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="clipboard" size="16" />
                    <span>Ringkasan Pesanan</span>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem;">
                        <div>
                            <span style="font-weight: 600;">{{ $item['package']->name }}</span>
                            <span style="color: var(--gray-400);"> × {{ $item['qty'] }}</span>
                        </div>
                        <span style="color: var(--gray-700); font-weight: 600;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div style="border-top: 2px solid var(--gray-100); padding-top: var(--space-md); margin-top: var(--space-md); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.1rem; font-weight: 700;">Total Pembayaran</span>
                        <span style="font-size: 1.4rem; font-weight: 800; color: var(--primary-600);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-xl" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;" id="submit-btn">
                <x-icon name="check-circle" size="18" />
                <span>Buat Pesanan & Lanjutkan Pembayaran</span>
            </button>

        </div>
    </form>
</div>
</div>

@push('scripts')
<script>
function highlightDropPoint(id) {
    document.querySelectorAll('[id^="dp-label-"]').forEach(el => {
        el.style.borderColor = 'var(--gray-200)';
        el.style.background = '#fff';
    });
    const label = document.getElementById('dp-label-' + id);
    if (label) {
        label.style.borderColor = 'var(--primary-500)';
        label.style.background = 'var(--primary-50)';
    }
}

// Initialize selected state
document.querySelectorAll('input[name="drop_point_id"]').forEach(radio => {
    if (radio.checked) highlightDropPoint(radio.value);
    radio.addEventListener('change', () => highlightDropPoint(radio.value));
});

// Prevent double submit
document.getElementById('checkout-form').addEventListener('submit', function() {
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('submit-btn').textContent = 'Memproses Pesanan...';
});
</script>
@endpush

@endsection
