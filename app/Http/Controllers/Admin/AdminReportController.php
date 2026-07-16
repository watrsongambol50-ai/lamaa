<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

/**
 * Financial reports — restricted to the full admin only (see the `super_admin`
 * middleware). Staff accounts cannot view revenue or discount data.
 */
class AdminReportController extends Controller
{
    public function index()
    {
        $from = request('from') ? \Carbon\Carbon::parse(request('from')) : now()->startOfMonth();
        $to   = request('to') ? \Carbon\Carbon::parse(request('to'))->endOfDay() : now()->endOfDay();

        $paidOrders = Order::whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 'cancelled');

        $totalRevenue    = (clone $paidOrders)->where('is_free_order', false)->sum('total_price');
        $totalOrders     = (clone $paidOrders)->count();
        $totalDiscount   = (clone $paidOrders)->sum('discount_amount');
        $freePiecesValue = (clone $paidOrders)->sum('free_pieces_count');
        $cashRevenue     = (clone $paidOrders)->where('payment_method', 'cash')->where('is_free_order', false)->sum('total_price');
        $onlineRevenue   = (clone $paidOrders)->where('payment_method', 'online')->where('payment_status', 'paid')->sum('total_price');

        $byDay = (clone $paidOrders)
            ->selectRaw("DATE(created_at) as day, SUM(CASE WHEN is_free_order = 0 THEN total_price ELSE 0 END) as revenue, COUNT(*) as orders_count")
            ->groupBy('day')->orderBy('day')->get();

        $byService = (clone $paidOrders)
            ->selectRaw('service_name_ar, COUNT(*) as orders_count, SUM(quantity) as pieces, SUM(total_price) as revenue')
            ->groupBy('service_name_ar')->orderByDesc('revenue')->limit(10)->get();

        $statusBreakdown = Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');

        $cancelledCount = Order::whereBetween('created_at', [$from, $to])->where('status', 'cancelled')->count();

        $maxDayRevenue = $byDay->max('revenue') ?: 1;

        return view('admin.reports.index', compact(
            'from', 'to', 'totalRevenue', 'totalOrders', 'totalDiscount', 'freePiecesValue',
            'cashRevenue', 'onlineRevenue', 'byDay', 'byService', 'statusBreakdown',
            'cancelledCount', 'maxDayRevenue'
        ));
    }
}
