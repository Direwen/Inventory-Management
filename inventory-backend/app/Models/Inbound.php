<?php

namespace App\Models;

use App\Observers\InboundObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InboundObserver::class])]
class Inbound extends Model
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
