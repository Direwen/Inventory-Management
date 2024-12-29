<?php

namespace App\Models;

use App\Observers\OutboundObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([OutboundObserver::class])]
class Outbound extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'user_id'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
