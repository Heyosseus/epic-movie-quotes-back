<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;

class AccountActivationController extends Controller
{
	public function create($token): \Illuminate\Http\JsonResponse
	{
		$user = User::where(['token' => $token])->first();
		if ($user) {
			$user->markEmailAsVerified();
			$user->save();
			return response()->json(['message' => 'Success! account is activated!']);
		}
		return response()->json(['message' => 'Invalid token!']);
	}
}
