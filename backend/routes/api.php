<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlantController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\VideoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Resource Endpoints
Route::apiResource('plants', PlantController::class)->only(['index', 'show']);
Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);
Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::apiResource('videos', VideoController::class)->only(['index', 'show']);

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Protected Admin Endpoints
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/analytics', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::apiResource('plants', PlantController::class)->except(['index', 'show']);
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    Route::apiResource('videos', VideoController::class)->except(['index', 'show']);
});
