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

// Ruta raíz para verificar que la API está funcionando
Route::get('/', function () {
    header('Content-Type: application/json');
    echo json_encode(['message' => 'API is working (raw)! ']);
    exit;
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
