<?php

namespace App\Http\Controllers;

use App\Events\LikeNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Models\Quotes;

class LikesController extends Controller
{
	public function store(Quotes $quote, User $user): JsonResponse
	{
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

			event(new LikeNotification($like));

			return response()->json([
				'message' => 'Liked successfully',
				'like'    => $quote->likes()->count(),
			]);
		}
		return response()->json([
			'like' => $quote->likes()->count(),
		], 500);
	}
}
