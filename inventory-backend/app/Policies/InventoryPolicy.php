<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InventoryPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Inventory $inventory): bool
    {
        return $inventory->collaborators->contains('user_id', $user->id);
    }
    
    public function update(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
    
        // Get Admin and Manager collaborators
        $admin = $collaborators->firstWhere('role', 'Admin');
        $manager = $collaborators->firstWhere('role', 'Manager');

        // Check if the user matches the Admin or Manager roles
        $isAdmin = $admin && $user->id === $admin->user_id;
        $isManager = $manager && $user->id === $manager->user_id;
    
        return $isAdmin || $isManager;
    }
    
    public function delete(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
        $admin = $collaborators->firstWhere('role', 'Admin');
        return $user->id === $admin->user_id;
    }

    public function manageRoles(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
        $admin = $collaborators->firstWhere('role', 'Admin');
        return $user->id === $admin->user_id;
    }

    public function storeProduct(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
    
        // Get Admin and Manager collaborators
        $admin = $collaborators->firstWhere('role', 'Admin');
        $manager = $collaborators->firstWhere('role', 'Manager');
    
        // Check if the user matches the Admin or Manager roles
        $isAdmin = $admin && $user->id === $admin->user_id;
        $isManager = $manager && $user->id === $manager->user_id;
    
        return $isAdmin || $isManager;
    }

    public function updateProduct(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
    
        // Get Admin and Manager collaborators
        $admin = $collaborators->firstWhere('role', 'Admin');
        $manager = $collaborators->firstWhere('role', 'Manager');

        // Check if the user matches the Admin or Manager roles
        $isAdmin = $admin && $user->id === $admin->user_id;
        $isManager = $manager && $user->id === $manager->user_id;
    
        return $isAdmin || $isManager;
    }

    public function destroyProduct(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
        $admin = $collaborators->firstWhere('role', 'Admin');
        return $user->id === $admin->user_id;
    }

    public function viewProduct(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
        return $collaborators->firstWhere('user_id', $user->id) ? true : false;
    }


    public function adjustStock(User $user, Inventory $inventory): bool
    {
        $collaborators = $inventory->collaborators;
    
        // Get Admin and Manager collaborators
        $admin = $collaborators->firstWhere('role', 'Admin');
        $manager = $collaborators->firstWhere('role', 'Manager');

        // Check if the user matches the Admin or Manager roles
        $isAdmin = $admin && $user->id === $admin->user_id;
        $isManager = $manager && $user->id === $manager->user_id;
    
        return $isAdmin || $isManager;
    }

    public function storeInvitation(User $user, Inventory $inventory)
    {
        $collaborators = $inventory->collaborators;
        $admin = $collaborators->firstWhere('role', 'Admin');
        return $user->id === $admin->user_id;
    }

    public function updateInvitation(User $user, Inventory $inventory, Invitation $invitation)
    {

        if ($invitation->status != "pending") return false;

        $collaborators = $inventory->collaborators;
        $admin = $collaborators->firstWhere('role', 'Admin');

        $invitee_email = $invitation->invitee_email;

        return ($user->id === $admin->user_id) || ($user->email === $invitee_email);
    }
    
}
