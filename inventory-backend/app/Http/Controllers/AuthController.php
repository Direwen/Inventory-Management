<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseUserEmailRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Mail\SendUserReactivationToken;
use App\Traits\ApiResponseTrait;
use Auth;
use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Mail;
use Str;

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

            if (empty($user))
                return $this->errorResponse("User not found");

            if ($user->trashed())
                return $this->errorResponse("Please Reactivated the account first");

            if (!$user->hasVerifiedEmail()) {

                $user->sendEmailVerificationNotification();

                return $this->errorResponse("Please Verify the email first");
            }

            if (
                !Auth::attempt([
                    "email" => $details["email"],
                    "password" => $details["password"]
                ], $details["remember"] ?? false)
            ) {
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

    // Redirect Google Oauth
    public function getRedirectLink(Request $request)
    {
        $link = $request->query('link');

        $redirectUrl = "";
        if ($link) {
            $redirectUrl = Socialite::driver('google')
                ->stateless()
                ->with(['prompt' => 'select_account'])
                ->redirect()
                ->getTargetUrl();
        }

        $redirectUrl = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return $this->successResponse(
            data: $redirectUrl,
            message: "Google Oauth Redirect Link"
        );
    }

    // Handle Google Auth Callback
    public function handleThirdPartyCallback(Request $request)
    {
        try {
            $thirdPartyUserData = Socialite::driver('google')->stateless()->user();
            $user = User::withTrashed()->where('email', $thirdPartyUserData->getEmail())->first();
            
            if (auth()->check()) {
                $existing_user = User::where('google_id', $thirdPartyUserData->getId())->where('id', '!=', auth()->id())->first();
                $auth_user = auth()->user();
                if ($existing_user) return $this->errorResponse("This Google account is already linked to another user.");
                if (empty($auth_user->google_id)) {
                    $auth_user->update(['google_id' => $thirdPartyUserData->getId()]);
                    return $this->successResponse(
                        data: [
                            'user' => $auth_user
                        ],
                        message: "Google Account Linked Successfully"
                    );
                } else {
                    return $this->errorResponse("This account needs to be unlinked first to be able to connect the other Google Account");
                }            
            }

            if (!$user) {
                // If user is not created yet
                $user = User::create([
                    'name' => $thirdPartyUserData->getName(),
                    'email' => $thirdPartyUserData->getEmail(),
                    'google_id' => $thirdPartyUserData->getId(),
                    'password' => null,
                ]);
                $user->markEmailAsVerified();
                $user->save();

            } elseif ($user->trashed()) {
                // If user is already created and Deactivated
                return $this->errorResponse("Account is deactivated. Please contact support.");
            } elseif ($user->google_id && ($user->google_id !== $thirdPartyUserData->getId())) {
                // If user is created and connected to a different Gmail Account
                return $this->errorResponse("This email is linked to another Google account.");
            } elseif (!$user->hasVerifiedEmail()) {
                // If user is already created but not verified
                $user->markEmailAsVerified();
                $user->google_id = $thirdPartyUserData->getId();
                $user->save();
            } else {
                // Update google_id if not already connected
                $user->update([
                    'google_id' => $thirdPartyUserData->getId()
                ]);
            }

            Auth::login($user);
            $token = $user->createToken('sessionToken')->plainTextToken;

            return $this->successResponse(
                data: [
                    'token' => $token,
                    'user' => $user
                ],
                message: "Successfully authenticated"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Unlinke
    public function unlinkGoogleAccount(Request $request)
    {
        $user = auth()->user();

        // Ensure user has a password before proceeding
        if (empty($user->password)) {
            return $this->errorResponse("You need to set a password before unlinking your Google account.");
        }

        // Proceed to unlink Google account (example)
        $user->google_id = null;
        $user->save();

        return $this->successResponse(
            data: $user,
            message: "Google account unlinked successfully."
        );
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

    //Restore the user
    public function sendReactivationToken(BaseUserEmailRequest $request)
    {
        $details = $request->validated();

        try {
            $user = User::withTrashed()->where('email', $details["email"])->first();

            //Make sure this user record is soft-deleted
            if (!$user->trashed())
                return $this->errorResponse("This User Account doesn't require the restoration");

            DB::transaction(function () use ($user) {
                $token = Str::random(10);
                $user->update([
                    "reactivation_token" => $token,
                    "reactivation_token_expires_at" => now()->addMinutes(5)
                ]);

                Mail::to($user->email)->send(new SendUserReactivationToken($user, $token));
            });

            return $this->successResponse(
                data: $user,
                message: "Reactivatin Token Sent"
            );
        } catch (Exception $err) {
            return $this->errorResponse($err->getMessage());
        }
    }
}
