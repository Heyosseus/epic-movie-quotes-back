<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenresController extends Controller
{
	public function index(): JsonResponse
	{
		$genres = Genre::all();
		return response()->json(['genres' => $genres], 200);
	}

	public function addGenres(Request $request, Movie $movies)
	{
		//		$movies = Movie::find($request->input('movie_id'));
		$genres = $request->input('genres');
		$movies->genres()->attach($genres);
		return response()->json(['Genres attached'], 200);
	}
}
