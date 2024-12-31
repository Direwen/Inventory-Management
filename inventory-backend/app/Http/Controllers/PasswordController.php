<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpVerificationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Password;
use Session;

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

    public function sendResetLink(SendResetLinkRequest $request)
    {
        $details = $request->validated();

        try {
    
            Password::sendResetLink($details['email']);
    
            return $this->successResponse(
                message: 'Password Reset Link has been sent'
            );

        } catch (Exception $err) {
            
            return $this->errorResponse($err->getMessage());

        }
    }

    public function redirectToResetPage(string $token)
    {
        return redirect()->away(env('FRONTEND_URL') . '/reset-password/' . $token);
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
    
            Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, $password) {
                    $user->update([
                        'password' => Hash::make($password)
                    ]);
    
                    $user->save();
                }
            );
    
            return $this->successResponse(
                message: "Successfully Update the password"
            );
            

        } catch (Exception $err) {
         
            return $this->errorResponse($err->getMessage());

        }
    }

    private function matchPassword(string $old_password, string $new_password)
    {
        return Hash::check($old_password, $new_password);
    }

    private function generateOtp()
    {
        return rand(100000, 900000);
    }



    //Forgot Password -> Enter Email -> Send OTP to that Email -> Enter OTP -> If correct, ask the user to enter new psw
}
