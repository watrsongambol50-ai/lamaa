<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderNotification;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer')->latest();
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', '%'.$request->search.'%'));
            });
        }
        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('customer', 'service')->findOrFail($id);
        $statuses = ['new', 'picked_up', 'washing', 'ready', 'out_for_delivery', 'delivered', 'cancelled'];
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:new,picked_up,washing,ready,out_for_delivery,delivered,cancelled']);
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        $statusLabels = [
            'ar' => ['new'=>'جديد','picked_up'=>'تم الاستلام','washing'=>'جاري الغسيل','ready'=>'جاهز','out_for_delivery'=>'في الطريق','delivered'=>'تم التسليم','cancelled'=>'ملغي'],
            'en' => ['new'=>'New','picked_up'=>'Picked Up','washing'=>'Washing','ready'=>'Ready','out_for_delivery'=>'Out for Delivery','delivered'=>'Delivered','cancelled'=>'Cancelled'],
        ];

        OrderNotification::create([
            'customer_id' => $order->customer_id,
            'title' => 'Order Status Updated',
            'title_ar' => 'تم تحديث حالة طلبك',
            'body' => 'Order ' . $order->order_number . ' status: ' . ($statusLabels['en'][$request->status] ?? $request->status),
            'body_ar' => 'طلبك رقم ' . $order->order_number . ' - الحالة: ' . ($statusLabels['ar'][$request->status] ?? $request->status),
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'is_read' => false,
        ]);

        return back()->with('success', 'تم تحديث الحالة بنجاح');
    }
}
