<?php

namespace App\Models;

use App\Observers\InventoryCollaboratorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InventoryCollaboratorObserver::class])]
class InventoryCollaborator extends Model
{

    protected $fillable = [
        'user_id',
        'inventory_id',
        'role'
    ];

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucwords($value),
            set: fn(string $value) => strtolower($value)
        );
    }

    /**
     * Get the user associated with this collaboration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the inventory associated with this collaboration.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

}
