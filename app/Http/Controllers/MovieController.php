<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;
use http\Env\Request;

class MovieController extends Controller
{
	public function index()
	{
		$movies = Movie::orderBy('created_at', 'desc')->get();
		//		$movies = Movie::all();
		return response()->json(['movie' => $movies], 200);
	}

	public function show($id)
	{
		$movie = Movie::find($id);
		return response()->json(['movie' => $movie], 200);
	}

	public function store(AddMovieRequest $request)
	{
		$attr = $request->all();

		$movie = Movie::create($attr);

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
