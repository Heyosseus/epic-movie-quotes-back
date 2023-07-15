<?php

use App\Http\Controllers\NotificationController;
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
	Route::post('/login', 'login');
	Route::post('/register', 'register');
	Route::get('/cookie-credentials', [App\Http\Controllers\AuthController::class, 'getDecryptedCredentials']);
});

Route::controller(\App\Http\Controllers\RecoveryPasswordController::class)->group(function () {
	Route::post('/forgot-password', 'store');
	Route::put('/reset-password', 'update');
});

// google auth
Route::controller(\App\Http\Controllers\GoogleAuthController::class)->group(function () {
	Route::get('/auth/google/redirect', 'redirect')->name('google-auth');
	Route::get('/auth/google/callback', 'callback')->name('google-auth-callback');
});

// movie routes
Route::controller(\App\Http\Controllers\MovieController::class)->group(function () {
	Route::get('/movies', 'index');
	Route::get('/all-movies', 'allMovies');
	Route::get('/search-movies/{query}', 'searchMovies');
	Route::post('/add-movies', 'store');
	Route::post('/update-movies/{movie}', 'update');
	Route::get('/movies/{movie}', 'show');
	Route::delete('/movies/{movie}', 'destroy');
});

// quote routes
Route::controller(\App\Http\Controllers\QuoteController::class)->group(function () {
	Route::get('/quotes ', 'index');
	Route::get('/search-quotes/{query}', 'searchQuotes');
	Route::post('/add-quotes', 'store');
	Route::post('/update-quotes/{quote}', 'update');
	Route::get('/show-quotes/{quote}', 'show');
	Route::delete('/quotes/{quote}', 'destroy');
});
//comments
Route::controller(\App\Http\Controllers\CommentController::class)->group(function () {
	Route::get('/comments/{quoteId}', 'index');
	Route::post('/add-comments', 'store');
});
//likes
Route::controller(\App\Http\Controllers\LikeController::class)->group(function () {
	Route::get('/likes/{quoteId}', 'index');
	Route::post('/quotes/{quote}/like/{user}', 'store')->middleware('auth:sanctum');
	Route::delete('/remove-likes', 'destroy');
});

//profile
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update']);

//genres
Route::get('/genres', [App\Http\Controllers\GenreController::class, 'index']);

Route::post('/add-genres', [App\Http\Controllers\GenreController::class, 'addGenres']);

// session
Route::get('/check-session', [App\Http\Controllers\SessionController::class, 'checkSession']);

// notifications
Route::post('/notifications/{user}/{type}', [App\Http\Controllers\NotificationController::class, 'notify'])->middleware('auth:sanctum');
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/{userId}/notifications', [NotificationController::class, 'getFilteredNotifications']);
Route::put('/notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');
Route::put('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->middleware('auth:sanctum');
