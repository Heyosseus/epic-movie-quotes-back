<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirect()
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback()
	{
		try {
			$google_user = Socialite::driver('google')->user();
			$user = User::where('google_id', $google_user->getId())->first();

			if (!$user) {
				$user = User::where('email', $google_user->getEmail())->first();

				if ($user) {
					Auth::login($user);
					session(['google_authenticated' => true]);
					return redirect('http://localhost:5173/news-feed');
				} else {
					$user = User::create([
						'name'              => $google_user->getName(),
						'email'             => $google_user->getEmail(),
						'google_id'         => $google_user->getId(),
						'email_verified_at' => now(),
					]);
					session(['google_authenticated' => true]);

					Auth::login($user);
				}
			} else {
				session(['google_authenticated' => true]);
				Auth::login($user);
			}

			return redirect(env('FRONTEND_URL') . '/thank-you');
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}
}
