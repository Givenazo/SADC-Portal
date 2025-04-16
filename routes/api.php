<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VideoApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CommentApiController;

// Authentication
Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    // Upload video
    Route::post('/videos/upload', [VideoApiController::class, 'upload']);
    // Download video
    Route::get('/videos/{video}/download', [VideoApiController::class, 'downloadVideo']);
    // Download script
    Route::get('/videos/{video}/script', [VideoApiController::class, 'downloadScript']);
    // Comment on video
    Route::post('/videos/{video}/comments', [CommentApiController::class, 'store']);
    // List available videos
    Route::get('/videos', [VideoApiController::class, 'index']);
});
