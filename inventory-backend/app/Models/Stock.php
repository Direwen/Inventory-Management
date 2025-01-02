<?php

namespace App\Models;

use App\Observers\StockObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([StockObserver::class])]
class Stock extends Model
{
    protected $fillable = [
        "product_id",
        "current_stock"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
