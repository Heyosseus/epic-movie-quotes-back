<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
	public function index(): JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		$user = auth()->user();

		if (!$user instanceof User) {
			return response()->json(['error' => 'User not found'], 404);
		}

		$query = $user->movies()
			->with('quotes', 'genres', 'user')
			->orderBy('created_at', 'desc');

		if (request('search')) {
			$query->where('title', 'LIKE', '%' . request('search') . '%');
		}

		$movies = $query->get();
		return  MovieResource::collection($movies);
	}

	public function allMovies(): JsonResponse
	{
		$movies = Movie::all();
		return response()->json(['movies' => $movies], 200);
	}

	public function searchMovies(Request $request, $query): JsonResponse
	{
		$movies = Movie::where('title->en', 'LIKE', '%' . $query . '%')->orWhere('title->ka', 'LIKE', '%' . $query . '%')->with('quotes')->get();
		return response()->json($movies);
	}

	public function show(Movie $movie): MovieResource
	{
		$movie->load('quotes', 'genres', 'user');
		return new MovieResource($movie);
	}

	public function store(AddMovieRequest $request)
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
		return new MovieResource($movie);
	}

	public function update(AddMovieRequest $request, Movie $movie)
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

		return new MovieResource($movie);
	}

	public function destroy($id): JsonResponse
	{
		Movie::destroy($id);
		return response()->json(['message' => 'Movie deleted successfully'], 200);
	}
}
