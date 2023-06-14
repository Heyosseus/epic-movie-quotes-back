<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});

Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
	Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
	Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
});

Route::controller(\App\Http\Controllers\RecoveryPasswordController::class)->group(function () {
	Route::post('/forgot-password', [App\Http\Controllers\RecoveryPasswordController::class, 'store']);
	Route::put('/reset-password', [App\Http\Controllers\RecoveryPasswordController::class, 'update']);
});

// google auth
Route::controller(\App\Http\Controllers\GoogleAuthController::class)->group(function () {
	Route::get('/auth/google/redirect', [App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('google-auth');
	Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('google-auth-callback');
});

// movie routes
Route::controller(\App\Http\Controllers\MovieController::class)->group(function () {
	Route::get('/movies', [App\Http\Controllers\MovieController::class, 'index']);
	Route::post('/add-movies', [App\Http\Controllers\MovieController::class, 'store']);
	Route::post('/update-movies/{movie}', [App\Http\Controllers\MovieController::class, 'update']);
	Route::get('/movies/{movie}', [App\Http\Controllers\MovieController::class, 'show']);
	Route::delete('/movies/{movie}', [App\Http\Controllers\MovieController::class, 'destroy']);
});

// quote routes
Route::controller(\App\Http\Controllers\QuotesController::class)->group(function () {
	Route::get('/quotes/{movieId}', [App\Http\Controllers\QuotesController::class, 'index']);
	Route::post('/add-quotes', [App\Http\Controllers\QuotesController::class, 'store']);
	Route::post('/update-quotes/{quote}', [App\Http\Controllers\QuotesController::class, 'update']);
	Route::get('/quotes/{quote}', [App\Http\Controllers\QuotesController::class, 'show']);
	Route::delete('/quotes/{quote}', [App\Http\Controllers\QuotesController::class, 'destroy']);
});

//profile
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update']);

//genres
Route::get('/genres', [App\Http\Controllers\GenresController::class, 'index']);

Route::post('/add-genres', [App\Http\Controllers\GenresController::class, 'addGenres']);

Route::get('/check-session', function () {
	$isSessionActive = false;
	$isGoogleAuthenticated = session('google_authenticated') === true;

	if (auth()->check()) {
		$isSessionActive = true;
	}

	return response()->json([
		'isSessionActive'       => $isSessionActive,
		'isGoogleAuthenticated' => $isGoogleAuthenticated,
	]);
});
