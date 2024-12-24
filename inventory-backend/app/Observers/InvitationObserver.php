<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Models\Log;

class InvitationObserver
{

    /**
     * Handle the Invitation "created" event.
     */
    public function created(Invitation $invitation): void
    {
        Log::create([
            "inventory_id" => $invitation->inventory_id,
            "user_id" => auth()->id(),
            "action" => "Invitation sent to {$invitation->invitee_email} by " . auth()->user()->email
        ]);
    }

    /**
     * Handle the Invitation "updated" event.
     */
    public function updated(Invitation $invitation): void
    {
        $changes = $invitation->getChanges(); // Get only the updated attributes

        // Log status updates with specific messaging
        if (isset($changes['status'])) {
            $statusMessages = [
                "accepted" => "{$invitation->invitee_email} accepted the invitation.",
                "declined" => "{$invitation->invitee_email} declined the invitation.",
                "cancelled" => "Invitation cancelled by admin " . auth()->user()->email . ".",
                "expired" => "Invitation expired.",
                "full" => "Invitation marked as full. No more users can join.",
            ];

            $message = $statusMessages[$changes['status']] ?? "Invitation status changed to {$changes['status']}.";
        } else {
            $message = "Invitation details updated by " . auth()->user()->email . ".";
        }

        Log::create([
            "inventory_id" => $invitation->inventory_id,
            "user_id" => auth()->id(),
            "action" => $message
        ]);
    }

    /**
     * Handle the Invitation "deleted" event.
     */
    public function deleted(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "restored" event.
     */
    public function restored(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "force deleted" event.
     */
    public function forceDeleted(Invitation $invitation): void
    {
        //
    }
}
