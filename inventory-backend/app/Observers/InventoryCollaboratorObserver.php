<?php

namespace App\Observers;

use App\Models\InventoryCollaborator;
use App\Models\Log;

class InventoryCollaboratorObserver
{
    /**
     * Handle the InventoryCollaborator "created" event.
     */
    public function created(InventoryCollaborator $inventoryCollaborator): void
    {
        // You can add any logic here when a collaborator is created
        Log::create([
            "inventory_id" => $inventoryCollaborator->inventory_id,
            "user_id" => auth()->id(),
            "action" => "Collaborator with email {$inventoryCollaborator->user->email} was added to the inventory."
        ]);
    }

    /**
     * Handle the InventoryCollaborator "updated" event.
     */
    public function updated(InventoryCollaborator $inventoryCollaborator): void
    {
        Log::create([
            "inventory_id" => $inventoryCollaborator->inventory_id,
            "user_id" => auth()->id(),
            "action" => "{$inventoryCollaborator->user->email} was assigned the role of " . strtoupper($inventoryCollaborator->role)
        ]);
    }


    /**
     * Handle the InventoryCollaborator "deleted" event.
     */
    public function deleted(InventoryCollaborator $inventoryCollaborator): void
    {
        Log::create([
            "inventory_id" => $inventoryCollaborator->inventory_id,
            "user_id" => auth()->id(),
            "action" => "{$inventoryCollaborator->user->email} exited the inventory as " . strtoupper($inventoryCollaborator->role)
        ]);
    }


    /**
     * Handle the InventoryCollaborator "restored" event.
     */
    public function restored(InventoryCollaborator $inventoryCollaborator): void
    {
        //
    }

    /**
     * Handle the InventoryCollaborator "force deleted" event.
     */
    public function forceDeleted(InventoryCollaborator $inventoryCollaborator): void
    {
        //
    }
}
