<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "name",
        "sku",
        "inventory_id"
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucwords($value),
            set: fn (?string $value) => strtolower($value)
        );
    }
    
    protected function sku(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => strtoupper($value),
            set: fn (?string $value) => strtoupper($value)
        );
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function inbounds()
    {
        return $this->hasMany(Inbound::class, "product_id");
    }
    
    public function outbounds()
    {
        return $this->hasMany(Outbound::class, "product_id");
    }
}
