<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function (){
    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['create', 'edit']);
})->middleware('api');
