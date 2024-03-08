<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ValeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('proveedores', [ProveedorController::class,'index'])
    ->name('proveedores.index');
    Route::get('valets', [ValeController::class,'index'])
    ->name('valets.index');
    Route::get('reportes/{id}', [ValeController::class,'reportes'])->name('reportes');
});