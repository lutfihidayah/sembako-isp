<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'dropPoint']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($dropPointId = $request->get('drop_point_id')) {
            $query->where('drop_point_id', $dropPointId);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $dropPoints = DropPoint::orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'dropPoints'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'dropPoint', 'items.package', 'statusLogs.admin']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $request->status;

        if (!$order->canAdvanceTo($newStatus)) {
            return back()->with('error', 'Status tidak bisa diubah ke tahap tersebut. Status harus berurutan.');
        }

        DB::transaction(function () use ($order, $newStatus, $request) {
            $order->update(['status' => $newStatus]);

            OrderStatusLog::create([
                'order_id'   => $order->id,
                'status'     => $newStatus,
                'changed_by' => Auth::guard('admin')->id(),
                'note'       => $request->note,
                'created_at' => now(),
            ]);
        });

        $label = Order::STATUS_LABELS[$newStatus] ?? $newStatus;
        return back()->with('success', "Status pesanan diubah ke \"{$label}\".");
    }

    public function verifyPayment(Request $request, Order $order)
    {
        if ($order->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }

        if (!$order->payment_proof) {
            return back()->with('error', 'Konsumen belum upload bukti pembayaran.');
        }

        DB::transaction(function () use ($order, $request) {
            // Kurangi stok saat pembayaran terverifikasi
            foreach ($order->items as $item) {
                $package = Package::lockForUpdate()->find($item->package_id);
                if ($package) {
                    $package->decrement('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'dibayar']);

            OrderStatusLog::create([
                'order_id'   => $order->id,
                'status'     => 'dibayar',
                'changed_by' => Auth::guard('admin')->id(),
                'note'       => 'Pembayaran telah diverifikasi oleh admin. ' . ($request->note ?? ''),
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Stok paket telah dikurangi.');
    }

    public function cancel(Request $request, Order $order)
    {
        if (in_array($order->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        DB::transaction(function () use ($order, $request) {
            // Kembalikan stok jika sudah dibayar
            if ($order->status !== 'menunggu_pembayaran') {
                foreach ($order->items as $item) {
                    $package = Package::lockForUpdate()->find($item->package_id);
                    if ($package) {
                        $package->increment('stock', $item->quantity);
                    }
                }
            }

            $order->update(['status' => 'dibatalkan']);

            OrderStatusLog::create([
                'order_id'   => $order->id,
                'status'     => 'dibatalkan',
                'changed_by' => Auth::guard('admin')->id(),
                'note'       => $request->note ?? 'Pesanan dibatalkan oleh admin.',
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
