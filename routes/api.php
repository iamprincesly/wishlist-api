<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;

Route::get('/', function (Request $request) {
    return match ($request->showing) {
        'info' => phpinfo(),
        default => 'API is up and running.',
    };
});

Route::name('api.')->prefix('v1')->group(function () {
    Route::name('auth.')->prefix('auth')->controller(AuthController::class)->group(function () {

        Route::post('register', 'register')->name('register');

        Route::post('login', 'login')->name('login');

        Route::post('logout', 'logout')->middleware('auth:sanctum')->name('logout');
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::name('user.')->prefix('user')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::name('products.')->prefix('products')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');

            Route::prefix('wishlist')->name('wishlist.')->controller(WishlistController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{product}', 'addToWishlist')->name('add');
                Route::delete('/{product}', 'removeFromWishlist')->name('remove');
            });
        });

    });
});
