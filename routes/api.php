<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ImageController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('throttle:basic-auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('password/forgot-email-link', [AuthController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});

//auth api endpoints
Route::middleware(['auth:api', 'api.permission', 'throttle:api-general'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('profile')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('{id}', 'viewProfile')->name('profile.view');
            Route::put('{id}', 'updateProfile')->name('profile.update');
        });
    });

    //products
    Route::prefix('products')->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('product.view');
            Route::post('/', 'store')->name('product.create');
            // update products add categories to previously saved without category
            Route::put('{id}', 'update')->name('product.update');
            Route::delete('{id}', 'destroy')->name('product.delete');

            Route::get('trashed', 'trashed')->name('product.trashed.view');
            Route::post('{id}/restore', 'restore')->name('product.restore');
            Route::delete('{id}/force', 'forceDelete')->name('product.force-delete');

        });
    });

    //categories
    Route::prefix('categories')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/','index')->name('category.view');
            Route::post('/','store')->name('category.create');
            Route::put('{id}','update')->name('category.update');
            Route::delete('{id}','destroy')->name('category.delete');
        });
    });

    //permissions update
    Route::prefix('permissions')->group(function () {
        Route::controller(PermissionController::class)->group(function () {
            Route::post('/', 'assign')->name('permissions.assign');
            Route::delete('/',  'revoke')->name('permissions.revoke');
        });
    });

    //addd images to models for check morphic relationship
    Route::controller(ImageController::class)->middleware('throttle:uploads')->group(function () {
        Route::post('products/{product}/images', 'storeProductImage')->name('product.image.create');
        Route::post('categories/{category}/images', 'storeCategoryImage')->name('category.image.create');
    });
});




