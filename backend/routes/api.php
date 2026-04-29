<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;

// ====================================================
// RUTAS DE AUTENTICACIÓN (públicas)
// ====================================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verificar-codigo', [AuthController::class, 'verificarCodigo']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check-email', [AuthController::class, 'checkEmail']);
    Route::get('/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/google/callback', [GoogleAuthController::class, 'callback']);
});


// ====================================================
// RUTAS DE AUTENTICACIÓN (protegidas)
// ====================================================
Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
