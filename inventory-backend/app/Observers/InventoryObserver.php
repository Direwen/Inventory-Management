<?php

namespace App\Observers;

use App\Models\Inventory;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Models\Log;

class InventoryObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Inventory "created" event.
     */
    public function created(Inventory $inventory): void
    {
        Log::create([
            "inventory_id" => $inventory->id,
            "user_id" => auth()->id(),
            "action" => "Inventory Created"
        ]);

        Log::create([
            "inventory_id" => $inventory->id,
            "user_id" => auth()->id(),
            "action" => auth()->user()->email . " is assigned to the Admin role"
        ]);
    }

    /**
     * Handle the Inventory "updated" event.
     */
    public function updated(Inventory $inventory): void
    {
        Log::create([
            "inventory_id" => $inventory->id,
            "user_id" => auth()->id(),
            "action" => "Updated the inventory"
        ]);
    }

    /**
     * Handle the Inventory "deleted" event.
     */
    public function deleted(Inventory $inventory): void
    {
    }

    /**
     * Handle the Inventory "restored" event.
     */
    public function restored(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "force deleted" event.
     */
    public function forceDeleted(Inventory $inventory): void
    {
        //
    }
}
