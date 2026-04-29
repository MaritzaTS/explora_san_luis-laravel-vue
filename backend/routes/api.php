<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


// ====================================================
// RUTAS DE AUTENTICACIÓN (públicas)
// ====================================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verificar-codigo', [AuthController::class, 'verificarCodigo']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check-email', [AuthController::class, 'checkEmail']);
});


// ====================================================
// RUTAS DE AUTENTICACIÓN (protegidas)
// ====================================================
Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
