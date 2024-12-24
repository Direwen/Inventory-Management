<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        "user_id",
        "title",
        "message",
        "is_read"
    ];

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucwords($value),
            set: fn (?string $value) => strtolower($value)
        );
    }

    protected function message(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucwords($value),
            set: fn (?string $value) => strtolower($value)
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
