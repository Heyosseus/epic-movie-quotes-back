<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Mail\AccountActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
	public function register(AuthRegisterRequest $request): object
	{
		$attr = $request->validated();
		$user = User::where('email', $attr['email'])->first();

		if ($user) {
			return response()->json(['message' => 'User already exists!'], 400);
		} else {
			$newUser = User::create($attr);
		}

		Mail::to($newUser->email)->send(new AccountActivationMail($newUser));
		$newUser->email_verified_at = now();
		return response()->json(['user' => $newUser], 200);
	}

	public function login(AuthLoginRequest $request)
	{
		$attrs = $request->validated();

		if (Auth::attempt($attrs)) {
			$user = Auth::user();
			Auth::login($user);
			return response()->json(['user' => $user], 200);
		}

		return response()->json(['message' => 'Invalid credentials'], 401);
	}

	public function logout()
	{
		try {
			Auth::guard('web')->logout();
			return response()->json(['message' => 'Logged out!'], 200);
		} catch (\Exception $e) {
			Log::error('Logout error: ' . $e->getMessage());
			return response()->json(['message' => 'Error occurred during logout'], 500);
		}
	}
}
