<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Traits\ApiResponseTrait;
use Hash;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    
    use ApiResponseTrait;

    public function update(UpdatePasswordRequest $request)
    {
        $details = $request->validated();
        $user = auth()->user();

        // Prevent Google users from updating passwords
        if (empty($user->password) && $user->google_id) {
            return $this->errorResponse("Password updates are not allowed for Google login users.", 403);
        }

        // Check if the old password matches
        if (!empty($user->password) && !$this->matchPassword($details["old_password"], $user->password)) {
            return $this->errorResponse("Wrong Password", 403);
        }

        // Hash and update the password
        $user->update([
            'password' => $details["password"]
        ]);

        return $this->successResponse(message: "Successfully updated the password.");
    }

    private function matchPassword(String $old_password, String $new_password)
    {
        return Hash::check($old_password, $new_password);
    }
}
