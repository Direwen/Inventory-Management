<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\ApiResponseTrait;
use Auth;
use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Exception;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function signup(StoreUserRequest $request)
    {
        try {
            // Validated data
            $details = $request->validated();

            // Store user record
            DB::transaction(function () use ($details) {
                $user = User::create([
                    'email' => $details["email"],
                    'password' => $details["password"]
                ]);

                event(new Registered($user));
            });

            return $this->successResponse(null, "User Record Created", 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $details = $request->validated();

            $user = User::withTrashed()->where('email', $details["email"])->first();

            if (empty($user)) return $this->errorResponse("User not found");

            if ($user->trashed()) return $this->errorResponse("Please Reactivated the account first");

            if (!$user->hasVerifiedEmail()) {

                $user->sendEmailVerificationNotification();

                return $this->errorResponse("Please Verify the email first");
            }

            if (!Auth::attempt([
                "email" => $details["email"],
                "password" => $details["password"]
            ], $details["remember"] ?? false)) {
                return $this->errorResponse("Wrong Credentials", 400);
            }

            $token = $user->createToken("sessionToken")->plainTextToken;

            return $this->successResponse([
                'token' => $token,
                'user' => $user
            ], "User Successfully Logged in", 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();

            return $this->successResponse(null, "Logged Out", 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Verify email
    public function verify($userId, Request $request)
    {
        try {
            if (!$request->hasValidSignature()) {
                return $this->errorResponse("Expired URL provided", 401);
            }

            $user = User::findOrFail($userId);

            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            // return $this->successResponse(null, "Email Verified", 200);
            return redirect()->away(env('FRONTEND_URL') . '/?status=success');
        } catch (Exception $e) {
            return redirect()->away(env('FRONTEND_URL') . '/?status=failed');
        }
    }

    // Resend email verification notification
    public function resend(Request $request)
    {
        try {
            if (!$request->has('email')) {
                return $this->errorResponse('Email is required', 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse("There's no account created with this email yet", 400);
            }

            if ($user->hasVerifiedEmail()) {
                return $this->errorResponse("Email is already verified", 400);
            }

            $user->sendEmailVerificationNotification();

            return $this->successResponse(null, "Verification Link has been sent", 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
