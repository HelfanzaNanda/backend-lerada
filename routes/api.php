<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;


//auth
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);
Route::post('register', [RegisterController::class, 'register']);

//product
Route::prefix('product')->group(function () {
    Route::get('/me', [ProductController::class, 'me']);
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/best-seller', [ProductController::class, 'bestSeller']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product:slug}', [ProductController::class, 'show']);
    Route::get('/{name}/search', [ProductController::class, 'search']);
    Route::put('/{product:id}/update', [ProductController::class, 'update']);
    Route::delete('/{product:id}/delete', [ProductController::class, 'delete']);
});
