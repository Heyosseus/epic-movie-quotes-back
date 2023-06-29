<?php

namespace App\Http\Controllers;

use App\Events\LikeNotification;
use App\Http\Requests\AddLikesRequest;
use App\Models\Likes;
use Illuminate\Http\JsonResponse;

class LikesController extends Controller
{
	public function store(AddLikesRequest $request): JsonResponse
	{
		$attributes = $request->validated();
		$quoteId = $request->quote_id;
		$userId = $request->user()->id;

		$existingLike = Likes::where('quote_id', $quoteId)->where('user_id', $userId)->first();

		if ($existingLike) {
			if ($existingLike->delete()) {
				event(new LikeNotification($existingLike, false));

				return response()->json([
					'message'    => 'Unlike successful',
				]);
			} else {
				return response()->json([
					'message' => 'Failed to remove like',
				], 500);
			}
		} else {
			$like = Likes::create($attributes);
			event(new LikeNotification($like));
			if ($like->save()) {
				return response()->json([
					'message'    => 'Like successful',
				]);
			} else {
				return response()->json([
					'message' => 'Failed to add like',
				], 500);
			}
		}
	}
}
