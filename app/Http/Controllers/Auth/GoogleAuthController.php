<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirect(): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback()
	{
		try {
			$google_user = Socialite::driver('google')->user();
			$user = User::where('email', $google_user->getEmail())->first();

			if ($user) {
				Auth::login($user);
				session(['google_authenticated' => true]);
				return redirect(env('FRONTEND_URL') . '/news-feed');
			} else {
				$user = User::create([
					'name'              => $google_user->getName(),
					'email'             => $google_user->getEmail(),
					'google_id'         => $google_user->getId(),
					'email_verified_at' => now(),
				]);
				session(['google_authenticated' => true]);

				Auth::login($user);

				return redirect(env('FRONTEND_URL') . '/thanks-google');
			}
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}
}
