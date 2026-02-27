<?php

use App\Http\Controllers\Api\V1\AuthController as V1AuthController;
use App\Http\Controllers\Api\V1\EventController as V1EventController;
use App\Http\Controllers\Api\V1\EventRegistrationController as V1EventRegistrationController;
use App\Http\Controllers\Api\V1\OrderController as V1OrderController;
use App\Http\Controllers\Api\V1\OrderDetailController as V1OrderDetailController;
use App\Http\Controllers\Api\V1\ProductController as V1ProductController;
use App\Http\Controllers\Api\V1\ReviewController as V1ReviewController;
use App\Http\Controllers\Api\V1\UserController as V1UserController;
use App\Http\Controllers\DetailOrderController as LegacyDetailOrderController;
use App\Http\Controllers\EventController as LegacyEventController;
use App\Http\Controllers\EventRegistrationController as LegacyEventRegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController as LegacyOrderController;
use App\Http\Controllers\ProductController as LegacyProductController;
use App\Http\Controllers\ProductReviewController as LegacyProductReviewController;
use App\Http\Controllers\UserController as LegacyUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [V1AuthController::class, 'login']);
        Route::post('/register', [V1AuthController::class, 'register']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('/me', [V1AuthController::class, 'me']);
            Route::post('/logout', [V1AuthController::class, 'logout']);
        });

        Route::apiResource('users', V1UserController::class);
        Route::apiResource('products', V1ProductController::class);
        Route::get('products/{product}/reviews', [V1ReviewController::class, 'byProduct']);
        Route::apiResource('orders', V1OrderController::class);
        Route::apiResource('order-details', V1OrderDetailController::class)
            ->parameters(['order-details' => 'orderDetail']);
        Route::apiResource('reviews', V1ReviewController::class)
            ->parameters(['reviews' => 'review']);
        Route::apiResource('events', V1EventController::class);
        Route::apiResource('event-registrations', V1EventRegistrationController::class)
            ->parameters(['event-registrations' => 'eventRegistration']);
    });
});

Route::middleware('legacy.deprecation')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', LegacyUserController::class);
        Route::apiResource('products', LegacyProductController::class);
        Route::apiResource('orders', LegacyOrderController::class);
        Route::apiResource('events', LegacyEventController::class);
        Route::apiResource('detail', LegacyDetailOrderController::class);
        Route::apiResource('reviews', LegacyProductReviewController::class);
        Route::apiResource('event/registrations', LegacyEventRegistrationController::class);
    });
});
