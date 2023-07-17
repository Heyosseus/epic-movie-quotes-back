<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
	public function index(Request $request): JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		$user = auth()->user();
		$this->authorize('index', Movie::class);

		$query = $user->movies()
			->with('quotes', 'genres', 'user')
			->orderBy('created_at', 'desc');

		if (request('search')) {
			$searchQuery = request('search');
			$this->searchMovies($query, $searchQuery);
		}

		$movies = $query->get();

		$movies = $this->searchMoviesIfEmpty($request, $movies);

		return MovieResource::collection($movies);
	}

	public function searchMovies($query, $searchQuery): void
	{
		$query->where(function ($q) use ($searchQuery) {
			$q->where('title->en', 'LIKE', '%' . $searchQuery . '%')
				->orWhere('title->ka', 'LIKE', '%' . $searchQuery . '%');
		});
	}

	public function searchMoviesIfEmpty($request, $movies)
	{
		if ($request->has('search') && $movies->isEmpty()) {
			$searchQuery = $request->input('search');
			$movies = Movie::where(function ($query) use ($searchQuery) {
				$query->where('title->en', 'LIKE', '%' . $searchQuery . '%')
					->orWhere('title->ka', 'LIKE', '%' . $searchQuery . '%');
			})
				->with('quotes')
				->get();
		}

		return $movies;
	}

	public function show(Movie $movie): MovieResource
	{
		$movie->load('quotes', 'genres', 'user', 'quotes.comments', 'quotes.likes');
		return new MovieResource($movie);
	}

	public function store(AddMovieRequest $request): MovieResource
	{
		$this->authorize('store', Movie::class);
		$attributes = $request->validated();

		$movie = Movie::create($attributes);
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

	public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
	{
		$this->authorize('update', $movie);
		$movie->title = $request->input('title');
		$movie->director = $request->input('director');
		$movie->description = $request->input('description');
		$movie->release_date = $request->input('release_date');

		if ($request->hasFile('poster')) {
			$poster = $request->file('poster');
			$filename = time() . '.' . $poster->getClientOriginalExtension();
			$path = $poster->storeAs('public/images', $filename);

			$relativePath = str_replace('public/', '', $path);

			$movie->poster = $relativePath;
			$movie->save();
		}
		$movie->save();
		return new MovieResource($movie);
	}

	public function destroy($id): JsonResponse
	{
		Movie::destroy($id);
		return response()->json(['message' => 'Movie deleted successfully'], 200);
	}
}
