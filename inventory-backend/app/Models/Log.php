<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        "user_id",
        "inventory_id",
        "action"
    ];

    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucwords($value),
            set: fn (?string $value) => strtolower($value)
        );
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
