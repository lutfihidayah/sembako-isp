<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));
        $dropPointId = $request->get('drop_point_id');

        $query = Order::with(['user', 'dropPoint'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotIn('status', ['dibatalkan', 'menunggu_pembayaran']);

        if ($dropPointId) {
            $query->where('drop_point_id', $dropPointId);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        $summary = [
            'total_orders'    => $query->count(),
            'total_revenue'   => $query->where('status', 'selesai')->sum('total_price'),
            'completed_orders' => $query->where('status', 'selesai')->count(),
        ];

        // Recount without pagination for summary
        $queryForSummary = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotIn('status', ['dibatalkan', 'menunggu_pembayaran']);
        if ($dropPointId) {
            $queryForSummary->where('drop_point_id', $dropPointId);
        }

        $summary = [
            'total_orders'     => $queryForSummary->count(),
            'total_revenue'    => (clone $queryForSummary)->where('status', 'selesai')->sum('total_price'),
            'completed_orders' => (clone $queryForSummary)->where('status', 'selesai')->count(),
        ];

        $dropPoints = DropPoint::orderBy('name')->get();

        // Pesanan per drop point
        $perDropPoint = Order::whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.status', 'selesai')
            ->when($dropPointId, fn($q) => $q->where('orders.drop_point_id', $dropPointId))
            ->join('drop_points', 'orders.drop_point_id', '=', 'drop_points.id')
            ->selectRaw('drop_points.name as dp_name, COUNT(orders.id) as count, SUM(orders.total_price) as revenue')
            ->groupBy('drop_points.name')
            ->orderByDesc('count')
            ->get();

        return view('admin.reports.index', compact(
            'orders', 'summary', 'dropPoints', 'perDropPoint',
            'startDate', 'endDate', 'dropPointId'
        ));
    }
}
