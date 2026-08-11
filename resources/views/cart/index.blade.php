@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="section" style="padding-bottom: 90px;">
<div class="container" style="max-width: 1040px;">
    <!-- Breadcrumb & Title -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <div>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">
                <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">Beranda</a>
                <span>/</span>
                <span style="color: #00873d; font-weight: 600;">Keranjang</span>
            </div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <x-icon name="cart" size="24" />
                <span>Keranjang Belanja</span>
            </h1>
        </div>
        @if(!empty($cartItems))
        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-ghost btn-sm" style="color: #ef4444; border: 1px solid #fecaca; background: #fff; font-size: 0.775rem; border-radius: 8px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')">
                <x-icon name="trash" size="13" />
                <span>Kosongkan</span>
            </button>
        </form>
        @endif
    </div>

    @if(empty($cartItems))
    <div class="card" style="text-align: center; padding: 48px 20px; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #f0fdf4; color: #00873d; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <x-icon name="cart" size="36" />
        </div>
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Keranjang Belanja Anda Kosong</h3>
        <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 20px; max-width: 380px; margin-left: auto; margin-right: auto;">Belum ada paket sembako yang ditambahkan. Yuk temukan paket hemat kebutuhan Anda!</p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; border-radius: 10px;">
            Lihat Katalog Paket Sembako
        </a>
    </div>
    @else
    <div class="cart-page-wrapper">

        <!-- Cart Items List -->
        <div>
            <div class="card" style="border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 700; color: #475569; display: flex; align-items: center; justify-content: space-between;">
                    <span>Daftar Paket Sembako ({{ count($cartItems) }} Item)</span>
                    <span style="font-size: 0.775rem; color: #94a3b8; font-weight: 500;">Periksa jumlah & subtotal</span>
                </div>

                <div class="card-body" style="padding: 0;">
                    @foreach($cartItems as $item)
                    <div class="cart-item-card">
                        <!-- Image -->
                        <div class="cart-item-img">
                            @if($item['package']->primary_image)
                            <img src="{{ asset('storage/' . $item['package']->primary_image) }}" alt="{{ $item['package']->name }}">
                            @else
                            <x-icon name="package" size="28" style="color: #94a3b8;" />
                            @endif
                        </div>

                        <!-- Info & Actions -->
                        <div class="cart-item-details">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                <h3 class="cart-item-title">{{ $item['package']->name }}</h3>
                                <!-- Remove Button -->
                                <form method="POST" action="{{ route('cart.remove', $item['package']->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="width: 28px; height: 28px; border-radius: 6px; border: none; background: #fee2e2; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s;" title="Hapus paket ini">
                                        <x-icon name="trash" size="13" />
                                    </button>
                                </form>
                            </div>

                            <div class="cart-item-price">
                                Rp {{ number_format($item['package']->price, 0, ',', '.') }} <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ paket</span>
                            </div>

                            <div class="cart-item-actions">
                                <!-- Quantity Stepper -->
                                <form method="POST" action="{{ route('cart.update', $item['package']->id) }}">
                                    @csrf @method('PATCH')
                                    <div class="cart-qty-group">
                                        <button type="button" class="cart-qty-btn" onclick="decrementQty(this)">−</button>
                                        <input type="number" name="quantity" class="cart-qty-input" value="{{ $item['quantity'] }}" min="1" max="{{ $item['package']->stock }}" onchange="this.form.submit()">
                                        <button type="button" class="cart-qty-btn" onclick="incrementQty(this)">+</button>
                                    </div>
                                </form>

                                <!-- Subtotal -->
                                <div style="text-align: right;">
                                    <span style="font-size: 0.725rem; color: #64748b; display: block;">Subtotal:</span>
                                    <span class="cart-subtotal-val">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tambah Produk Lain -->
            <div style="margin-top: 14px; text-align: left;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #00873d; font-size: 0.85rem; font-weight: 700; text-decoration: none; padding: 6px 12px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
                    <span>+ Tambah Paket Sembako Lain</span>
                </a>
            </div>
        </div>

        <!-- Order Summary Card (Desktop & Tablet) -->
        <div style="position: sticky; top: 84px;">
            <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div style="background: #f8fafc; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                    <x-icon name="receipt" size="17" />
                    <span>Ringkasan Belanja</span>
                </div>
                <div class="card-body" style="padding: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                        @foreach($cartItems as $item)
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #475569;">
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;">{{ $item['package']->name }} <small style="color:#94a3b8;">×{{ $item['quantity'] }}</small></span>
                            <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="border-top: 1.5px dashed #e2e8f0; padding-top: 14px; margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <span style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">Total Belanja</span>
                            <span style="font-size: 1.35rem; font-weight: 800; color: #00873d;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">*Harga sudah termasuk seluruh item paket</div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 0.95rem; font-weight: 700; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,135,61,0.25);">
                        <span>Lanjut ke Checkout</span>
                        <x-icon name="arrow-right" size="16" />
                    </a>
                </div>
            </div>
        </div>

    </div>
    @endif
</div>
</div>

@push('scripts')
<script>
function decrementQty(btn) {
    const form = btn.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    let val = parseInt(input.value) - 1;
    if (val >= 1) { 
        input.value = val; 
        btn.disabled = true;
        form.submit(); 
    }
}
function incrementQty(btn) {
    const form = btn.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    const max = parseInt(input.max) || 999;
    let val = parseInt(input.value) + 1;
    if (val <= max) { 
        input.value = val; 
        btn.disabled = true;
        form.submit(); 
    }
}
</script>
@endpush

@endsection
