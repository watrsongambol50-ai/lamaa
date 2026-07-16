<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->take(5)->get();
        $unreadCount = $user->notifications()->where('is_read', false)->count();
        // Loyalty is per-ORDER: every 5th order is fully free (see User::loyaltyProgress,
        // which mirrors OrderController::isLoyaltyFreeOrder — the actual pricing logic).
        // This used to be computed separately here as total_orders % 4, which drifted
        // out of sync with the real free-order rule; now reuses the model accessor.
        $loyaltyProgress = $user->loyalty_progress;
        $nextFree = $user->next_free_order;
        $totalFreeOrders = intdiv($user->total_orders, 5);
        return view('dashboard', compact('user', 'orders', 'unreadCount', 'loyaltyProgress', 'nextFree', 'totalFreeOrders'));
    }
}
