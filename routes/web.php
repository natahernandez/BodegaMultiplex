<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Rutas de Productos
    Route::resource('productos', ProductoController::class);
    Route::put('productos/{producto}/stock', [ProductoController::class, 'updateStock'])->name('productos.updateStock');
    Route::get('productos-datatable', [ProductoController::class, 'datatable'])->name('productos.datatable');
});
