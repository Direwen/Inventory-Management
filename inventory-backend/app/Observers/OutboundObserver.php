<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Outbound;

class OutboundObserver
{
    /**
     * Handle the Outbound "created" event.
     */
    public function created(Outbound $outbound): void
    {
        $outbound->loadMissing('product.inventory');  // Ensure product and inventory are loaded

        Log::create([
            "inventory_id" => $outbound->product->inventory->id,
            "user_id" => $outbound->user->id,
            "action" => "{$outbound->user->email} removed {$outbound->quantity} units from '{$outbound->product->sku}'"
        ]);
    }

    /**
     * Handle the Outbound "updated" event.
     */
    public function updated(Outbound $outbound): void
    {
        //
    }

    /**
     * Handle the Outbound "deleted" event.
     */
    public function deleted(Outbound $outbound): void
    {
        //
    }

    /**
     * Handle the Outbound "restored" event.
     */
    public function restored(Outbound $outbound): void
    {
        //
    }

    /**
     * Handle the Outbound "force deleted" event.
     */
    public function forceDeleted(Outbound $outbound): void
    {
        //
    }
}
