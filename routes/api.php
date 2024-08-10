<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryProductController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['middleware' => 'api'], function ($routes) {
    Route::controller(AuthController::class)->group(function ($routes) {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
    });

    Route::group(['middleware' => 'CustomJWTMiddleware'], function () {
        Route::controller(CategoryProductController::class)->group(function() {
            Route::get('/category-products', 'getAll');
            Route::get('/category-products/{id}', 'getById');
            Route::post('/category-products', 'create');
            Route::put('/category-products/{id}', 'update');
            Route::delete('/category-products/{id}', 'delete');
        });

        Route::controller(ProductController::class)->group(function() {
            Route::get('/products', 'index');
            Route::get('/products/{id}', 'show');
            Route::post('/products', 'store');
            Route::put('/products/{id}', 'update');
            Route::delete('/products/{id}', 'destroy');
        });

        // Route::resource('/products', ProductController::class);
    });

});

// Route::middleware('jwt:auth')

// Route::get('/greeting', function () {
//     return 'Hello World';
// })->middleware('auth:api');
