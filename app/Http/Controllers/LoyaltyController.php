<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $freePiecesTotal = $user->free_pieces_earned;
        // Loyalty is per-ORDER: orders 1-4 in a cycle are paid, the 5th order is fully free
        // (see User::loyaltyProgress, which mirrors OrderController::isLoyaltyFreeOrder).
        // Free delivery is a separate, per-ORDER perk on every 3rd order.
        $loyaltyProgress = $user->loyalty_progress;
        $nextFree        = $user->next_free_order;
        $completedCycles = intdiv($user->total_orders, 5);
        $freeDeliveryProgress = $user->total_orders % 3;
        $nextFreeDelivery     = $freeDeliveryProgress === 0 ? 3 : 3 - $freeDeliveryProgress;

        return view('loyalty', compact(
            'user', 'freePiecesTotal', 'loyaltyProgress', 'nextFree', 'completedCycles',
            'freeDeliveryProgress', 'nextFreeDelivery'
        ));
    }
}
