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
		$user = User::create($request->validated());

		Mail::to($user->email)->send(new AccountActivationMail($user->token));
		$user->email_verified_at = now();
		return response()->json(['user' => $user], 200);
	}

	public function login(AuthLoginRequest $request)
	{
        Auth::once($request->validated());

        $result['Logged in'] = Auth::check();

        if($result['Logged in']){
            $result['token'] = Auth::user()->createToken('authToken')->plainTextToken;
        }
        return $result;
	}
}
