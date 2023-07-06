<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quotes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
	public function index($movieId)
	{
		$quote = Quotes::with('comments', 'likes')->where('movie_id', $movieId)->orderBy('created_at', 'desc')->get();
		$quote->load('movie', 'user', 'comments', 'likes', 'comments.user');
		return QuoteResource::collection($quote);
	}

	public function searchQuotes(Request $request, $query)
	{
		$quotes = Quotes::where('body->en', 'LIKE', '%' . $query . '%')->orWhere('body->ka', 'LIKE', '%' . $query . '%')->get();
		return response()->json($quotes);
	}

	public function newsFeed(): JsonResponse
	{
		$quotes = Quotes::with('movie', 'user', 'comments', 'comments.user', 'likes')
			->orderBy('created_at', 'desc')
			->take(10)
			->get();

		$quotes->load('movie', 'user', 'comments', 'comments.user');

		return response()->json(['quotes' => $quotes], 200);
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
