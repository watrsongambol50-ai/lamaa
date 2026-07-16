<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Only self-editable profile fields are mass-assignable. Privileged/system fields
     * (role, created_by, loyalty counters) are intentionally excluded — they must be
     * set explicitly via forceFill()/direct assignment in trusted controller code only,
     * never from raw request input, to prevent privilege escalation via mass assignment.
     */
    protected $fillable = [
        'name', 'phone', 'country_code', 'email', 'address', 'region', 'password',
    ];
    protected $guarded = [
        'role', 'total_orders', 'total_pieces_washed', 'free_pieces_earned', 'created_by', 'id',
    ];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'total_orders'         => 'integer',
        'total_pieces_washed'  => 'integer',
        'free_pieces_earned'   => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->hasMany(OrderNotification::class, 'customer_id');
    }

    /**
     * Loyalty is counted per ORDER (matches OrderController::isLoyaltyFreeOrder,
     * which is the code that actually zeroes out total_price / frees all pieces
     * on an order — that is the single source of truth for this business rule).
     * Orders 1-4 in a 5-order cycle are paid; the 5th order is fully free (every
     * piece in it). Current position within that 5-order cycle (0–4).
     */
    public function getLoyaltyProgressAttribute()
    {
        return $this->total_orders % 5;
    }

    /**
     * How many more orders until the next fully-free order.
     */
    public function getNextFreeOrderAttribute()
    {
        $rem = $this->total_orders % 5;
        return $rem === 0 ? 5 : 5 - $rem;
    }
}
