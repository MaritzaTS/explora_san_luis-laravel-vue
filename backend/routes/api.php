<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


// ====================================================
// RUTAS DE AUTENTICACIÓN (públicas)
// ====================================================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verificar-codigo', [AuthController::class, 'verificarCodigo']);
});
