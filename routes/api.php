<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpinionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Exporta las reseñas a un archivo CSV

Route::get('opinions/export', [OpinionController::class, 'export']);

// Crea automáticamente las rutas CRUD para nuestras opiniones
Route::apiResource('opinions', OpinionController::class);
