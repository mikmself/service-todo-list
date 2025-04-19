<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function (){
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['create', 'edit']);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['create', 'edit']);
    Route::resource('todos', \App\Http\Controllers\TodoController::class)->except(['create', 'edit']);
})->middleware('api');
