<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecoveryPasswordRequest;
use App\Mail\RecoveryPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoveryPasswordController extends Controller
{
	public function store(Request $request)
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

	public function update(RecoveryPasswordRequest $request)
	{
		$user = User::where('email', $request->email)->first();
		if ($user) {
			$user->password = $request->password;
			$user->save();

			$attrs = $request->validated();
			if (Auth::attempt($attrs)) {
				$user = Auth::user();
				auth()->login($user);
				return response()->json(['message' => 'Password is changed!', 'user' => $user]);
			}
		}

		return response()->json(['message' => 'password is changed!', 'user' => $user]);
	}
}
