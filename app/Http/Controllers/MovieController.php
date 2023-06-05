<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;
use http\Env\Request;

class MovieController extends Controller
{
	public function index()
	{
		$movie = Movie::orderBy('created_at', 'desc')->get();

		$query = Movie::query();
		if (request('search')) {
			$query->where('title', 'LIKE', '%' . request('search') . '%');
		}
		$movie = $query->get();
		return response()->json(['movie' => $movie], 200);
	}

	public function show(Movie $movie)
	{
		$movies = Movie::find($movie->id);
		return response()->json(['movie' => $movies], 200);
	}

	public function store(AddMovieRequest $request)
	{
		$attr = $request->all();

		$movie = Movie::create($attr);

		if ($request->hasFile('poster')) {
			$poster = $request->file('poster');
			$filename = time() . '.' . $poster->getClientOriginalExtension();
			$path = $poster->storeAs('public/storage/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$movie->poster = $relativePath;
			$movie->save();
		}
		return response()->json(['movie' => $movie], 200);
	}

	public function update(Request $request, $id)
	{
		$attr = $request->all();
		$movie = Movie::find($id);
		$movie->update($attr);
		return response()->json(['movie' => $movie], 200);
	}

	public function destroy($id)
	{
		Movie::destroy($id);
		return response()->json(['message' => 'Movie deleted successfully'], 200);
	}
}
