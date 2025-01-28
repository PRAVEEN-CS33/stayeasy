<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        $token = User::newRegister($validated);

        return response()->json( [
            'message' => 'Registered successfully. Please verify your email.', 
            'token'=> $token
        ]);
    }
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        $token = User::authenticate( $validated['email'], $validated['password'] );

        return response([
            'message'=> 'User Login successfully ',
            'token'=> $token
         ]);
    }
    public function logout(Request $request)
    {
        if($request->user()){
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message'=> 'User Loged out successfully'], 200);
        }
        return response()->json(['message'=> 'You are nit logged in'], 200);
    }

    //Email verification :-
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
