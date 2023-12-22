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

//auth
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

//middleware auth token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth', [App\Http\Controllers\Api\AuthController::class, 'auth']);
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    //profile
    Route::get('/profile', [App\Http\Controllers\Api\ProfileController::class, 'getProfile']);
    Route::post('/profile/change-password', [App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
    Route::post('/profile/change-profile', [App\Http\Controllers\Api\ProfileController::class, 'changeProfile']);

    //history
    Route::get('/history', [App\Http\Controllers\Api\HistoryController::class, 'getHistory']);
    Route::post('/history/detail', [App\Http\Controllers\Api\HistoryController::class, 'detailHistory']);
    Route::post('/upload', [App\Http\Controllers\Api\CheckController::class, 'upload3']);
});
