<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Jobs\UpdateEmailJob;
use App\Models\User;

class ProfileController extends Controller
{
	public function update(UpdateProfileRequest $request, User $old_user)
	{
		$attr = $request->validated();

		$user = auth()->user();

		$oldEmail = $user->email;

		$user->update($attr);

		if ($user->email !== $oldEmail) {
			UpdateEmailJob::dispatch($user);
		}

		if ($request->hasFile('profile_picture')) {
			$profile_picture = $request->file('profile_picture');
			$filename = time() . '.' . $profile_picture->getClientOriginalExtension();
			$path = $profile_picture->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$user->profile_picture = $relativePath;
			$user->save();
		}

		return response()->json(['user' => $user], 200);
	}
}
