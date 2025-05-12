<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

//All Blog post route or api for Task-1

Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);


//All Route for user auth Task-2
Route::post('/register', [AuthController::class, 'register']);



//All route for task management Task-3
Route::post('/tasks', [TaskController::class, 'store']);
Route::patch('/tasks/{id}', [TaskController::class, 'update']);
Route::get('/tasks/pending', [TaskController::class, 'pending']);







Route::get('/hello', function () {
    return response()->json(['message' => "hello world"], 200);
});
