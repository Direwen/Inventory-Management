<?php

namespace App\Models;

use App\Observers\InvitationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InvitationObserver::class])]
class Invitation extends Model
{
    protected $fillable = [
        "inviter_id",
        "invitee_email",
        "inventory_id",
        "status",
        "expires_at"
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => strtolower($value),
            set: fn (?string $value) => strtolower($value)
        );
    }

    public function inventory()
    {
        $this->belongsTo(User::class);
    }

    public function inviter()
    {
        $this->belongsTo(User::class);
    }
}
