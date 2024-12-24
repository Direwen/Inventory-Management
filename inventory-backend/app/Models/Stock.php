<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        "product_id",
        "current_stock"
    ];

    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
