<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Mail\AccountActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
	public function register(AuthRegisterRequest $request) : object
	{

        $attr = $request->validated();
        $user = User::where('email', $attr['email'])->first();
        $user->createToken('authToken')->plainTextToken;

        if($user){
            return response()->json(['message' => 'User already exists!'], 400);
        }else{
            $newUser = User::create($attr);
            $token = $newUser->createToken('authToken')->plainTextToken;
        }

		Mail::to($newUser->email)->send(new AccountActivationMail($newUser));
        $newUser->email_verified_at = now();
		return response()->json(['user' => $newUser, 'token' => $token], 200);
	}

	public function login(AuthLoginRequest $request)
	{
        $attrs = $request->validated();
        $user = User::where('email', $attrs['email'])->first();
        if(!$user){
            return response()->json(['message' => 'User not found!'], 404);
        }
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 200);
	}

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out!'], 200);
    }
}
