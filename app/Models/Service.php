<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Service extends Model {
    protected $fillable = ['name_ar','name_en','icon','category','base_price','price_wash_only','price_iron_only','description'];

    public function priceForMode(string $mode): float
    {
        return match ($mode) {
            'wash_only' => $this->price_wash_only ?? round($this->base_price * 0.8, 2),
            'iron_only' => $this->price_iron_only ?? round($this->base_price * 0.6, 2),
            default     => $this->base_price,
        };
    }
}
