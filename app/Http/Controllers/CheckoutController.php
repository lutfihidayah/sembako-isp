<?php

namespace App\Http\Controllers;

use App\Models\DropPoint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $packageId => $qty) {
            $package = Package::find($packageId);
            if ($package && $package->is_active) {
                $subtotal = $package->price * $qty;
                $cartItems[] = compact('package', 'qty', 'subtotal');
                $total += $subtotal;
            }
        }

        $dropPoints = DropPoint::active()->orderBy('region')->get();
        $user = Auth::user();

        return view('checkout.index', compact('cartItems', 'total', 'dropPoints', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'drop_point_id'  => ['required', 'exists:drop_points,id'],
            'payment_method' => ['required', 'in:transfer_bank,qris'],
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $dropPoint = DropPoint::findOrFail($request->drop_point_id);
        if (!$dropPoint->is_active) {
            return back()->with('error', 'Drop point yang dipilih tidak aktif.');
        }

        try {
            DB::transaction(function () use ($request, $cart) {
                $total = 0;
                $cartItems = [];

                foreach ($cart as $packageId => $qty) {
                    $package = Package::lockForUpdate()->find($packageId);
                    if (!$package || !$package->is_active) {
                        throw new \Exception("Paket tidak tersedia.");
                    }
                    // Stock belum dikurangi di sini — dikurangi saat admin verifikasi bayar
                    $subtotal = $package->price * $qty;
                    $total += $subtotal;
                    $cartItems[] = compact('package', 'qty', 'subtotal');
                }

                $order = Order::create([
                    'order_number'   => Order::generateOrderNumber(),
                    'user_id'        => Auth::id(),
                    'drop_point_id'  => $request->drop_point_id,
                    'status'         => 'menunggu_pembayaran',
                    'total_price'    => $total,
                    'payment_method' => $request->payment_method,
                    'expired_at'     => now()->addHours(24),
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'package_id' => $item['package']->id,
                        'quantity'   => $item['qty'],
                        'price'      => $item['package']->price, // snapshot harga
                        'subtotal'   => $item['subtotal'],
                    ]);
                }

                // Log status awal
                OrderStatusLog::create([
                    'order_id'   => $order->id,
                    'status'     => 'menunggu_pembayaran',
                    'changed_by' => null,
                    'note'       => 'Pesanan dibuat oleh konsumen.',
                    'created_at' => now(),
                ]);

                session()->forget('cart');
                session(['last_order_id' => $order->id]);
            });

            $orderId = session('last_order_id');
            return redirect()->route('orders.show', $orderId)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}
