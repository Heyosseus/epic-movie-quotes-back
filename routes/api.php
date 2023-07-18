<?php

use App\Http\Controllers\Notifications\NotificationController;
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
	Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
});

Route::controller(\App\Http\Controllers\Auth\AuthController::class)->group(function () {
	Route::post('/login', 'login')->name('login');
	Route::post('/register', 'register')->name('register');
	Route::get('/cookie-credentials', [\App\Http\Controllers\Auth\AuthController::class, 'getDecryptedCredentials'])->name('cookie-credentials');
});

Route::controller(\App\Http\Controllers\Profile\RecoveryPasswordController::class)->group(function () {
	Route::post('/forgot-password', 'store')->name('forgot-password.store');
	Route::put('/reset-password', 'update')->name('reset-password.update');
});

// google auth
Route::controller(\App\Http\Controllers\Auth\GoogleAuthController::class)->group(function () {
	Route::get('/auth/google/redirect', 'redirect')->name('google-auth')->name('google.redirect');
	Route::get('/auth/google/callback', 'callback')->name('google-auth-callback')->name('google.callback');
});

// movie routes
Route::controller(\App\Http\Controllers\Movies\MovieController::class)->group(function () {
	Route::get('/movies', 'index')->name('movies.index');
	Route::get('/all-movies', 'allMovies')->name('movies.all-movies');
	Route::get('/search-movies/{query}', 'searchMovies')->name('movies.search-movies');
	Route::post('/add-movies', 'store')->name('movies.store');
	Route::post('/update-movies/{movie}', 'update')->name('movies.update');
	Route::get('/movies/{movie}', 'show')->name('movies.show');
	Route::delete('/movies/{movie}', 'destroy')->name('movies.destroy');
});

// quote routes
Route::controller(\App\Http\Controllers\Quotes\QuoteController::class)->group(function () {
	Route::get('/quotes ', 'index')->name('quotes.index');
	Route::get('/search-quotes/{query}', 'searchQuotes')->name('quotes.search-quotes');
	Route::post('/add-quotes', 'store')->name('quotes.store');
	Route::post('/update-quotes/{quote}', 'update')->name('quotes.update');
	Route::get('/show-quotes/{quote}', 'show')->name('quotes.show');
	Route::delete('/quotes/{quote}', 'destroy')->name('quotes.destroy');
});
//comments
Route::controller(\App\Http\Controllers\Notifications\CommentController::class)->group(function () {
	Route::get('/comments/{quoteId}', 'index')->name('comments.index');
	Route::post('/add-comments', 'store')->name('comments.store');
});
//likes
Route::controller(\App\Http\Controllers\Notifications\LikeController::class)->group(function () {
	Route::get('/likes/{quoteId}', 'index')->name('likes.index');
	Route::post('/quotes/{quote}/like/{user}', 'store')->name('likes.store')->middleware('auth:sanctum');
	Route::delete('/remove-likes', 'destroy')->name('likes.destroy');
});

//profile
Route::post('/profile', [\App\Http\Controllers\Profile\ProfileController::class, 'update'])->name('profile.update');

//genres
Route::get('/genres', [\App\Http\Controllers\Movies\GenreController::class, 'index'])->name('genres.index');

Route::post('/add-genres', [\App\Http\Controllers\Movies\GenreController::class, 'addGenres'])->name('genres.add-genres');

// session
Route::get('/check-session', [\App\Http\Controllers\Auth\SessionController::class, 'checkSession'])->name('session.check-session');

// notifications
Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::get('/notifications', [\App\Http\Controllers\Notifications\NotificationController::class, 'index'])->name('notification.index');
	Route::get('/user/{userId}/notifications', [NotificationController::class, 'getFilteredNotifications'])->name('notification.get-filtered-notifications');
	Route::post('/notifications/{user}/{type}', [\App\Http\Controllers\Notifications\NotificationController::class, 'notify']);
	Route::put('/notifications/{notification}/mark-as-read', [\App\Http\Controllers\Notifications\NotificationController::class, 'markAsRead'])->name('notification.mark-as-read');
	Route::put('/notifications/mark-all-read', [\App\Http\Controllers\Notifications\NotificationController::class, 'markAllAsRead'])->name('notification.mark-all-as-read');
});
