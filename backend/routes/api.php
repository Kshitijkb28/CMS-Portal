<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('posts', PostController::class)->names('api.posts');
    Route::patch('posts/{post}/publish', [PostController::class, 'publish'])->name('api.posts.publish');

    Route::apiResource('pages', PageController::class)->names('api.pages');

    Route::apiResource('categories', CategoryController::class)->names('api.categories');

    Route::apiResource('media', MediaController::class)
        ->only(['index', 'show', 'destroy'])
        ->names('api.media');
    Route::post('media/upload', [MediaController::class, 'store'])->name('api.media.upload');
});
