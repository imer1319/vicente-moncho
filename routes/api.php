<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ValeController;
use App\Http\Controllers\ValeItemController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Proveedores
Route::get('apiproveedores', [ProveedorController::class, 'apiproveedores']);
Route::post('apiproveedores', [ProveedorController::class, 'store']);
Route::put('apiproveedores/{id}', [ProveedorController::class, 'update']);
Route::delete('apiproveedores/{id}', [ProveedorController::class, 'destroy']);

// Valets
Route::get('apivalets', [ValeController::class, 'apivalets']);
Route::post('apivalets', [ValeController::class, 'store']);
Route::put('apivalets/{id}', [ValeController::class, 'update']);
Route::delete('apivalets/{id}', [ValeController::class, 'destroy']);

Route::get('apivalets/validateForm', [ValeItemController::class, 'validateForm']);
