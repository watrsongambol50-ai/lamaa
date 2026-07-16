<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminPosController extends Controller
{
    public function create()
    {
        $services = Service::orderBy('category')->orderBy('base_price')->get();
        return view('admin.pos.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string',
            'items'          => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'service_mode'   => 'required|in:wash_only,wash_iron,iron_only',
            'coupon_code'    => 'nullable|string',
        ]);

        $customer = User::where('phone', $request->customer_phone)->first();
        if (!$customer) {
            $customer = User::create([
                'name'     => $request->customer_name,
                'phone'    => $request->customer_phone,
                'password' => Hash::make(Str::random(12)),
                'role'     => 'customer',
                'total_orders' => 0,
            ]);
        }

        $cartItems = [];
        foreach ($request->items as $row) {
            $service = Service::findOrFail($row['service_id']);
            $cartItems[] = ['service' => $service, 'quantity' => (int) $row['quantity']];
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

        $discount = 0;
        $couponCode = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = round($subtotal * ($coupon->percentage / 100), 2);
                $couponCode = $coupon->code;
                $coupon->increment('used_count');
            }
        }

        $totalPrice = max(0, $subtotal - $discount);
        $newTotalOrders = $customer->total_orders + 1;

        $order = DB::transaction(function () use ($request, $customer, $cartItems, $isExpress, $subtotal, $discount, $totalPrice, $couponCode, $totalPieces, $newTotalOrders) {
            $first = $cartItems[0]['service'];
            $order = Order::create([
                'order_number'    => 'LM' . strtoupper(Str::random(6)),
                'barcode'         => 'LM' . date('Ymd') . str_pad($newTotalOrders, 4, '0', STR_PAD_LEFT),
                'customer_id'     => $customer->id,
                'service_id'      => $first->id,
                'service_mode'    => $request->service_mode,
                'service_name'    => $first->name_en,
                'service_name_ar' => $first->name_ar,
                'quantity'        => $totalPieces,
                'total_price'     => $totalPrice,
                'subtotal_price'  => $subtotal,
                'discount_amount' => $discount,
                'coupon_code'     => $couponCode,
                'is_free_order'   => false,
                'status'          => 'new',
                'pickup_date'     => now()->toDateString(),
                'address'         => 'استلام من الفرع (كاشير)',
                'express_delivery'=> $isExpress,
                'payment_method'  => 'cash',
                'is_pos_order'    => true,
            ]);

            foreach ($cartItems as $item) {
                $unitPrice = $item['service']->priceForMode($request->service_mode);
                if ($isExpress) $unitPrice += 1.5;
                OrderItem::create([
                    'order_id'        => $order->id,
                    'service_id'      => $item['service']->id,
                    'service_name'    => $item['service']->name_en,
                    'service_name_ar' => $item['service']->name_ar,
                    'quantity'        => $item['quantity'],
                    'free_quantity'   => 0,
                    'unit_price'      => $unitPrice,
                    'subtotal'        => $item['quantity'] * $unitPrice,
                ]);
            }

            $customer->increment('total_orders');
            $customer->increment('total_pieces_washed', $totalPieces);

            return $order;
        });

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'تم إنشاء طلب الكاشير بنجاح، رقم الطلب: ' . $order->order_number);
    }
}
