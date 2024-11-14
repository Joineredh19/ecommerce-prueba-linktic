<?php

use App\Http\Controllers\Api\OrdenController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/**
 * Rutas para exportación de datos
 */
Route::prefix('productos')->group(function () {
    Route::get('exportar', [ProductoController::class, 'export'])
        ->name('productos.exportar');
});

Route::prefix('ordenes')->group(function () {
    Route::get('exportar', [OrdenController::class, 'export'])
        ->name('ordenes.exportar');
});


    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('ordenes', OrdenController::class);
