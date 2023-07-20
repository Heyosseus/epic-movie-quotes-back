<?php

namespace App\Http\Controllers\Notifications;

use App\Events\LikeNotification;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function store(Quote $quote): JsonResponse
	{
		$user = User::find(auth()->id());
		$existingLike = $quote->likes()->where('user_id', $user->id)->first();

		if ($existingLike) {
			if ($existingLike->user_id !== $user->id) {
				$quote->likes()->detach($user);

				return response()->json([
					'message' => 'Unlike successful',
					'like'    => $quote->likes()->count(),
				]);
			}
		} else {
			$quote->likes()->attach($user, ['likes' => true]);
			$like = $quote->likes()->where('user_id', $user->id)->first();
			event(new LikeNotification($like, $quote->id, 'like'));

			return response()->json([
				'message' => 'Liked successfully',
				'like'    => $quote->likes()->count(),
			]);
		}
		return response()->json([
			'like' => $quote->likes()->count(),
		], 200);
	}
}
