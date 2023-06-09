<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use Illuminate\Http\JsonResponse;

class GenresController extends Controller
{
	public function index(): JsonResponse
	{
		$genres = Genres::all();
		return response()->json(['genres' => $genres], 200);
	}
}
