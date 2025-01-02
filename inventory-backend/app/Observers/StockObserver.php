<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Stock;

class StockObserver
{
    /**
     * Handle the Stock "created" event.
     */
    public function created(Stock $stock): void
    {
        // Check if the stock has hit 0
        if ($stock->current_stock == 0) {

            $stock->load('product.inventory.collaborators');

            // Get the collaborators of the product's inventory
            $collaborators = $stock->product->inventory->collaborators;
            // Loop through each collaborator and create a notification
            foreach ($collaborators as $collaborator) {
                Notification::create([
                    'user_id' => $collaborator->id, // Assuming 'collaborator' has 'id' field
                    'title' => 'Stock Depleted',
                    'message' => "The stock for product {$stock->product->name} in inventory {$stock->product->inventory->name} has reached zero.",
                ]);
            }
        }
    }

    /**
     * Handle the Stock "updated" event.
     */
    public function updated(Stock $stock): void
    {
        // Check if the stock has hit 0
        if ($stock->current_stock == 0) {

            $stock->load('product.inventory.collaborators');

            // Get the collaborators of the product's inventory
            $collaborators = $stock->product->inventory->collaborators;
            // Loop through each collaborator and create a notification
            foreach ($collaborators as $collaborator) {
                Notification::create([
                    'user_id' => $collaborator->id, // Assuming 'collaborator' has 'id' field
                    'title' => 'Stock Depleted',
                    'message' => "The stock for product {$stock->product->name} in inventory {$stock->product->inventory->name} has reached zero.",
                ]);
            }
        }
    }

    /**
     * Handle the Stock "deleted" event.
     */
    public function deleted(Stock $stock): void
    {
        //
    }

    /**
     * Handle the Stock "restored" event.
     */
    public function restored(Stock $stock): void
    {
        //
    }

    /**
     * Handle the Stock "force deleted" event.
     */
    public function forceDeleted(Stock $stock): void
    {
        //
    }
}
