<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderNotification extends Model
{
    protected $table = 'order_notifications';

    protected $fillable = [
        'customer_id', 'title', 'title_ar', 'body', 'body_ar',
        'is_read', 'order_id', 'order_number'
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getDisplayTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title;
    }

    public function getDisplayBodyAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->body_ar : $this->body;
    }
}
