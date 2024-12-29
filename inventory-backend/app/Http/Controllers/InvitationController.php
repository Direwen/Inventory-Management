<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Inventory;
use App\Models\Invitation;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;

class InvitationController extends Controller
{

    use ApiResponseTrait;

    public function index(Inventory $inventory)
    {

        $invitations = $inventory->invitations()->latest()->paginate(10);

        return $this->successResponse(
            data: $invitations,
            message: "Paginated invitations of the inventory '{$inventory->name}'"
        );
    }

    public function indexForMe() {
        $invitations = Invitation::with(['inventory', 'inviter'])  // Eager load first
            ->where("invitee_email", auth()->user()->email)
            ->where("status", "pending")
            ->get();
    
        return $this->successResponse(
            data: $invitations ?? [],
            message: "Pending invitations for the user"
        );
    }
    
    

    public function show(Inventory $inventory, Invitation $invitation)
    {

    }

    public function store(StoreInvitationRequest $request, Inventory $inventory)
    {
        $details = $request->validated();
    
        try {

             // Check if the invitee is already a collaborator
            $existingCollaborator = $inventory->collaborators()
                ->whereHas('user', function ($query) use ($details) {
                    $query->where('email', $details['invitee_email']);
                })
                ->exists();

            // If the user is already a collaborator, return an error response
            if ($existingCollaborator) {
                return $this->errorResponse("The user is already a collaborator in this inventory.");
            }
            
            // Check for existing pending invitations, but allow new invitations if expired
            if ($this->hasActiveInvitation($inventory, $details['invitee_email'])) {
                return $this->errorResponse("An active invitation already exists for this invitee.");
            }
    
            // Create the new invitation
            $invitation = Invitation::create(array_merge($details, [
                "inviter_id" => auth()->id(),
                "inventory_id" => $inventory->id,
            ]));
    
            return $this->successResponse(
                data: $invitation,
                message: "Invitation has been sent."
            );
        } catch (Exception $e) {
            return $this->errorResponse("Failed to send the invitation: " . $e->getMessage());
        }
    }
    
    public function update(UpdateInvitationRequest $request, Inventory $inventory, Invitation $invitation)
    {
        $details = $request->validated();
    
        // Allow admin to cancel if the invitation is still active
        if ($this->isAdmin(auth()->id(), $inventory) && $details["status"] === "cancelled") {
            return $this->cancelInvitation($invitation);
        }
    
        // Check if the invitation is valid for updates (non-admin users)
        if (!$this->canUpdateInvitation($invitation)) {
            return $this->errorResponse("This invitation cannot be updated.");
        }

        try {
            DB::transaction(function () use ($invitation, $inventory, $details) {
                
                $isAllowed = $this->hasEnoughSpot($inventory);

                // Handle acceptance and collaborator addition
                if (($details["status"] == "accepted") && $isAllowed) {

                    // Update the invitation with the provided details
                    $invitation->update($details);

                    $this->handleAcceptance($inventory);

                } elseif (($details["status"] == "accepted") && !$isAllowed) {
                    $invitation->update([
                        "status" => "full"
                    ]);
                } else {

                    // Update the invitation with the provided details
                    $invitation->update($details);

                }
                
            });
    
            return $this->successResponse(
                data: $invitation,
                message: "Invitation has been updated."
            );
        } catch (Exception $e) {
            return $this->errorResponse("Failed to update the invitation: " . $e->getMessage());
        }
    }
    
    private function hasActiveInvitation(Inventory $inventory, string $inviteeEmail): bool
    {
        return Invitation::where([
            'inventory_id' => $inventory->id,
            'invitee_email' => $inviteeEmail,
            'status' => 'pending',
        ])
        ->where(function ($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        })
        ->exists();
    }
    
    private function canUpdateInvitation(Invitation $invitation): bool
    {
        return $invitation->status === "pending" && 
               (!$invitation->expires_at || now()->lessThanOrEqualTo($invitation->expires_at));
    }
    
    private function isAdmin(int $userId, Inventory $inventory): bool
    {
        $admin = $inventory->collaborators->firstWhere('role', 'Admin');
        return $admin && $admin->user_id === $userId;
    }
    
    private function cancelInvitation(Invitation $invitation)
    {
        $invitation->update(['status' => 'cancelled']);
    
        return $this->successResponse(
            data: $invitation,
            message: "Invitation has been cancelled."
        );
    }
    
    private function handleAcceptance(Inventory $inventory)
    {
        $inventory->collaborators()->create([
            "user_id" => auth()->id()
        ]);
    }
    
    private function hasEnoughSpot(Inventory $inventory)
    {
        return $inventory->collaborators->count() < 5;
    }

}
