<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout',[AuthController::class,'logout']);
    Route::get('/users',[AuthController::class,'index']);
    Route::get('/deleteUsers/{id}',[AuthController::class,'delete']);

    Route::post('/addTodo',[TodoListController::class,'modify']);
    Route::get('/todo',[TodoListController::class,'index']);
    Route::get('/deleteTodo/{id}',[TodoListController::class,'delete']);
});

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);

