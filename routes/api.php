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
	Route::get('/search-movies/{query}', [App\Http\Controllers\MovieController::class, 'searchMovies']);
	Route::post('/add-movies', [App\Http\Controllers\MovieController::class, 'store']);
	Route::post('/update-movies/{movie}', [App\Http\Controllers\MovieController::class, 'update']);
	Route::get('/movies/{movie}', [App\Http\Controllers\MovieController::class, 'show']);
	Route::delete('/movies/{movie}', [App\Http\Controllers\MovieController::class, 'destroy']);
});

// quote routes
Route::controller(\App\Http\Controllers\QuotesController::class)->group(function () {
	Route::get('/quotes/{movieId}', [App\Http\Controllers\QuotesController::class, 'index']);
	Route::get('/search-quotes/{query}', [App\Http\Controllers\QuotesController::class, 'searchQuotes']);
	Route::get('/news-feed', [App\Http\Controllers\QuotesController::class, 'newsFeed']);
	Route::post('/add-quotes', [App\Http\Controllers\QuotesController::class, 'store']);
	Route::post('/update-quotes/{quote}', [App\Http\Controllers\QuotesController::class, 'update']);
	Route::get('/show-quotes/{quote}', [App\Http\Controllers\QuotesController::class, 'show']);
	Route::delete('/quotes/{quote}', [App\Http\Controllers\QuotesController::class, 'destroy']);
});
//comments
Route::controller(\App\Http\Controllers\CommentsController::class)->group(function () {
	Route::get('/comments/{quoteId}', [App\Http\Controllers\CommentsController::class, 'index']);
	Route::post('/add-comments', [App\Http\Controllers\CommentsController::class, 'store']);
});
//likes
Route::controller(\App\Http\Controllers\LikesController::class)->group(function () {
	Route::get('/likes/{quoteId}', [App\Http\Controllers\LikesController::class, 'index']);
	Route::post('/add-likes', [App\Http\Controllers\LikesController::class, 'store']);
	Route::delete('/remove-likes', [App\Http\Controllers\LikesController::class, 'destroy']);
});

//profile
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update']);

//genres
Route::get('/genres', [App\Http\Controllers\GenresController::class, 'index']);

Route::post('/add-genres', [App\Http\Controllers\GenresController::class, 'addGenres']);

// session
Route::get('/check-session', [App\Http\Controllers\SessionController::class, 'checkSession']);

// notifications
Route::post('/notifications/{user}', [App\Http\Controllers\NotificationController::class, 'notify'])->middleware('auth:sanctum');
