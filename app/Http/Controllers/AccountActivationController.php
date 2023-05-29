<?php

namespace App\Http\Controllers;

use App\Models\User;

class AccountActivationController extends Controller
{
	public function create($token)
	{
		$user = User::where(['token' => $token, 'is_verified' => 0])->first();
		if ($user) {
			$email = $user->email;
			return response()->json(['message' => 'Success! account is activated!']);
		}
		return response()->json(['message' => 'Invalid token!']);
	}
}
