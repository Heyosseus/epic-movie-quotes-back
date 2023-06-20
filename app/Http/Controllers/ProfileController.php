<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Mail\AccountActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
	public function update(UpdateProfileRequest $request, User $old_user)
	{
		$attr = $request->validated();

		$user = auth()->user();
		$user->update($attr);

		if ($request->hasFile('profile_picture')) {
			$profile_picture = $request->file('profile_picture');
			$filename = time() . '.' . $profile_picture->getClientOriginalExtension();
			$path = $profile_picture->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$user->profile_picture = $relativePath;
			$user->save();
		}
		//		Mail::to($old_user->email)->send(new AccountActivationMail($old_user));
		return response()->json(['user' => $user], 200);
	}
}
