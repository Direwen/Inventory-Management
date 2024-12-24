<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\User;
use Exception;
use Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            Notification::create([
                "user_id" => $user->id,
                "title" => "User Created",
                "message" => "Welcome, " . $user->email 
            ]);
        } catch (Exception $e) {
            Log::warning("somethign something");
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
