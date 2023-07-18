<?php

namespace App\Http\Controllers\Notifications;

use App\Events\CommentNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\AddCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function index($quoteId): JsonResponse
	{
		$comments = Comment::with('quote', 'quote.movie', 'user')
			->where('quote_id', $quoteId)
			->latest()
			->get();

		return response()->json(['comments' => $comments], 200);
	}

	public function store(AddCommentRequest $request): JsonResponse
	{
		$attributes = $request->validated();

		$comment = Comment::create($attributes)->load('user');

		event(new CommentNotification($comment));
		return response()->json([
			'message' => 'Comment added successfully',
			'comment' => $comment,
		]);
	}
}
