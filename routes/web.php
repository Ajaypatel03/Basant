<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::resource('login',LoginController::class);
Route::resource('login', LoginController::class)->only(['index', 'create','store']);
Route::resource('product', ProductController::class);
Route::get('/',[WebController::class,'index']);
Route::get('/categories/{category}', [CategoryController::class,'show'])->name('categories.show');