<?php

namespace App\Http\Controllers;

use App\Models\User;

class AccountActivationController extends Controller
{
	public function create($token)
	{
		$user = User::where(['token' => $token])->first();
		if ($user) {
			$email = $user->email;
			$user->email_verified_at = now();
			$user->save();
			return response()->json(['message' => 'Success! account is activated!']);
		}
		return response()->json(['message' => 'Invalid token!']);
	}
}
