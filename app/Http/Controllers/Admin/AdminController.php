<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalOrders     = Order::count();
        $totalCustomers  = User::where('role', 'customer')->count();
        $todayOrders     = Order::whereDate('created_at', today())->count();
        $pendingOrders   = Order::whereNotIn('status', ['delivered', 'cancelled'])->count();
        $revenue         = Order::where('is_free_order', false)->sum('total_price');
        $todayRevenue    = Order::whereDate('created_at', today())->where('is_free_order', false)->sum('total_price');
        $monthRevenue    = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('is_free_order', false)->sum('total_price');
        $avgOrderValue   = $totalOrders > 0 ? round(Order::where('is_free_order', false)->avg('total_price'), 2) : 0;
        $freePiecesGiven = (int) Order::sum('free_pieces_count');
        $freeDeliveries  = Order::where('free_delivery', true)->count();
        $statusCounts    = Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');

        $query = Order::with(['customer', 'items'])->latest();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('q')) {
            $search = request('q');
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
                })->orWhere('id', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        $allOrders = $query->get();

        return view('admin.index', compact(
            'totalOrders', 'totalCustomers', 'todayOrders', 'pendingOrders',
            'revenue', 'todayRevenue', 'monthRevenue', 'avgOrderValue',
            'freePiecesGiven', 'freeDeliveries', 'allOrders', 'statusCounts'
        ));
    }
}
