<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\EventRegistrationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login']);
Route::apiResource('users', UserController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('detail', DetailOrderController::class);
Route::apiResource('reviews', ProductReviewController::class);
Route::apiResource('event/registrations', EventRegistrationController::class);