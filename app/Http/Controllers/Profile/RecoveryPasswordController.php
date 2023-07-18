<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\RecoveryPasswordRequest;
use App\Mail\RecoveryPasswordMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoveryPasswordController extends Controller
{
	public function store(Request $request): JsonResponse
	{
		$user = User::where('email', $request->email)->first();
		if ($user) {
			$token = str::random(60);
			$user->token = $token;
			$user->save();
			Mail::to($user->email)->send(new RecoveryPasswordMail($token));
		}

		return response()->json(['message' => 'email is sent!', 'user' => $user]);
	}

	public function update(RecoveryPasswordRequest $request, User $user): JsonResponse
	{
		$user = User::where('email', $request->email)->first();
		$user->password = $request->password;
		$user->update();

		$attributes = $request->validated();
		if (Auth::attempt($attributes)) {
			$user = Auth::user();
			auth()->login($user);
			return response()->json(['message' => 'Password is changed!', 'user' => $user]);
		}

		return response()->json(['message' => 'password is changed!', 'user' => $user]);
	}
}
