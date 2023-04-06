<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

// -- 26 MAR 2023 - Introduction to REST API
Route::get('/posts', [PostController::class, 'index']);

// -- 27 MAR 2023 - Custom API resources
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::get('/posts2/{id}', [PostController::class, 'show2']);

Route::post('/login', [AuthenticationController::class, 'login']);
// Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);

// -- 4 APR 2023 - Viewing user data
// Route::get('/me', [AuthenticationController::class, 'me'])->middleware(['auth:sanctum']);

// -- 5 APR 2023
Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::post('/posts', [PostController::class, 'store']);
    // 06 APR
    Route::patch('/posts/{id}', [PostController::class, 'update'])->middleware('post.owner');
    Route::delete('posts/{id}', [PostController::class, 'delete'])->middleware('post.owner');
});