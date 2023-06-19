<?php

namespace App\Http\Controllers;

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
				return response()->json([
					'message'    => 'Like removed successfully',
				]);
			} else {
				return response()->json([
					'message' => 'Failed to remove like',
				], 500);
			}
		} else {
			$likes = Likes::create($attributes);

			if ($likes->save()) {
				return response()->json([
					'message'    => 'Like added successfully',
				]);
			} else {
				return response()->json([
					'message' => 'Like could not be added',
				], 500);
			}
		}
	}
}
