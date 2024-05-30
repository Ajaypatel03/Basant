<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// OPEN ROUTES
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [RegisterController::class, 'login']);
    // Route::post('logout', [RegisterController::class, 'logout'])->middleware('auth:api');

    Route::apiResource('category', CategoryController::class);
    Route::apiResource('product', ProductController::class);

//Protected Routes
Route::group(['middleware' => 'auth:api'], function() {
    
    Route::post('logout', [RegisterController::class, 'logout']);

});