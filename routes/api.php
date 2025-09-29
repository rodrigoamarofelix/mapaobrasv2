<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '2.0.0'
    ]);
});

Route::get('/test', function () {
    return response()->json(['test' => 'ok']);
});

Route::get('/test-filter', function (Request $request) {
    return response()->json([
        'message' => 'Test filter endpoint',
        'params' => $request->all()
    ]);
});

// Rotas da API de obras (sem middleware para evitar problemas de Redis)
Route::get('/filter', [ApiController::class, 'filter'])->name('api.filter');
Route::get('/obras', [ApiController::class, 'index'])->name('api.obras.index');
Route::get('/obras/{id}', [ApiController::class, 'show'])->name('api.obras.show');
Route::get('/projeto/{id_projeto}', [ApiController::class, 'getByProject'])->name('api.obras.by-project');
Route::get('/statistics', [ApiController::class, 'statistics'])->name('api.statistics');


