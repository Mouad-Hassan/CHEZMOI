<?php

use App\Http\Controllers\Api\AnnonceController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API ChezMoi (protégée partiellement par Laravel Sanctum)
|--------------------------------------------------------------------------
*/

// --- Public ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show']);

// --- Protégé par Sanctum ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/annonces', [AnnonceController::class, 'store']);
});
