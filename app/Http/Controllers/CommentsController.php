<?php

namespace App\Http\Controllers;

use App\Events\CommentNotification;
use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentsController extends Controller
{
	public function index($quoteId): JsonResponse
	{
		$comments = Comment::with('quote', 'quote.movie', 'user')
			->where('quote_id', $quoteId)
			->orderBy('created_at', 'asc')
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
