<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Models\Quotes;
use Illuminate\Http\Request;
use League\CommonMark\Extension\SmartPunct\Quote;

class QuotesController extends Controller
{
	public function index($movieId)
	{
		$quote = Quotes::where('movie_id', $movieId)
			->orderBy('created_at', 'desc')
			->get();

		return response()->json(['quote' => $quote], 200);
	}

	public function store(AddQuoteRequest $request)
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

	public function show($id)
	{
		$quote = Quote::findOrFail($id);
	}

	public function edit($id)
	{
		$quote = Quote::findOrFail($id);
	}

	public function update($id, Request $request)
	{
		$quote = Quote::findOrFail($id);
		$quote->body = $request->body;
		$quote->save();
	}

	public function destroy($id)
	{
		$quote = Quote::findOrFail($id);

		$quote->delete();
	}
}
