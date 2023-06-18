<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
	public function checkSession(): JsonResponse
	{
		$isSessionActive = false;
		$isGoogleAuthenticated = session('google_authenticated') === true;

		if (Auth::check()) {
			$isSessionActive = true;
		}

		return response()->json([
			'isSessionActive'       => $isSessionActive,
			'isGoogleAuthenticated' => $isGoogleAuthenticated,
		]);
	}
}
