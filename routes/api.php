<?php

use App\Http\Controllers\GoogleAuthController;
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

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/forgot-password', [App\Http\Controllers\RecoveryPasswordController::class, 'store']);
Route::put('/reset-password', [App\Http\Controllers\RecoveryPasswordController::class, 'update']);

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google-auth-callback');

// movie routes
Route::get('/movies', [App\Http\Controllers\MovieController::class, 'index']);
Route::post('/add-movies', [App\Http\Controllers\MovieController::class, 'store']);
Route::get('/movies/{id}', [App\Http\Controllers\MovieController::class, 'show']);
