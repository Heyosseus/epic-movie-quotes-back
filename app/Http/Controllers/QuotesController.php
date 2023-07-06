<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use App\Models\Quotes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
	public function index(Request $request, Movie $movie)
	{
		//		$quote->load('movie', 'user', 'comments', 'likes', 'comments.user');

		if ($request->has('query')) {
			$query = $request->query('query');
			$quotes = Quotes::where('body->en', 'LIKE', '%' . $query . '%')
				->orWhere('body->ka', 'LIKE', '%' . $query . '%')
				->get();
		} else {
			$quotes = Quotes::with('movie', 'user', 'comments', 'comments.user', 'likes')
				->orderBy('created_at', 'desc')
				->paginate(5);
		}
		$quotes->load('movie', 'user', 'comments', 'comments.user');
		return QuoteResource::collection($quotes);
	}

	public function store(AddQuoteRequest $request): JsonResponse
	{
		$attr = $request->all();

		$quote = Quotes::create($attr);

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

	public function show(Quotes $quote)
	{
		$quote->load('movie', 'user', 'comments', 'likes', 'comments.user');
		return response()->json(['quote' => $quote], 200);
	}

	public function update(UpdateQuoteRequest $request, $quoteId)
	{
		$quote = Quotes::find($quoteId);

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

	public function destroy($id)
	{
		Quotes::destroy($id);

		return response()->json(['message' => 'Quote deleted successfully'], 200);
	}
}
