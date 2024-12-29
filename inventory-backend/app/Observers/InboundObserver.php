<?php

namespace App\Observers;

use App\Models\Inbound;
use App\Models\Log;

class InboundObserver
{
    /**
     * Handle the Inbound "created" event.
     */
    public function created(Inbound $inbound): void
    {
        $inbound->loadMissing('product.inventory');  // Ensure product and inventory are loaded

        Log::create([
            "inventory_id" => $inbound->product->inventory->id,
            "user_id" => $inbound->user->id,
            "action" => "{$inbound->user->email} added {$inbound->quantity} units to '{$inbound->product->sku}'"
        ]);
    }


    /**
     * Handle the Inbound "updated" event.
     */
    public function updated(Inbound $inbound): void
    {
        //
    }

    /**
     * Handle the Inbound "deleted" event.
     */
    public function deleted(Inbound $inbound): void
    {
        //
    }

    /**
     * Handle the Inbound "restored" event.
     */
    public function restored(Inbound $inbound): void
    {
        //
    }

    /**
     * Handle the Inbound "force deleted" event.
     */
    public function forceDeleted(Inbound $inbound): void
    {
        //
    }
}
