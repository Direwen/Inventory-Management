<?php

namespace App\Http\Controllers;


use App\Http\Requests\DestroyCollaboratorRequest;
use App\Http\Requests\StoreCollaboratorRequest;
use App\Http\Requests\UpdateCollaboratorRequest;
use App\Models\Inventory;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;

class InventoryCollaboratorController extends Controller
{
    use ApiResponseTrait;

    public function show(Inventory $inventory) {
        // Eager load the 'user' relationship along with 'collaborators'
        $inventoryWithUsers = $inventory->load('collaborators.user');
    
        return $this->successResponse(
            data: $inventoryWithUsers->collaborators,
            message: "Fetched collaborators and their users for the inventory"
        );
    }
    

    public function store(StoreCollaboratorRequest $request, Inventory $inventory)
    {

        $details = $request->validated();

        $collaborators = $inventory->collaborators;

        // Ensure the current auth user is the admin of this inventory
        if ($response = $this->ensureAdmin($collaborators)) {
            return $response;
        }

        // Ensure total collaborators do not exceed 5
        if ($collaborators->count() >= 5) {
            return $this->errorResponse("Each inventory can only have up to 5 collaborators.");
        }

        // Check if the user is already a collaborator
        if ($collaborators->contains('user_id', $details['user_id'])) {
            return $this->errorResponse("This user is already a collaborator for this inventory.");
        }

        try {
            $inventory->collaborators()->create([
                'user_id' => $details["user_id"],
                'role' => 'employee'
            ]);

            return $this->successResponse(
                data: $inventory->load('collaborators'),
                message: "Collaborator added successfully."
            );
        } catch (Exception $e) {
            return $this->errorResponse("Error adding collaborator: " . $e->getMessage());
        }
    }

    public function update(UpdateCollaboratorRequest $request, Inventory $inventory)
    {

        $details = $request->validated();
        $collaborators = $inventory->collaborators;

        // Ensure the current auth user is the admin of this inventory
        if ($response = $this->ensureAdmin($collaborators)) {
            return $response;
        }

        // Find the collaborator to update
        $collaboratorToUpdate = $this->getCollaboratorToUpdate($collaborators, $details['user_id']);
        if (!$collaboratorToUpdate) {
            return $this->errorResponse("Collaborator not found");
        }

        // Handle the role update logic
        $newRole = $details['role'];

        DB::beginTransaction();

        try {
            if ($response = $this->handleRoleChange($collaboratorToUpdate, $collaborators->firstWhere('role', 'Admin'), $newRole, $collaborators)) {
                return $response;
            }

            DB::commit();

            return $this->successResponse(
                data: $inventory->load('collaborators'),
                message: "Collaborator updated successfully."
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Error updating collaborator: " . $e->getMessage());
        }
    }

    public function destroy(DestroyCollaboratorRequest $request, Inventory $inventory)
    {
        $details = $request->validated();

        DB::beginTransaction();

        try {

            // Fetch all collaborators at once
            $collaborators = $inventory->collaborators;

            // Ensure the current user is the admin of this inventory
            if ($response = $this->ensureAdmin($collaborators)) {
                return $response;
            }

            // Ensure there are other users in this inventory
            if ($collaborators->count() <= 1) {
                return $this->errorResponse("Invite others to keep this inventory running or delete the inventory itself");
            }

            // Find the manager
            $manager = $collaborators->firstWhere('role', 'Manager');

            // Find the collaborator to delete
            $collaboratorToDelete = $collaborators->firstWhere('user_id', $details['user_id']);

            // If the collaborator to delete is not found
            if (!$collaboratorToDelete) {
                return $this->errorResponse("Collaborator not found");
            }

            // Ensure the admin cannot leave without a manager to replace them
            if (strcasecmp($collaboratorToDelete->role, 'admin') === 0 && empty($manager)) {
                return $this->errorResponse("Cannot delete the admin without a manager to replace them.");
            }

            // Promote the manager to admin if deleting the current admin
            if (strcasecmp($collaboratorToDelete->role, 'admin') === 0 && !empty($manager)) {
                $manager->update(['role' => 'admin']);
            }
            // Delete the collaborator
            $collaboratorToDelete->delete();

            DB::commit();

            return $this->successResponse(
                data: $inventory->load('collaborators'),
                message: "Collaborator removed successfully."
            );
        } catch (Exception $e) {

            DB::rollBack();
            
            return $this->errorResponse("Error removing collaborator: " . $e->getMessage());
        }
    }

    // Helper method to ensure user is authorized as admin
    private function ensureAdmin($collaborators)
    {
        $admin = $collaborators->firstWhere('role', 'Admin');
        if (auth()->id() != $admin->user_id) {
            return $this->errorResponse("You are not authorized for this action");
        }
        return null;
    }

    // Helper method to get the collaborator to update
    private function getCollaboratorToUpdate($collaborators, $userId)
    {
        return $collaborators->firstWhere('user_id', $userId);
    }

    // Helper method to handle role changes
    private function handleRoleChange($collaborator, $admin, $newRole, $collaborators)
    {
        switch (strtolower($collaborator->role)) {
            case 'employee':
                if ($newRole === 'manager') {
                    $collaborator->update(['role' => 'manager']);
                } elseif ($newRole === 'admin') {
                    $admin->update(['role' => 'employee']);
                    $collaborator->update(['role' => 'admin']);
                } else {
                    return $this->errorResponse("Invalid role change.");
                }
                break;

            case 'manager':
                if ($newRole === 'admin') {
                    $admin->update(['role' => 'manager']);
                    $collaborator->update(['role' => 'admin']);
                } elseif ($newRole === 'employee') {
                    $collaborator->update(['role' => 'employee']);
                } else {
                    return $this->errorResponse("Invalid role change.");
                }
                break;

            case 'admin':
                if ($newRole === 'manager') {
                    $manager = $collaborators->firstWhere('role', 'Manager');
                    if (!$manager) {
                        return $this->errorResponse("No manager available to promote to admin.");
                    }
                    $manager->update(['role' => 'admin']);
                    $collaborator->update(['role' => 'manager']);
                } elseif ($newRole === 'employee') {
                    $manager = $collaborators->firstWhere('role', 'Manager');
                    if (!$manager) {
                        return $this->errorResponse("Cannot remove admin without a manager to promote to admin.");
                    }
                    $manager->update(['role' => 'admin']);
                    $collaborator->update(['role' => 'employee']);
                } else {
                    return $this->errorResponse("Invalid role change.");
                }
                break;

            default:
                return $this->errorResponse("Invalid role for collaborator.");
        }

        return null;
    }
}
