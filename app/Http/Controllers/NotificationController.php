<?php

namespace App\Http\Controllers;

use App\Models\OrderNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = OrderNotification::where('customer_id', Auth::id())->where('audience', 'customer')->latest()->get();
        OrderNotification::where('customer_id', Auth::id())->where('audience', 'customer')->where('is_read', false)->update(['is_read' => true]);
        return view('notifications', compact('notifications'));
    }

    public function markRead($id)
    {
        OrderNotification::where('customer_id', Auth::id())->findOrFail($id)->update(['is_read' => true]);
        return back();
    }

    public function markAllRead()
    {
        OrderNotification::where('customer_id', Auth::id())->update(['is_read' => true]);
        return back();
    }

    public function clearAll()
    {
        OrderNotification::where('customer_id', Auth::id())->where('audience', 'customer')->delete();
        return back()->with('success', __('messages.notifications_cleared'));
    }
}
