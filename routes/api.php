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
Route::get('/', function () {
    return response()->json(['message' => 'API is working!']);
});

// Ruta para el Health Check de Render
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'app_key' => config('app.key') ? 'set' : 'missing',
        'debug' => config('app.debug'),
        'db' => config('database.default'),
        'mongo_uri' => env('DB_URI'),
    ]);
});
