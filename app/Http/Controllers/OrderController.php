<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\SmsService;
use App\Services\PaymentService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->orders()->with('items')->latest();
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $orders = $query->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $services = Service::orderBy('category')->orderBy('base_price')->get();
        return view('orders.create', compact('services'));
    }

    /**
     * Apply the per-ORDER loyalty rule.
     * Orders 1-4 in a cycle are paid normally; the 5th order in the cycle is
     * completely free (all pieces free). The cycle then resets and repeats
     * (order 6-9 paid, order 10 free, etc.).
     */
    private function isLoyaltyFreeOrder(int $orderSequenceNumber): bool
    {
        return $orderSequenceNumber % 5 === 0;
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'               => 'required|array|min:1',
            'items.*.service_id'  => 'required|exists:services,id',
            'items.*.quantity'    => 'required|integer|min:1',
            'service_mode'        => 'required|in:wash_only,wash_iron,iron_only',
            'pickup_date'         => 'required|date',
            'city'                => 'required|string',
            'district'            => 'nullable|string',
            'building_name'       => 'nullable|string',
            'building_number'     => 'nullable|string',
            'payment_method'      => 'required|in:cash,online',
            'lat'                 => 'nullable|numeric|between:-90,90',
            'lng'                 => 'nullable|numeric|between:-180,180',
        ]);

        $onlinePaymentAvailable = filled(config('services.moyasar.secret_key'));
        if ($request->payment_method === 'online' && !$onlinePaymentAvailable) {
            return back()->withErrors(['payment_method' => 'الدفع الإلكتروني غير متاح حالياً — قريباً إن شاء الله. الرجاء اختيار الدفع عند الاستلام.'])->withInput();
        }

        $user = Auth::user();

        $cartItems = [];
        foreach ($request->items as $row) {
            $service = Service::findOrFail($row['service_id']);
            $cartItems[] = [
                'service'  => $service,
                'quantity' => (int) $row['quantity'],
            ];
        }

        $isExpress = $request->boolean('express');
        $subtotal = 0;
        $totalPieces = 0;
        foreach ($cartItems as $item) {
            $unitPrice = $item['service']->priceForMode($request->service_mode);
            if ($isExpress) $unitPrice += 1.5;
            $subtotal += $item['quantity'] * $unitPrice;
            $totalPieces += $item['quantity'];
        }

        // Coupon
        $discount = 0;
        $couponCode = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = round($subtotal * ($coupon->percentage / 100), 2);
                $couponCode = $coupon->code;
                $coupon->increment('used_count');
            } else {
                return back()->withErrors(['coupon_code' => 'كود الخصم غير صالح أو منتهي.'])->withInput();
            }
        }

        $newTotalOrders = $user->total_orders + 1;
        // Free-delivery milestone: every 3rd order gets free delivery
        $freeDelivery = ($newTotalOrders % 3 === 0);

        // Loyalty (per-order cycle): orders 1-4 paid, 5th order fully free, cycle repeats
        $isFreeOrder = $this->isLoyaltyFreeOrder($newTotalOrders);
        $freePieces  = $isFreeOrder ? $totalPieces : 0;

        foreach ($cartItems as &$item) {
            $item['free_quantity'] = $isFreeOrder ? $item['quantity'] : 0;
        }
        unset($item);

        $totalPrice = $isFreeOrder ? 0 : max(0, $subtotal - $discount);

        $order = DB::transaction(function () use (
            $request, $user, $cartItems, $newTotalOrders, $isExpress, $freeDelivery,
            $subtotal, $discount, $totalPrice, $couponCode, $freePieces, $isFreeOrder, $totalPieces
        ) {
            $first = $cartItems[0]['service'];

            $order = Order::create([
                'order_number'      => 'LM' . strtoupper(Str::random(6)),
                'barcode'           => 'LM' . date('Ymd') . str_pad($newTotalOrders, 4, '0', STR_PAD_LEFT),
                'customer_id'       => $user->id,
                'service_id'        => $first->id,
                'service_mode'      => $request->service_mode,
                'service_name'      => $first->name_en,
                'service_name_ar'   => $first->name_ar,
                'quantity'          => $totalPieces,
                'total_price'       => $totalPrice,
                'subtotal_price'    => $subtotal,
                'discount_amount'   => $discount,
                'coupon_code'       => $couponCode,
                'is_free_order'     => $isFreeOrder,
                'free_pieces_count' => $freePieces,
                'status'            => 'new',
                'notes'             => $request->notes,
                'pickup_date'       => $request->pickup_date,
                'pickup_time_slot'  => $request->pickup_time_slot,
                'address'           => $request->address,
                'city'              => $request->city,
                'district'          => $request->district,
                'building_name'     => $request->building_name,
                'building_number'   => $request->building_number,
                'address_notes'     => $request->address_notes,
                'lat'               => $request->lat,
                'lng'               => $request->lng,
                'express_delivery'  => $isExpress,
                'free_delivery'     => $freeDelivery,
                'payment_method'    => $request->payment_method,
                'payment_status'    => $request->payment_method === 'online' ? 'pending' : 'unpaid',
            ]);

            foreach ($cartItems as $item) {
                $unitPrice = $item['service']->priceForMode($request->service_mode);
                if ($isExpress) $unitPrice += 1.5;
                $paidQty = $item['quantity'] - $item['free_quantity'];
                OrderItem::create([
                    'order_id'        => $order->id,
                    'service_id'      => $item['service']->id,
                    'service_name'    => $item['service']->name_en,
                    'service_name_ar' => $item['service']->name_ar,
                    'quantity'        => $item['quantity'],
                    'free_quantity'   => $item['free_quantity'],
                    'unit_price'      => $unitPrice,
                    'subtotal'        => $paidQty * $unitPrice,
                ]);
            }

            $user->increment('total_orders');
            $user->increment('total_pieces_washed', $totalPieces);
            $user->increment('free_pieces_earned', $order->free_pieces_count);

            return $order;
        });

        $freeMsg     = $freePieces > 0 ? " (تم تفعيل {$freePieces} قطعة مجانية ضمن برنامج الولاء!)" : '';
        $deliveryMsg = $freeDelivery ? ' + توصيل مجاني 🎉' : '';

        OrderNotification::create([
            'audience'     => 'customer',
            'customer_id'  => $user->id,
            'title'        => 'Order Placed Successfully',
            'title_ar'     => 'تم استلام طلبك بنجاح',
            'body'         => 'Your order ' . $order->order_number . ' has been placed.',
            'body_ar'      => 'تم استلام طلبك رقم ' . $order->order_number . ' بنجاح.' . $freeMsg . $deliveryMsg,
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
            'is_read'      => false,
        ]);

        OrderNotification::create([
            'audience'     => 'admin',
            'customer_id'  => null,
            'title'        => 'New Order Received',
            'title_ar'     => 'طلب جديد من ' . $user->name,
            'body'         => 'New order ' . $order->order_number,
            'body_ar'      => 'وصل طلب جديد رقم ' . $order->order_number . ' من العميل ' . $user->name,
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
            'is_read'      => false,
        ]);

        // Best-effort SMS — silently skipped if no gateway configured yet (see app/Services/SmsService.php)
        try {
            app(SmsService::class)->send($user->country_code . $user->phone, 'تم استلام طلبك رقم ' . $order->order_number . ' في لماء، شكراً لثقتك.');
        } catch (\Throwable $e) {
            // Intentionally ignored — SMS gateway not configured yet.
        }

        if ($order->payment_method === 'online' && $order->total_price > 0) {
            return redirect()->route('orders.pay', $order->id);
        }

        return redirect()->route('orders.show', $order->id)->with('success', __('messages.order_placed'));
    }

    /**
     * Show the Moyasar hosted payment form for an unpaid online order.
     */
    public function pay($id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);
        if ($order->payment_method !== 'online' || $order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id);
        }
        abort_unless(filled(config('services.moyasar.publishable_key')), 503, 'الدفع الإلكتروني غير مُفعّل بعد.');
        return view('orders.pay', compact('order'));
    }

    /**
     * Moyasar redirects the browser back here after the customer completes payment.
     * We verify the payment status server-side via Moyasar's API before trusting it.
     */
    public function paymentCallback(Request $request, $id, PaymentService $payments)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);
        $paymentId = $request->query('id');

        if ($paymentId) {
            $result = $payments->verify($paymentId);
            if ($result && ($result['status'] ?? null) === 'paid') {
                $order->update(['payment_status' => 'paid', 'payment_reference' => $paymentId]);
                return redirect()->route('orders.show', $order->id)->with('success', 'تم الدفع بنجاح، شكراً لك!');
            }
            $order->update(['payment_status' => 'failed', 'payment_reference' => $paymentId]);
        }

        return redirect()->route('orders.pay', $order->id)->with('error', 'لم تكتمل عملية الدفع، حاول مرة أخرى.');
    }

    /**
     * Customer-initiated cancellation. Only allowed while the order hasn't
     * started washing yet ("new" or "picked_up"), per business rule.
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        if (!$order->isCancellable()) {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب لأن الغسيل قد بدأ أو تم تسليمه.');
        }

        $order->update([
            'status'        => 'cancelled',
            'cancelled_at'  => now(),
            'cancel_reason' => $request->input('reason'),
        ]);

        OrderNotification::create([
            'audience'     => 'admin',
            'customer_id'  => null,
            'title'        => 'Order Cancelled by Customer',
            'title_ar'     => 'تم إلغاء طلب من العميل',
            'body'         => 'Order ' . $order->order_number . ' was cancelled by the customer.',
            'body_ar'      => 'قام العميل ' . Auth::user()->name . ' بإلغاء الطلب رقم ' . $order->order_number . ($request->filled('reason') ? (' — السبب: ' . $request->input('reason')) : ''),
            'order_id'     => $order->id,
            'order_number' => $order->order_number,
            'is_read'      => false,
        ]);

        return back()->with('success', 'تم إلغاء الطلب بنجاح.');
    }

    public function show($id)
    {
        $order      = Order::where('customer_id', Auth::id())->with('items')->findOrFail($id);
        $statuses   = ['new', 'picked_up', 'washing', 'ready', 'out_for_delivery', 'delivered'];
        $currentStep = array_search($order->status, $statuses);
        return view('orders.show', compact('order', 'statuses', 'currentStep'));
    }

    public function rate(Request $request, $id)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);
        $order = Order::where('customer_id', Auth::id())->where('status', 'delivered')->findOrFail($id);
        $order->update(['rating' => $request->rating, 'rating_comment' => $request->comment]);
        return back()->with('success', __('messages.rated'));
    }

    public function confirmDelivery($id)
    {
        $order = Order::where('customer_id', Auth::id())->where('status', 'out_for_delivery')->findOrFail($id);
        $order->update(['status' => 'delivered', 'delivery_date' => now()->toDateString()]);
        return back()->with('success', __('messages.delivery_confirmed'));
    }
}
