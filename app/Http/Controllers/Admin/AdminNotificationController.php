<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderNotification;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = OrderNotification::where('audience', 'admin')->latest()->get();
        OrderNotification::where('audience', 'admin')->where('is_read', false)->update(['is_read' => true]);
        return view('admin.notifications', compact('notifications'));
    }

    public function clearAll()
    {
        OrderNotification::where('audience', 'admin')->delete();
        return back()->with('success', 'تم حذف كل الإشعارات');
    }
}
