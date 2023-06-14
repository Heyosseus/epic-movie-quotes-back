<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		$movie = Movie::orderBy('created_at', 'desc')->get();

		$query = Movie::query();
		if (request('search')) {
			$query->where('title', 'LIKE', '%' . request('search') . '%');
		}
		$movie = $query->get();
		return response()->json(['movie' => $movie], 200);
	}

	public function show(Movie $movie): JsonResponse
	{
		return response()->json(['movie' => $movie], 200);
	}

	public function store(AddMovieRequest $request): JsonResponse
	{
		$attr = $request->all();

		$movie = Movie::create($attr);
		$genres = json_decode($request->input('genre'));

		$movie->genres()->attach($genres);

		if ($request->hasFile('poster')) {
			$poster = $request->file('poster');
			$filename = time() . '.' . $poster->getClientOriginalExtension();
			$path = $poster->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$movie->poster = $relativePath;
			$movie->save();
		}
		return response()->json(['movie' => $movie], 200);
	}

	public function update(AddMovieRequest $request, Movie $movie): JsonResponse
	{
		$attr = $request->validated();

		$movie->update($attr);
		if ($request->hasFile('poster')) {
			$poster = $request->file('poster');
			$filename = time() . '.' . $poster->getClientOriginalExtension();
			$path = $poster->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$movie->poster = $relativePath;
			$movie->save();
		}

		return response()->json(['movie' => $movie], 200);
	}

	public function destroy($id): JsonResponse
	{
		Movie::destroy($id);
		return response()->json(['message' => 'Movie deleted successfully'], 200);
	}
}
