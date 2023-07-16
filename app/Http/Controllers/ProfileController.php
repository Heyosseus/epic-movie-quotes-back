<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Mail\NewEmailMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
	public function update(UpdateProfileRequest $request): JsonResponse
	{
		$attributes = $request->validated();

		$user = auth()->user();

		$oldEmail = $user->email;
		$user->update($attributes);

		if ($user->email !== $oldEmail) {
			Mail::to($user->email)->send(new NewEmailMail($user));
			$user->markEmailAsVerified();
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
