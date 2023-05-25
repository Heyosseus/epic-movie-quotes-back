<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Mail\AccountActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
	public function register(AuthRegisterRequest $request)
	{
		$user = User::create($request->validated());

		Mail::to($user->email)->send(new AccountActivationMail($user->token));
		$user->email_verified_at = now();
		return response()->json(['user' => $user], 200);
	}

	public function login(AuthLoginRequest $request)
	{
		$attributes = $request->only('email', 'password');

		if (auth()->attempt($attributes)) {
			return response()->json(['message' => 'Logged in']);
		} else {
			return response()->json(['message' => 'Invalid credentials'], 401);
		}
	}
}
