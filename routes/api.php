<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataInventarisController;
use App\Http\Controllers\Api\DataAnggotaController;
use App\Http\Controllers\Api\AuthController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Resource Data Inventaris (Endpoint: /api/inventaris)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']); 
    
    Route::resource('inventaris', DataInventarisController::class)->only([
        'index', 'store', 'destroy', 'update'
    ]);

    // Resource Data Anggota (Endpoint: /api/anggota)
    Route::resource('anggota', DataAnggotaController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
});
