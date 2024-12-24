<?php

namespace App\Models;

use App\Observers\InventoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([InventoryObserver::class])]
class Inventory extends Model
{

    protected $fillable = [
        'name',
        'description',
        'stock_threshold'
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucwords($value),
            set: fn(string $value) => strtolower($value)
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => ucwords($value),
            set: fn(?string $value) => strtolower($value)
        );
    }

    /**
     * Get all the collaborations for this inventory.
     */
    public function collaborators()
    {
        return $this->hasMany(InventoryCollaborator::class, 'inventory_id');
    }

    
    public function products()
    {
        return $this->hasMany(Product::class, "inventory_id");
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, "inventory_id");
    }

    public function logs()
    {
        return $this->hasMany(Log::class, "inventory_id");
    }
    
}
