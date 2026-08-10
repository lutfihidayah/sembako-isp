<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Ambil isi keranjang dari session
     */
    private function getCart(): array
    {
        return session('cart', []);
    }

    /**
     * Simpan keranjang ke session
     */
    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $cartItems = [];
        $total = 0;

        foreach ($cart as $packageId => $qty) {
            $package = Package::find($packageId);
            if ($package) {
                $subtotal = $package->price * $qty;
                $cartItems[] = [
                    'package'  => $package,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Package $package)
    {
        if (!$package->is_active || $package->stock <= 0) {
            return back()->with('error', 'Paket tidak tersedia atau stok habis.');
        }

        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = $this->getCart();
        $currentQty = $cart[$package->id] ?? 0;

        if ($currentQty + $qty > $package->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia (' . $package->stock . ' unit).');
        }

        $cart[$package->id] = $currentQty + $qty;
        $this->saveCart($cart);

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout.index')->with('success', "Paket \"{$package->name}\" siap dipesan.");
        }

        return back()->with('success', "Paket \"{$package->name}\" berhasil ditambahkan ke keranjang.");
    }

    public function update(Request $request, $packageId)
    {
        $qty = max(0, (int) $request->input('quantity', 0));
        $cart = $this->getCart();

        if ($qty === 0) {
            unset($cart[$packageId]);
        } else {
            $package = Package::find($packageId);
            if ($package && $qty > $package->stock) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart[$packageId] = $qty;
        }

        $this->saveCart($cart);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($packageId)
    {
        $cart = $this->getCart();
        unset($cart[$packageId]);
        $this->saveCart($cart);
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
