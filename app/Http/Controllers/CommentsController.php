<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		$user = auth()->user();
		if (!$user instanceof User) {
			return response()->json(['error' => 'User not found'], 404);
		}
		$comments = $user->comments()->with('quote', 'quote.movie', 'quote.movie.user')->orderBy('created_at', 'asc')->get();

		return response()->json(['comments' => $comments], 200);
	}

	public function store(Request $request)
	{
		$request->validate([
			'quote_id' => 'required',
			'content'  => 'required|string|max:255',
		]);

		$comment = new Comments();
		$comment->quote_id = $request->quote_id;
		$comment->user_id = $request->user()->id;
		$comment->content = $request->input('content');
		if ($comment->save()) {
			return response()->json([
				'success' => true,
				'message' => 'Comment added successfully',
				'comment' => $comment,
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Comment could not be added',
			], 500);
		}
	}
}
