<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\AuthLoginRequest;
use App\Http\Requests\auth\AuthRegisterRequest;
use App\Mail\AccountActivationMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
	public function register(AuthRegisterRequest $request): JsonResponse
	{
		$attributes = $request->validated();

		$newUser = User::create($attributes);

		Mail::to($newUser->email)->send(new AccountActivationMail($newUser));
		$newUser->markEmailAsVerified();
		$newUser->save();
		return response()->json(['user' => $newUser], 200);
	}

		public function login(AuthLoginRequest $request): JsonResponse
		{
			try {
				if (Auth::attempt($request->only('email', 'name', 'password'), $request->remember_me)) {
					$user = Auth::user();
					if ($request->remember_me) {
						Cookie::queue(Cookie::make('email', $request->email, 60 * 24 * 2)->withHttpOnly(false));
						Cookie::queue(Cookie::make('password', $request->password, 60 * 24 * 2)->withHttpOnly(false));
					}

					return response()->json(['user' => $user], 200);
				} else {
					throw ValidationException::withMessages([
						'email'    => 'Email credentials are incorrect.',
						'password' => 'Password credentials are incorrect.',
					]);
				}
			} catch (ValidationException $e) {
				return response()->json(['errors' => $e->errors()], 401);
			}
		}

	public function getDecryptedCredentials(Request $request): JsonResponse
	{
		$email = $request->cookie('email');
		$password = $request->cookie('password');

		return response()->json(['email' => $email, 'password' => $password]);
	}

	public function logout(): JsonResponse
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
