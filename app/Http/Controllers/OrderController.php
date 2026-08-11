<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $statusTab = $request->get('status', 'all');

        $query = $user->orders()->with(['dropPoint', 'items.package'])->orderBy('created_at', 'desc');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items.package', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($statusTab === 'belum_bayar') {
            $query->where('status', 'menunggu_pembayaran');
        } elseif ($statusTab === 'dikemas') {
            $query->whereIn('status', ['dibayar', 'sedang_dibelanjakan']);
        } elseif ($statusTab === 'dikirim') {
            $query->where('status', 'dikirim');
        } elseif ($statusTab === 'siap_diambil') {
            $query->where('status', 'siap_diambil');
        } elseif ($statusTab === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($statusTab === 'dibatalkan') {
            $query->where('status', 'dibatalkan');
        }

        $orders = $query->paginate(10)->withQueryString();

        $counts = [
            'all'          => $user->orders()->count(),
            'belum_bayar'  => $user->orders()->where('status', 'menunggu_pembayaran')->count(),
            'dikemas'      => $user->orders()->whereIn('status', ['dibayar', 'sedang_dibelanjakan'])->count(),
            'dikirim'      => $user->orders()->where('status', 'dikirim')->count(),
            'siap_diambil' => $user->orders()->where('status', 'siap_diambil')->count(),
            'selesai'      => $user->orders()->where('status', 'selesai')->count(),
            'dibatalkan'   => $user->orders()->where('status', 'dibatalkan')->count(),
        ];

        return view('orders.index', compact('orders', 'statusTab', 'counts'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load(['dropPoint', 'items.package', 'statusLogs.admin']);
        return view('orders.show', compact('order'));
    }

    public function uploadPayment(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $order->update(['payment_proof' => $path]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Admin akan memverifikasi segera.');
    }
}
