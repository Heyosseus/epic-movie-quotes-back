<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
	public function index(Request $request, Movie $movie): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		$this->authorize('index', Quote::class);
		if ($request->has('query')) {
			$query = $request->query('query');
			$quotes = Quote::with('movie', 'user', 'comments', 'likes')->where('body->en', 'LIKE', '%' . $query . '%')
				->orWhere('body->ka', 'LIKE', '%' . $query . '%')
				->get();
		} else {
			$perPage = $request->input('per_page', 2);
			$quotes = Quote::with('movie', 'user', 'comments', 'comments.user', 'likes')
				->orderBy('created_at', 'desc')
				->paginate($perPage);
		}
		$quotes->load('movie', 'user', 'comments', 'comments.user');
		return QuoteResource::collection($quotes);
	}

	public function store(AddQuoteRequest $request): JsonResponse
	{
		$attr = $request->all();
		$this->authorize('store', Quote::class);
		$quote = Quote::create($attr);

		if ($request->hasFile('thumbnail')) {
			$thumbnail = $request->file('thumbnail');
			$filename = time() . '.' . $thumbnail->getClientOriginalExtension();
			$path = $thumbnail->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$quote->thumbnail = $relativePath;
			$quote->save();
		}
		return response()->json(['quote' => $quote], 200);
	}

	public function show(Quote $quote): JsonResponse
	{
		$quote->load('movie', 'user', 'comments', 'likes', 'comments.user');
		return response()->json(['quote' => $quote], 200);
	}

	public function update(UpdateQuoteRequest $request, $quoteId): JsonResponse
	{
		$quote = Quote::find($quoteId);
		$this->authorize('update', $quote);
		$quote->body = $request->input('body');

		$quote->movie_id = $request->input('movie_id');

		if ($request->hasFile('thumbnail')) {
			$thumbnail = $request->file('thumbnail');
			$filename = time() . '.' . $thumbnail->getClientOriginalExtension();
			$path = $thumbnail->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$quote->thumbnail = $relativePath;
			$quote->save();
		}
		$quote->save();

		return response()->json(['quote' => $quote], 200);
	}

	public function destroy($id): JsonResponse
	{
		Quote::destroy($id);
		return response()->json(['message' => 'Quote deleted successfully'], 200);
	}
}
