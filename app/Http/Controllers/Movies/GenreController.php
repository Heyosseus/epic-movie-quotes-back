<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
	public function index(): JsonResponse
	{
		$genres = Genre::all();
		return response()->json(['genres' => $genres], 200);
	}

	public function addGenres(Request $request, Movie $movies): JsonResponse
	{
		$genres = $request->genres;
		$movies->genres()->attach($genres);
		return response()->json(['Genres attached'], 200);
	}
}
