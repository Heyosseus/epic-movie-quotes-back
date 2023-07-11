<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

		SendEmailJob::dispatch($newUser);
		return response()->json(['user' => $newUser], 200);
	}

		public function login(AuthLoginRequest $request): object
		{
			try {
				$attrs = $request->validated();

				if (Auth::attempt($attrs)) {
					$user = Auth::user();

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

	public function logout(): object
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
