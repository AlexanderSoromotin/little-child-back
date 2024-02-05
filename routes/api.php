<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TrackController;
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

Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    // Здесь находятся ваши маршруты, доступные только администраторам

//    Route::post('/tracks', [TrackController::class, 'store']);
//    Route::patch('/tracks/{id}', [TrackController::class, 'update']);
//    Route::delete('/tracks/{id}', [TrackController::class, 'destroy']);

//    Route::post('/albums', [AlbumController::class, 'store']);
//    Route::patch('/albums/{id}', [AlbumController::class, 'update']);
//    Route::delete('/albums/{id}', [AlbumController::class, 'destroy']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});

Route::post('/auth/login', [AuthController::class, 'login']);

//Route::get('/tracks', [TrackController::class, 'index']);
//Route::get('/albums', [AlbumController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);



Route::any('/error-token', function () {
    return response(['message' => 'Invalid access token.'], 401);
})->name('login');
