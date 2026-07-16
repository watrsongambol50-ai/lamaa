<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'barcode', 'customer_id', 'service_id', 'service_mode',
        'service_name', 'service_name_ar', 'quantity', 'total_price',
        'is_free_order', 'status', 'notes', 'damage_note_photo_url',
        'pickup_date', 'delivery_date', 'pickup_time_slot', 'delivery_time_slot',
        'address', 'city', 'district', 'building_name', 'building_number', 'address_notes',
        'rating', 'rating_comment', 'express_delivery', 'payment_method',
        'coupon_code', 'discount_amount', 'subtotal_price', 'free_delivery',
        'is_pos_order', 'free_pieces_count', 'lat', 'lng',
        'cancelled_at', 'cancel_reason', 'payment_status', 'payment_reference',
    ];

    protected $casts = [
        'is_free_order'    => 'boolean',
        'express_delivery' => 'boolean',
        'free_delivery'    => 'boolean',
        'is_pos_order'     => 'boolean',
        'total_price'      => 'float',
        'subtotal_price'   => 'float',
        'discount_amount'  => 'float',
        'lat'              => 'float',
        'lng'              => 'float',
        'cancelled_at'     => 'datetime',
    ];

    /**
     * Customer can cancel their own order only while it hasn't started washing yet.
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['new', 'picked_up'], true);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'ar' => [
                'new' => 'جديد', 'picked_up' => 'تم الاستلام',
                'washing' => 'جاري الغسيل', 'ready' => 'جاهز',
                'out_for_delivery' => 'في الطريق', 'delivered' => 'تم التسليم',
                'cancelled' => 'ملغي'
            ],
            'en' => [
                'new' => 'New', 'picked_up' => 'Picked Up',
                'washing' => 'Washing', 'ready' => 'Ready',
                'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered',
                'cancelled' => 'Cancelled'
            ]
        ];
        $locale = app()->getLocale();
        return $labels[$locale][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'new' => 'blue',
            'picked_up' => 'yellow',
            'washing' => 'purple',
            'ready' => 'green',
            'out_for_delivery' => 'orange',
            'delivered' => 'gray',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
}
