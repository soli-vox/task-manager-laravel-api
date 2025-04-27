<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/curent-user', [AuthController::class, 'currentUser']);     // GET      /current-user
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/tasks', [TaskController::class, 'index']);                 // GET      /tasks
    Route::post('/tasks', [TaskController::class, 'store']);                // POST     /tasks
    Route::get('/tasks/{task}', [TaskController::class, 'show']);           // GET      /tasks/1
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);       // PATCH    /tasks/1
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);     // DELETE   /tasks/1
});
