<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\EntidadPublicaController;
use App\Http\Controllers\SitioTuristicoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\Admin\EntidadController as AdminEntidadController;




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

// ====================================================
// RUTAS PÚBLICAS (catálogo)
// ====================================================
Route::get('/tipos', [CatalogoController::class, 'tipos']);
Route::get('/entidades/{tipoEntidad:slug}', [EntidadPublicaController::class, 'index']);
Route::get('/sitios-turisticos', [SitioTuristicoController::class, 'index']);
Route::get('/eventos', [EventoController::class, 'index']);


// ====================================================
// RUTAS ADMIN (protegidas: auth + isAdmin)
// ====================================================
Route::prefix('admin')->middleware(['auth:api', 'is_admin'])->group(function () {

    // Entidades
    Route::get('/entidades', [AdminEntidadController::class, 'index']);
    Route::post('/entidades', [AdminEntidadController::class, 'store']);
    Route::put('/entidades/{id}', [AdminEntidadController::class, 'update']);
    Route::patch('/entidades/{id}/estado', [AdminEntidadController::class, 'cambiarEstado']);
});
