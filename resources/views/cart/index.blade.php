@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="section">
<div class="container">
    <h1 style="margin-bottom: var(--space-xl); display: flex; align-items: center; gap: 10px;">
        <x-icon name="cart" size="28" />
        <span>Keranjang Belanja</span>
    </h1>

    @if(empty($cartItems))
    <div class="empty-state">
        <div class="empty-icon">
            <x-icon name="cart" size="54" />
        </div>
        <h3>Keranjang Anda Kosong</h3>
        <p>Belum ada paket sembako yang ditambahkan ke keranjang belanja.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Lihat Katalog Paket</a>
    </div>
    @else
    <div class="layout-split-cart">

        <!-- Cart Items -->
        <div>
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>{{ count($cartItems) }} item dalam keranjang</span>
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--danger); border-color: var(--danger); display: inline-flex; align-items: center; gap: 6px;" onclick="return confirm('Kosongkan keranjang?')">
                            <x-icon name="trash" size="14" />
                            <span>Kosongkan</span>
                        </button>
                    </form>
                </div>
                <div class="card-body" style="padding: 0;">
                    @foreach($cartItems as $item)
                    <div class="cart-item-row">
                        <!-- Image -->
                        <div style="width: 64px; height: 64px; border-radius: var(--radius-md); overflow: hidden; flex-shrink: 0; background: var(--primary-50); display: flex; align-items: center; justify-content: center;">
                            @if($item['package']->image)
                            <img src="{{ asset('storage/' . $item['package']->image) }}" alt="{{ $item['package']->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <x-icon name="package" size="28" />
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="cart-item-info">
                            <div style="font-weight: 600; color: var(--gray-800); margin-bottom: 2px;">{{ $item['package']->name }}</div>
                            <div style="font-size: 0.85rem; color: var(--primary-600); font-weight: 700;">
                                Rp {{ number_format($item['package']->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Controls & Subtotal -->
                        <div class="cart-item-controls">
                            <!-- Quantity -->
                            <form method="POST" action="{{ route('cart.update', $item['package']->id) }}" style="display: flex; align-items: center; gap: 6px;">
                                @csrf @method('PATCH')
                                <div style="display: flex; align-items: center; border: 1.5px solid var(--gray-200); border-radius: var(--radius-md); overflow: hidden; background: #fff;">
                                    <button type="button" onclick="decrementQty(this)" style="width: 32px; height: 34px; border: none; background: var(--gray-50); cursor: pointer; font-size: 1rem; font-family: inherit;">−</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['package']->stock }}"
                                           style="width: 44px; height: 34px; border: none; text-align: center; font-size: 0.9rem; font-weight: 600; outline: none; font-family: inherit;"
                                           onchange="this.form.submit()">
                                    <button type="button" onclick="incrementQty(this)" style="width: 32px; height: 34px; border: none; background: var(--gray-50); cursor: pointer; font-size: 1rem; font-family: inherit;">+</button>
                                </div>
                            </form>

                            <!-- Subtotal -->
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: var(--gray-900); font-size: 0.95rem;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                            </div>

                            <!-- Remove -->
                            <form method="POST" action="{{ route('cart.remove', $item['package']->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" style="width: 32px; height: 32px; border-radius: var(--radius-md); border: none; background: var(--gray-100); cursor: pointer; color: var(--gray-500); display: flex; align-items: center; justify-content: center;" title="Hapus">
                                    <x-icon name="trash" size="14" />
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div style="position: sticky; top: 80px;">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="clipboard" size="16" />
                    <span>Ringkasan Belanja</span>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div style="display: flex; justify-content: space-between; font-size: 0.875rem; margin-bottom: 8px; color: var(--gray-700);">
                        <span>{{ Str::limit($item['package']->name, 25) }} × {{ $item['quantity'] }}</span>
                        <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div style="border-top: 1px solid var(--gray-100); margin: var(--space-md) 0; padding-top: var(--space-md);">
                        <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 800; color: var(--gray-800);">
                            <span>Total</span>
                            <span style="color: var(--primary-600);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="width: 100%; margin-top: var(--space-sm); display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span>Lanjut ke Checkout</span>
                        <x-icon name="arrow-right" size="16" />
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-ghost btn-sm" style="width: 100%; margin-top: var(--space-sm); text-align: center;">
                        + Tambah Produk Lain
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
    if (val >= 1) { input.value = val; form.submit(); }
}
function incrementQty(btn) {
    const form = btn.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    const max = parseInt(input.max);
    let val = parseInt(input.value) + 1;
    if (val <= max) { input.value = val; form.submit(); }
}
</script>
@endpush

@endsection
