<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;

class UserController extends Controller
{

    use ApiResponseTrait;

    public function index()
    {
        
    }

    public function show()
    {
        return $this->successResponse(
            data: auth()->user(),
            message: "Currently Authenticated User"
        );
    }

    public function update(UpdateUserRequest $request)
    {
        $details = $request->validated();

        try {
            
            $user = auth()->user();

            $user->update($details);

            return $this->successResponse(
                data: $user,
                message: "User profile updated successfully"
            );

        } catch (Exception $e) {

            return $this->errorResponse($e->getMessage());
        }
        
    }

    public function destroy()
    {
        try {
            $user = auth()->user();
    
            // Soft delete the user
            $user->delete();
    
            // Revoke all personal access tokens for the user
            $user->tokens()->delete();
    
            return $this->successResponse(
                message: "User account has been deactivated and logged out"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function restore(RestoreUserRequest $request)
    {
        $details = $request->validated();

        try {
            $user = User::withTrashed()->where('email', $details["email"])->first();

            //Make sure this user record is soft-deleted
            if (!$user->trashed()) return $this->errorResponse("This User Account doesn't require the restoration");

            $user->restore();

            return $this->successResponse(
                message: "User account successfully restored",
                data: $user
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}
