<?php

use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

Route::get('/', HomeController::class)->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[A-Za-z0-9-]+')
    ->name('pages.show');
