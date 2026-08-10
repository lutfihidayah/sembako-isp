<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'        => Order::count(),
            'pending_payment'     => Order::where('status', 'menunggu_pembayaran')->count(),
            'orders_processing'   => Order::whereIn('status', ['dibayar', 'sedang_dibelanjakan', 'dikirim', 'siap_diambil'])->count(),
            'orders_completed'    => Order::where('status', 'selesai')->count(),
            'total_revenue'       => Order::where('status', 'selesai')->sum('total_price'),
            'active_drop_points'  => DropPoint::where('is_active', true)->count(),
            'total_packages'      => Package::where('is_active', true)->count(),
            'total_users'         => User::count(),
        ];

        // Pesanan terbaru
        $recentOrders = Order::with(['user', 'dropPoint'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Pendapatan 7 hari terakhir
        $revenueChart = Order::where('status', 'selesai')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'revenueChart'));
    }
}
