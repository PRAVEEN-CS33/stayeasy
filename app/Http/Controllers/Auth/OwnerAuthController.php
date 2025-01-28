<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginOwnersRequest;
use App\Http\Requests\RegisterOwnersRequest;
use App\Http\Requests\StoreOwnersRequest;
use App\Models\Owners;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerAuthController extends Controller
{
    //register
    public function register(RegisterOwnersRequest $request)
    {
            $validated = $request->validated();
            $token = Owners::registerNewOwner($validated);

            return response()->json([ 
                'message' => 'owner registered successfully. please verify the email', 
                'token'=> $token
            ]);
    }

    //login
    public function login(LoginOwnersRequest $request)
    {
        $validated = $request->validated();
        $token = Owners::authenticate($validated['email'], $validated['password']);

        return response()->json([
            'message' => 'owner login successfully',
            'token'=> $token
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Owner logged out successfully'
            ], 200);
        }
        return response()->json([
            'message' => 'No logged-in user found'
        ], 401);
    }  

    //email verification
    public function emailVerifyNotice(){
        return response()->json(['message'=> 'Email verification required.'],200);
    }

    public function verifyEmail(EmailVerificationRequest $request){
        $request->fulfill();
        return response()->json(['message' => 'Email verified successfully.']);
    }   

    public function resendEmailVerification(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json(['message'=> 'Email already verified.'],200);
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email sent.']);
    }
}
